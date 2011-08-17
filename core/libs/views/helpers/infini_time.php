<?php
/**
 * InfiniTime Helper class file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.helpers
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Time Helper class for easy use of time data.
 *
 * Manipulation of time data.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.view.helpers
 * @link http://book.cakephp.org/view/1470/Time
 */
App::import('Helper', 'Time');

class InfiniTimeHelper extends TimeHelper {

/**
 * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
 *
 * @param string $dateString Datetime string
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Parsed timestamp
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function fromString($dateString, $userOffset = null) {
		return parent::fromString($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns a nicely formatted date string for given Datetime string.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function nice($dateString = null, $userOffset = null) {
		return parent::nice($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns a formatted descriptive date string for given datetime string.
 *
 * If the given date is today, the returned string could be "Today, 16:54".
 * If the given date was yesterday, the returned string could be "Yesterday, 16:54".
 * If $dateString's year is the current year, the returned string does not
 * include mention of the year.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Described, relative date string
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function niceShort($dateString = null, $userOffset = null) {
		return parent::niceShort($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns a partial SQL string to search for all records between two times
 * occurring on the same day.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param string $fieldName Name of database field to compare with
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Partial SQL string.
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function dayAsSql($dateString, $fieldName, $userOffset = null) {
		return parent::dayAsSql($dateString, $fieldName, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns true if given datetime string is today.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string is today
 * @access public
 */
	function isToday($dateString, $userOffset = null) {
		return parent::isToday($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns true if given datetime string is within this week
 * @param string $dateString
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string is within current week
 * @access public
 * @link http://book.cakephp.org/view/1472/Testing-Time
 */
	function isThisWeek($dateString, $userOffset = null) {
		return parent::isThisWeek($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns true if given datetime string is within this month
 * @param string $dateString
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string is within current month
 * @access public
 * @link http://book.cakephp.org/view/1472/Testing-Time
 */
	function isThisMonth($dateString, $userOffset = null) {
		return parent::isThisMonth($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns true if given datetime string is within current year.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @return boolean True if datetime string is within current year
 * @access public
 * @link http://book.cakephp.org/view/1472/Testing-Time
 */
	function isThisYear($dateString, $userOffset = null) {
		return parent::isThisYear($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns true if given datetime string was yesterday.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string was yesterday
 * @access public
 * @link http://book.cakephp.org/view/1472/Testing-Time
 *
 */
	function wasYesterday($dateString, $userOffset = null) {
		return parent::wasYesterday($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns true if given datetime string is tomorrow.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return boolean True if datetime string was yesterday
 * @access public
 * @link http://book.cakephp.org/view/1472/Testing-Time
 */
	function isTomorrow($dateString, $userOffset = null) {
		return parent::isTomorrow($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns a UNIX timestamp from a textual datetime description. Wrapper for PHP function strtotime().
 *
 * @param string $dateString Datetime string to be represented as a Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return integer Unix timestamp
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function toUnix($dateString, $userOffset = null) {
		return parent::toUnix($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns a date formatted for Atom RSS feeds.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function toAtom($dateString, $userOffset = null) {
		return parent::toAtom($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Formats date for RSS feeds
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function toRSS($dateString, $userOffset = null) {
		return parent::toRSS($dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns either a relative date or a formatted date depending
 * on the difference between the current time and given datetime.
 * $datetime should be in a <i>strtotime</i> - parsable format, like MySQL's datetime datatype.
 *
 * ### Options:
 *
 * - `format` => a fall back format if the relative time is longer than the duration specified by end
 * - `end` => The end of relative time telling
 * - `userOffset` => Users offset from GMT (in hours)
 *
 * Relative dates look something like this:
 *	3 weeks, 4 days ago
 *	15 seconds ago
 *
 * Default date formatting is d/m/yy e.g: on 18/2/09
 *
 * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
 * like 'Posted ' before the function output.
 *
 * @param string $dateString Datetime string or Unix timestamp
 * @param array $options Default format if timestamp is used in $dateString
 * @return string Relative time string.
 * @access public
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function timeAgoInWords($dateTime, $options = array()) {
		return parent::timeAgoInWords($dateTime, $this->__userOffset($dateTime, $options));
	}

/**
 * Alias for timeAgoInWords
 *
 * @param mixed $dateTime Datetime string (strtotime-compatible) or Unix timestamp
 * @param mixed $options Default format string, if timestamp is used in $dateTime, or an array of options to be passed
 *   on to timeAgoInWords().
 * @return string Relative time string.
 * @see TimeHelper::timeAgoInWords
 * @access public
 * @deprecated This method alias will be removed in future versions.
 * @link http://book.cakephp.org/view/1471/Formatting
 */
	function relativeTime($dateTime, $options = array()) {
		return parent::relativeTime($dateTime, $this->__userOffset($dateTime, $options));
	}

/**
 * Returns true if specified datetime was within the interval specified, else false.
 *
 * @param mixed $timeInterval the numeric value with space then time type.
 *    Example of valid types: 6 hours, 2 days, 1 minute.
 * @param mixed $dateString the datestring or unix timestamp to compare
 * @param int $userOffset User's offset from GMT (in hours)
 * @return bool
 * @access public
 * @link http://book.cakephp.org/view/1472/Testing-Time
 */
	function wasWithinLast($timeInterval, $dateString, $userOffset = null) {
		return parent::wasWithinLast($timeInterval, $dateString, $this->__userOffset($dateString, $userOffset));
	}

/**
 * Returns a formatted date string, given either a UNIX timestamp or a valid strtotime() date string.
 * This function also accepts a time string and a format string as first and second parameters.
 * In that case this function behaves as a wrapper for TimeHelper::i18nFormat()
 *
 * @param string $format date format string (or a DateTime string)
 * @param string $dateString Datetime string (or a date format string)
 * @param boolean $invalid flag to ignore results of fromString == false
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted date string
 * @access public
 */
	function format($format, $date = null, $invalid = false, $userOffset = null) {
		return format($format, $date, $invalid, $this->__userOffset($date, $userOffset));
	}

/**
 * Returns a formatted date string, given either a UNIX timestamp or a valid strtotime() date string.
 * It take in account the default date format for the current language if a LC_TIME file is used.
 *
 * @param string $dateString Datetime string
 * @param string $format strftime format string.
 * @param boolean $invalid flag to ignore results of fromString == false
 * @param int $userOffset User's offset from GMT (in hours)
 * @return string Formatted and translated date string @access public
 * @access public
 */
	function i18nFormat($date, $format = null, $invalid = false, $userOffset = null) {
		return i18nFormat($data, $format, $invalid, $this->__userOffset($date, $userOffset));
	}

	private function __userOffset($dateString, $userOffset = null) {
		if(is_array($userOffset)) {
			if(!array_key_exists('userOffset', $userOffset) || is_null($userOffset['userOffset'])) {
				$userOffset['userOffset'] = $this->__userOffset($dateString);
			}

			return $userOffset;
		}

		$this->Session = new CakeSession();
		if(is_null($userOffset) && $this->Session->check('Auth.User.time_zone')) {
			$timeZone = $this->Session->read('Auth.User.time_zone');

			if(phpversion() >= 5.2) {
				$date = new DateTime($dateString, new DateTimeZone('UTC'));
				$date->setTimezone(new DateTimeZone($timeZone));
				$userOffset = $date->getOffset() / 3600;
			} else {
				//TODO: add support for older versions
			}
		}

		return $userOffset;
	}
}