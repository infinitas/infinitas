<?php
	/**
	 * Get emails in your app with cake like finds.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.models.datasources.reader
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class ImapSource extends ImapFunctions {

		public $driver = null;
		private $__isConnected = false;
		private $__connectionString = null;
		private $__baseConfigs = array(
			'global' => array(
				'username' => false,
				'password' => false,
				'email' => false,
				'server' => 'localhost',
				'type' => 'pop3',
				'ssl' => false
			),
			'imap' => array(
				'port' => 143
			),
			'pop3' => array(
				'port' => 110
			)
		);
		private $__connectionType = 'pop3';

		/**
		 * __construct()
		 *
		 * @param mixed $config
		 */
		function __construct($config) {
			parent::__construct($config);
		}

		/**
		 * describe the data
		 *
		 * @param mixed $model
		 * @return array the shcema of the model
		 */
		public function describe(&$model) {
			return $model->schema;
		}

		/**
		 * listSources
		 *
		 * list the sources???
		 *
		 * @return array sources
		 */
		public function listSources() {
			return array('listSources');
		}

		/**
		 * read data
		 *
		 * this is the main method that reads data from the datasource and
		 * formats it according to the request from the model.
		 *
		 * @param mixed $model the model that is requesting data
		 * @param mixed $query the qurey that was sent
		 *
		 * @return the data requested by the model
		 */
		public function read(&$Model, $query) {
			if (!$this->__connectToServer($Model)) {
				die('something wrong');
				exit;
			}

			switch ($Model->findQueryType) {
				case 'count':
					return array(
						array(
							$Model->alias => array(
								'count' => $this->_mailCount($query)
							)
						)
					);
					break;

				case 'all':
					$query['limit'] = $query['limit'] >= 1 ? $query['limit'] : 20;
					return $this->__getMails($Model, $query);
					break;

				case 'first':
					return array($this->__getMail($Model, $query));
					break;

				default:
					pr($Model->findQueryType);
					pr($query);
					exit;
					// find(list)
					pr(imap_fetch_overview($this->MailServer, '400:350', 0));
					exit;
					break;
			}

			return $result;
		}

		/**
		 * no clue
		 * @param <type> $Model
		 * @param <type> $func
		 * @param <type> $params
		 * @return <type>
		 */
		public function calculate(&$Model, $func, $params = array()) {
			$params = (array) $params;
			switch (strtolower($func)) {
				case 'count':
					return 'count';
					break;
			}
		}

		/**
		 * connect to the mail server
		 */
		private function __connectToServer($Model) {
			if ($this->__isConnected) {
				return true;
			}

			$Model->server['type'] = isset($Model->server['type']) && !empty($Model->server['type']) ? $Model->server['type'] : 'pop3';

			if ($Model->server['type'] == 'default' || !in_array($Model->server['type'], array_keys($this->__baseConfigs))) {
				// throw error bad config.
			}

			$config = array_merge($this->__baseConfigs['global'], $this->__baseConfigs[$Model->server['type']], $Model->server);
			$config['email'] = !empty($config['email']) ? $config['email'] : $config['username'];

			$this->__connectionType = $config['type'];

			switch ($config['type']) {
				case 'imap':
					$this->__connectionString = sprintf(
						'{%s:%s%s}',
						$config['server'],
						$config['port'],
						$config['ssl'] === true ? '/ssl' : ''
					);
					break;

				case 'pop3':
					$this->__connectionString = sprintf(
						'{%s:%s/pop3%s}',
						$config['server'],
						$config['port'],
						$config['ssl'] === true ? '/ssl' : ''
					);
					break;
			}

			try {
				$this->MailServer = imap_open($this->__connectionString, $config['username'], $config['password']);
			}

			catch (Exception $error) {
				pr(imap_last_error());
				pr($error);
				exit;
			}

			return $this->__isConnected = true;
		}

		/**
		 * Get the full email for a read / find(first)
		 *
		 * @param object $Model
		 * @param array $query
		 *
		 * @return array the email according to the find
		 */
		private function __getMail($Model, $query) {
			if (!isset($query['conditions'][$Model->alias . '.id']) || empty($query['conditions'][$Model->alias . '.id'])) {
				return array();
			}

			if ($this->__connectionType == 'imap') {
				$uuid = $query['conditions'][$Model->alias . '.id'];
			} else {
				$uuid = base64_decode($query['conditions'][$Model->alias . '.id']);
			}

			return $this->__getFormattedMail($Model, imap_msgno($this->MailServer, $uuid));
		}

		/**
		 * Get the emails
		 *
		 * The method for finding all emails paginated from the mail server, used
		 * by code like find('all') etc.
		 *
		 * @todo conditions / order other find params
		 *
		 * @param object $Model the model doing the find
		 * @param array $query the find conditions and params
		 * @return array the data that was found
		 */
		private function __getMails($Model, $query) {
			$pagination = $this->_figurePagination($query);
			
			$mails = array();
			for ($i = $pagination['start']; $i > $pagination['end']; $i--) {
				$mails[][$Model->alias] = $this->__getFormattedBasics($i);
			}

			unset($mail);

			return $mails;
		}

		/**
		 * get the basic details like sender and reciver with flags like attatchments etc
		 *
		 * @param int $message_id the id of the message
		 * @return array empty on error/nothing or array of formatted details
		 */
		private function __getFormattedBasics($message_id) {
			$mail = imap_header($this->MailServer, $message_id);
			$structure = imap_fetchstructure($this->MailServer, $mail->Msgno);
			
			$toName = isset($mail->to[0]->personal) ? $mail->to[0]->personal : $mail->to[0]->mailbox;
			$fromName = isset($mail->from[0]->personal) ? $mail->from[0]->personal : $mail->from[0]->mailbox;
			$replyToName = isset($mail->reply_to[0]->personal) ? $mail->reply_to[0]->personal : $mail->reply_to[0]->mailbox;

			if (isset($mail->sender)) {
				$senderName = isset($mail->sender[0]->personal) ? $mail->sender[0]->personal : $mail->sender[0]->mailbox;
			}

			else {
				$senderName = $fromName;
				$mail->sender = $mail->from;
				$mail->senderaddress = $mail->fromaddress;
			}

			$id = $this->__connectionType == 'imap' 
				? imap_uid($this->MailServer, $mail->Msgno)
				: str_replace(array('<', '>'), '', base64_encode($mail->message_id));

			return array(
				'id' => $id,
				'message_id' => $mail->message_id,
				'email_number' => $mail->Msgno,
				'subject' => htmlspecialchars($mail->subject),
				'size' => $mail->Size,
				'recent' => $mail->Recent,
				'unread' => (bool)trim($mail->Unseen),
				'flagged' => (bool)trim($mail->Flagged),
				'answered' => $mail->Answered,
				'draft' => $mail->Draft,
				'deleted' => $mail->Deleted,
				'to' => array(
					'email' => $mail->toaddress,
					'name' => $toName
				),
				'from' => array(
					'email' => sprintf('%s@%s', $mail->from[0]->mailbox, $mail->from[0]->host),
					'name' => $fromName
				),
				'reply_to' => array(
					'email' => sprintf('%s@%s', $mail->reply_to[0]->mailbox, $mail->reply_to[0]->host),
					'name' => $replyToName
				),
				'sender' => array(
					'email' => sprintf('%s@%s', $mail->sender[0]->mailbox, $mail->sender[0]->host),
					'name' => $replyToName
				),
				'attachments' => $this->_attachement($structure),
				'new' => !isset($mail->in_reply_to) ? true : false,
				'created' => $mail->date
			);
		}

		/**
		 * Get the meat of the email,
		 * @param int $message_id the id of the message to get
		 */
		private function __getFormattedMail($Model, $message_id) {
			$structure = imap_fetchstructure($this->MailServer, $message_id);

			$return[$Model->alias] = $this->__getFormattedBasics($message_id);

			$return[$Model->alias]['Email']['html'] = $dataTxt = $this->_getPart($this->MailServer, $message_id, 'TEXT/HTML', $structure);
			$return[$Model->alias]['Email']['text'] = $dataTxt = $this->_getPart($this->MailServer, $message_id, 'TEXT/PLAIN', $structure);

			$return[$Model->alias]['Attachements'] = $return[$Model->alias]['attachments'] === true ? $this->_attachement($structure, false) : array();

			return $return;
		}

	}

	class ImapFunctions extends DataSource {
		/**
		 * get the count of mails for the given conditions and params
		 *
		 * @todo conditions / order other find params
		 *
		 * @param array $query conditions for the query
		 * @return int the number of emails found
		 */
		protected function _mailCount($query) {
			return imap_num_msg($this->MailServer);
		}

		/**
		 * used to check / get the attachements in an email.
		 *
		 * @param object $structure the structure of the email
		 * @param bool $check should just check or get the attachements
		 *
		 * @return mixed, bool for check / array of attachements
		 */
		protected function _attachement($structure, $check = true) {
			$has = false;
			$attachments = array();
			if (isset($structure->parts)) {
				foreach ($structure->parts as $partOfPart) {
					if ($check) {
						$has = $has || $this->_attachement($partOfPart, $check);
					} else {
						$attachments[] = $this->_attachement($partOfPart, $check);
					}
				}
			} else {
				if (isset($structure->disposition)) {
					if (strtolower($structure->disposition) == 'attachment') {
						if ($check) {
							return true;
						} else {
							return array(
								'type' => $structure->subtype,
								'file' => $structure->dparameters[0]->value,
								'size' => $structure->bytes
							);
						}
					}
				}
			}

			if ($check) {
				return (bool) $has;
			}

			return $attachments;
		}

		/**
		 * Figure out how many and from where emails should be returned. Uses the
		 * current page and the limit set to figure out what to send back
		 *
		 * @param array $query the current query
		 * @return array of start / end int for the for() loop in the email find
		 */
		protected function _figurePagination($query) {
			$count = $this->_mailCount($query); // total mails
			$pages = ceil($count / $query['limit']); // total pages
			$query['page'] = $query['page'] <= $pages ? $query['page'] : $pages; // dont let the page be more than available pages

			$return = array(
				'start' => $query['page'] == 1
					? $count	// start at the end
					: ($pages - $query['page'] + 1) * $query['limit'], // start at the end - x pages
			);

			$return['end'] = $query['limit'] >= $count
				? 0
				: $return['start'] - $query['limit'];

			$return['end'] = $return['end'] >= 0 ? $return['end'] : 0;

			if (isset($query['order']['date']) && $query['order']['date'] == 'asc') {
				return array(
					'start' => $return['end'],
					'end' => $return['start'],
				);
			}

			return $return;
		}

		protected function _getMimeType($structure) {
			$primary_mime_type = array('TEXT', 'MULTIPART', 'MESSAGE', 'APPLICATION', 'AUDIO', 'IMAGE', 'VIDEO', 'OTHER');

			if ($structure->subtype) {
				return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
			}
			
			return 'TEXT/PLAIN';
		}

		protected function _getPart($MailServer, $msg_number, $mime_type, $structure = false, $part_number = false) {
			$prefix = null;
			if ($structure) {
				if ($mime_type == $this->_getMimeType($structure)) {
					if (!$part_number) {
						$part_number = '1';
					}
					
					$text = imap_fetchbody($MailServer, $msg_number, $part_number);
					if ($structure->encoding == 3) {
						return imap_base64($text);
					}

					else if ($structure->encoding == 4) {
						return imap_qprint($text);
					}

					else {
						return $text;
					}
				}

				/* multipart */
				if ($structure->type == 1) {
					while (list($index, $sub_structure) = each($structure->parts)) {
						if ($part_number) {
							$prefix = $part_number . '.';
						}
						
						$data = $this->_getPart($this->MailServer, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
						if ($data) {
							return $data;
						}
					}
				}
			}

			return false;
		}
	}