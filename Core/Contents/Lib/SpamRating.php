<?php

App::uses('Validation', 'Utility');
/**
 * Spam Rating lib
 *
 * This was originally a behavior writen by Jose Diaz-Gonzalez, its now been refactored into a lib that can 
 * be used on any content such as email contact forms, forums and so on.
 *
 * This lib makes it possible to accept comments from public without a capcha getting in the way.
 * It works by analising various parts of the content along with comparing to historic data.
 *
 * Besides a black list of keywords the lib checks user state, email, mx records, length, 
 * grammar, punctuation and so on.
 *
 * @link http://github.com/josegonzalez/cakephp-commentable-behavior
 * 
 * @package Infinitas.Contents.Lib
 * @since 0.9b
 * @author Jose Diaz-Gonzalez
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class SpamRating {

	protected $_default = array(
		'blacklist' => null,
		'url_text' => array('.html', '.info', '?', '&', '.de', '.pl', '.cn', '.ru'),
	);

/**
 * Stored internal config
 * 
 * @var array
 */
	protected $_config = array();

/**
 * cache of email domains by count of occurence in the supplied model
 * 
 * @var array
 */
	protected $_emailDomainList = array();

/**
 * Constructor
 * 
 * @param array $config configuration to load
 */
	public function __construct(array $config = array()) {
		$string = 'sex|chat|cam|free|nubiles|webcam|porn|loan|cialis|gratis|kamagra|tramadol|videos|viagra|casino|sunglasses|prescription|hydrocodone|valium|sale|payday|sexy|credit|' .
			'xxx|drug|dosage|online|generic|oakley|shades|levitra|camgirls|gossip|jewelry|swarovski|hottest|wet|alcohol|smut|livesex|juventus|overdose|lender|schmooze|cum|meds';

		$this->_default['blacklist'] = explode('|', strtolower($string));

		if (!empty($config)) {
			$this->config($config);
		}
	}

/**
 * Change the config
 * 
 * @param array $config the new config to load
 * @return void
 */
	public function config(array $config) {
		$this->_config = array_merge($this->_default, array_filter($this->_config), array_filter($config));
	}

	public function outcome(array $data, $points = null) {
		if ($points === null) {
			$points = $this->rate($data);
		}

		$mxRecord = $this->_mxRecord($data[$this->_config['column_email']]);
		switch (true) {
			case $points < -50: 
				$status = 'delete';
				break;

			case $points < -10: 
				$status = 'spam';
				break;

			case $points <= 0 || !$mxRecord: 
				$status = 'pending'; 
				break;

			case $points > 0:
				$status = 'approved'; 
				break;
		}

		return array(
			'points' => $points,
			'status' => $status,
			'active' => $status == 'approved' ? 1 : 0,
			'mx_record' => $mxRecord
		);
	}

	public function rate(array $data, array $options = array()) {
		if (empty($options)) {
			$options = $this->_defaultRateOptions();
		}
		$score = 0;
		$fails = 0;
		foreach ($options as $method) {
			$thisRound = $this->{$method}($data);
			if ($thisRound < 0) {
				$fails++;
			}
			$score += $thisRound;
		}
		if ($fails / count($options) * 100 > 30) {
			$score /= .3;
		}
		return $score;
	}

	protected function _defaultRateOptions() {
		$options = array();
		foreach (get_class_methods($this) as $method) {
			if (strlen($method) > 4 && substr($method, 0, 4) == 'rate') {
				$options[] = $method;
			}
		}

		return $options;
	}

/**
 * Get the rating of the mx record
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateMxRecord($data) {
		return self::_mxRecord($data[$this->_config('email')]) ? 1 : -10;
	}

/**
 * adds points based on the amount and length of links in the comment
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateLinks($data) {
		$links = $this->_getLinks($data);

		$linkCount = count($links);
		$length = mb_strlen($data[$this->_config('content')]);
		// How many links are in the body
		// -1 per link if over 2, otherwise +2 if less than 2
		$maxLinks = (int)$this->_config('maximum_links') ?: 2;

		$offset = $linkCount > $maxLinks ? 0 : 3;
		$points = ($linkCount * -1) + $offset;
		// URLs that have certain words or characters in them
		// -1 per blacklisted word
		// URL length
		// -1 if more then 30 chars
		foreach ($links as $link) {
			foreach ((array)$this->_config('url_text') as $word) {
				if (stripos($link, $word) !== false) {
					$points--;
				}
			}

			foreach ((array)$this->_config('blacklist') as $keyword) {
				if (stripos($link, $keyword) !== false) {
					$points--;
				}
			}

			$points = (strlen($link) >= 30) ? $points - 1 : $points;
		}

		return $points;
	}

/**
 * rate according to the lenght of the text
 *
 * Rate the length of the comment. if the length is greater than the required
 * and there are no links then 2 points are added. with links only 1 point
 * is added. if the lenght is too short 1 point is deducted
 * 
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateLength($data) {
		// How long is the body
		// +2 if more then 20 chars and no links, -1 if less then 20
		$links = $this->_getLinks($data);
		$urlLength = mb_strlen(implode('', $links));
		$length = strip_tags(mb_strlen($data[$this->_config('content')])) - $urlLength;
		$linkCount = count($links);

		$minLenght = (int)$this->_config('minimum_length');
		if (!$minLenght) {
			$minLenght = 20;
		}

		if ($length < $minLenght) {
			if ($linkCount > 0) {
				return -5;
			}
			
			return - 1;
		}
		if ($length >= $minLenght && $linkCount < 1) {
			return 2;
		}
		if ($length >= $minLenght && $linkCount == 1) {
			return 1;
		}

		return 0;
	}

/**
 * check for blacklisted words
 *
 * Checks the text to see if it contains any of the blacklisted words.
 * If there are, 1 point is deducted for each match.
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateKeywords($data) {
		$points = 0;

		foreach ($this->_config('blacklist') as $keyword) {
			if (stripos($data[$this->_config('content')], $keyword) !== false) {
				--$points;
			}
		}

		return $points;
	}

/**
 * rate according to the start of the comment
 *
 * Checks the first word against the blacklist keywords. if there is a
 * match then 10 points are deducted.
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateStartingWord($data) {
		$data = trim($data[$this->_config('content')]);
		$firstWord = mb_substr($data, 0, stripos($data, ' '));

		if (!in_array(mb_strtolower($firstWord), $this->_config('blacklist'))) {
			return 0;
		}

		return - 10;
	}

/**
 * rate according to the structure of words
 *
 * For any group of 5 or more letters points are deducted. This gives the content less score
 * for cases like 'looooooooooool :D'
 *
 * Rate according to the text. Generaly words do not contain more than
 * a few consecutive consonants. -1 point is given per 5 consecutive
 * consonants.
 *
 * If most of the text is not ascii it gets points deducted.
 *
 * If the content is all caps or all lower case points are deducted.
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateBody($data) {
		$points = 0;
		$content = $data[$this->_config('content')];

		preg_match_all('/[A-Z]{5,}/', $content, $matches);
		$points -= count($matches[0]);

		preg_match_all('/(.)\1\1\1/', $content, $matches);
		$points -= count($matches[0]);

		preg_match_all('/[^aeiou\s]{5,}+/i', $content, $matches);
		$points -= count($matches[0]);

		preg_match_all('/[^a-z0-9 ]/i', $content, $matches);
		$matches = implode('', $matches[0]);
		if ($matches && (strlen($content) - strlen($matches) / strlen($content)) * 100 < 70) {
			$points -= 5;
		}

		$matches = -15 + count(explode(' ', trim($content)));
		if ($matches < 0) {
			$points += $matches;
		}

		if (!preg_match('/\b[A-Z]/', $content)) {
			$points--;
		}

		if (!preg_match('/[a-z]/', $content)) {
			$points -= 2;
		}

		return $points;
	}

/**
 * rate according to past history
 *
 * Check previous comments by the same user. If they have been marked as
 * active they get points added, if they are marked as spam points are
 * deducted.
 *
 * on dogmatic69.com 95% of the spam had a number as the email address,
 * There is hardly any people that have a number as the email address.
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateEmail($data) {
		$emailUser = $this->_getEmailUser($data[$this->_config('column_email')]);
		if ((int)$emailUser > 0 || !preg_match('/[a-z0-9]/i', $emailUser)) {
			return -15;
		}

		if (!$this->_config('model') instanceof Model) {
			return 0;
		}

		$comments = $this->_fetchPreviousComments($data);

		$points = 0;
		foreach ($comments as $comment) {
			switch($comment[$this->_config('model')->alias]['status']) {
				case 'approved':
					++$points;
					break;

				case 'spam':
					--$points;
					break;
			}
		}

		return $points;
	}

	protected function _getEmailDomain($email) {
		return end(explode('@', $email));
	}

	public function _getEmailUser($email) {
		return current(explode('@', $email));
	}

	public function rateWebsite($data) {
		$points = 0;
		$website = $data[$this->_config('website')];

		if (empty($website)) {
			return $points;
		}
		
		if (strstr($website, '#')) {
			$points -= 3;
		}

		$length = strlen($website);
		if ($length > 100) {
			$points -= 20;
		} else if ($length > 50) {
			$points -= 7;
		} else if ($length > 30) {
			$points -= 2;
		}

		foreach ((array)$this->_config('url_text') as $word) {
			if (stripos($website, $word) !== false) {
				$points--;
			}
		}

		foreach ((array)$this->_config('blacklist') as $keyword) {
			if (stripos($website, $keyword) !== false) {
				$points--;
			}
		}
	}

/**
 * Rate the domian of the email used
 *
 * This is not used when the user is logged in as it would affect many people using popular email
 * hosts such as gmail. if they are not logged in the domain is compared with the popular domains 
 * in the system and used to score the domain
 *
 * The more often the domain occurs the less points they are given.
 * 
 * @param array $data the data being checked
 * @return array
 */
	public function rateDomain($data) {
		if ($this->rateAccount($data) > 0) {
			return 0;
		}
		if (empty($data[$this->_config('email')])) {
			return 0;
		}
		
		$domains = $this->_getEmailDomainList();
		$commentDomain = $this->_getEmailDomain($this->_config('email'));
		foreach ($domains as $k => $domain) {
			if ($domain['domain'] == $commentDomain) {
				return - $k;
			}
		}

		return 0;
	}

	protected function _getEmailDomainList() {
		$Model = $this->_config('model');
		if (!$Model instanceof Model) {
			return array();
		}

		if (empty($this->_emailDomainList)) {
			$Model->virtualFields['count'] = 'COUNT(*)';
			$Model->virtualFields['domain'] = 'SUBSTRING_INDEX( SUBSTR( email, INSTR( email,  \'@\' ) +1 ) ,  \'.\', 10 )';
			$this->_emailDomainList = Hash::extract($Model->find('all', array(
				'fields' => array(
					'domain',
					'count',
				),
				'group' => array(
					'domain'
				),
				'order' => array(
					'count' => 'desc'
				),
				'limit' => 10
			)), '{n}.{s}');
		}

		return $this->_emailDomainList;
	}

/**
 * Get previous comments with the same email address
 * 
 * @param array $data The data being checked
 * 
 * @return array
 */
	protected function _fetchPreviousComments(array $data) {
		$Model = $this->_config('model');

		return $Model->find('all', array(
			'fields' => array(
				$Model->alias . '.id',
				$Model->alias . '.status'
			),
			'conditions' => array(
				$Model->alias . '.' . $this->_config('column_email') => $data[$this->_config('column_email')],
				//$Model->alias . '.active' => 1
			),
			'contain' => false
		));
	}

/**
 * Deduct points if it is a copy of any other comments in the database.
 *
 * @param array $data array the data from the form
 *
 * @return integer
 */
	public function rateByPreviousComment($data) {
		$Model = $this->_config('model');
		if (!$Model instanceof Model) {
			return 0;
		}

		$previousComments = $Model->find('count', array(
			'conditions' => array(
				$Model->alias . '.' . $this->_config('content') => $data[$this->_config('content')]
			),
			'contain' => false
		));

		return 0 - $previousComments;
	}

/**
 * Check the users account
 *
 * If the user is logged in (there is a session), or its via cli and the data has a user id
 * its assumed to be not spammy
 * 
 * @param array $data the data being checked
 * 
 * @return integer
 */
	public function rateAccount($data) {
		$userId = $this->_config('user_id');
		if (AuthComponent::user('id') || (php_sapi_name() == 'cli' && array_key_exists($userId, $data) && $data[$userId])) {
			return 5;
		}

		return 0;
	}

/**
 * Internal method for reading config values without needing to check
 * 
 * @param string $field The config field to read
 * 
 * @return string|integer|array
 */
	protected function _config($field) {
		if (empty($this->_config[$field])) {
			return $field;
		}

		return $this->_config[$field];
	}

/**
 * Check the MX record of the supplied email address
 * 
 * @param string $email the email address to check
 * 
 * @return boolean|string
 */
	protected function _mxRecord($email) {
		preg_match(
			'/@((?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel))$/i',
			$email,
			$host
		);

		if (empty($host[1])) {
			return false;
		}

		if (function_exists('getmxrr')) {
			return getmxrr($host[1], $mxhosts);
		} else if (function_exists('checkdnsrr')) {
			return checkdnsrr($host[1], 'MX');
		}

		return gethostbynamel($host[1]);
	}

/**
 * Extract the links from the content
 *
 * Borrowed from http://daringfireball.net/2010/07/improved_regex_for_matching_urls
 * 
 * @param array $data the data being processed
 * @return array
 */
	protected function _getLinks($data) {
		$links = preg_match_all(
			"/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))/",
			$data[$this->_config('content')],
			$matches
		);

		if (!empty($matches[0])) {
			return $matches[0];
		}

		return array();
	}

}