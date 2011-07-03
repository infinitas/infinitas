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
	class EmailSource extends DataSource {
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
		 * @param mixed $Model
		 * @return array the shcema of the model
		 */
		public function describe($Model) {
			return $Model->schema;
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
		public function read($Model, $query) {
			if (!$this->__connectToServer($Model, $query)) {
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
					$query['limit'] = ($query['limit'] >= 1) ? $query['limit'] : 20;
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
		public function calculate($Model, $func, $params = array()) {
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
		private function __connectToServer($Model, $query) {
			if ($this->__isConnected) {
				return true;
			}
			
			if(!isset($query['conditions']) || empty($query['conditions'])){
				return false;
			}

			switch($query['conditions'][$Model->alias . '.type']){
				case 'pop3':
					App::import('Libs', 'Emails.drivers/' . $query['conditions'][$Model->alias . '.type']);
					$server = ucfirst(strtolower($query['conditions'][$Model->alias . '.type'])) . 'Socket';
					$this->Server = new $server();
					break;

				default:
					return false;
					break;
			}
			
			$connection = array();
			foreach($query['conditions'] as $k => $v){
				$connection[str_replace($Model->alias . '.', '', $k)] = $v;
			}
			$query['conditions'] = $connection;
			$this->Server->set($query['conditions']);

			return $this->__isConnected = $this->Server->login();
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
			}

			else {
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
				$mails[] = $this->__getFormattedMail($Model, $i);
			}

			return array_filter($mails);
		}

		/**
		 * get the basic details like sender and reciver with flags like attatchments etc
		 *
		 * @param int $messageId the id of the message
		 * @return array empty on error/nothing or array of formatted details
		 */
		private function __getFormattedMail($Model, $messageId) {
			$return = array();			
			$message = $this->Server->getMail($messageId);

			if(!$message){
				return false;
			}
			
			$return['Email']['size'] = $message['size'];
			$return['Email']['unique_id'] = $message['uid'];
			$return['Email']['Dkim'] = $message['headers']['dkim_signature'];
			$return['Email']['DomainKey'] = $message['headers']['domainkey_signature'];
			
			$return['Email']['Spam'] = $message['headers']['spam_status'];
			$return['Email']['Spam']['level'] = $message['headers']['spam_level'];

			$return['Sender']['date'] = $message['headers']['delivery_date']['date_time'];
			$return['Sender']['time_zone'] = $message['headers']['delivery_date']['time_zone'];

			$return['Reciver']['date'] = $message['headers']['date']['date_time'];
			$return['Reciver']['time_zone'] = $message['headers']['date']['time_zone'];
			$return['Reciver']['Path'] = $message['headers']['received'];

			$return['Message']['id'] = $message['id'];
			$return['Message']['size_formatted'] = $message['sizeReadable'];
			$return['Message']['message_number'] = $message['message_number'];
			$return['Message']['text'] = Set::extract('/body[type=text/plain]', $message);
			$return['Message']['text'] = isset($return['Message']['text'][0]['body']) ? $return['Message']['text'][0]['body'] : null;
			$return['Message']['html'] = Set::extract('/body[type=text/html]', $message);
			$return['Message']['html'] = isset($return['Message']['html'][0]['body']) ? $return['Message']['html'][0]['body'] : null;

			unset($message);

			return $return;

			// @todo sort out attaachments

			App::import('Lib', 'Emails.AttachmentDownloader');
			$this->AttachmentDownloader = new AttachmentDownloader($messageId);
			$return['Attachment'] = $this->_getAttachments($structure, $messageId);

			return $return;
		}

		/**
		 * Get any attachments for the current message, images, documents etc
		 * 
		 * @param <type> $structure
		 * @param <type> $messageId
		 * @return <type>
		 */
		protected function _getAttachments($structure, $messageId){
			$attachments = array();
			if(isset($structure->parts) && count($structure->parts)) {
				for($i = 0; $i < count($structure->parts); $i++) {

					$attachment = array(
						'message_id' => $messageId,
						'is_attachment' => false,
						'filename' => '',
						'mime_type' => '',
						'type' => '',
						'name' => '',
						'size' => 0,
						'attachment' => ''
					);

					if($structure->parts[$i]->ifdparameters) {
						foreach($structure->parts[$i]->dparameters as $object) {
							if(strtolower($object->attribute) == 'filename') {
								$attachment['is_attachment'] = true;
								$attachment['filename'] = $object->value;
							}
						}
					}

					if($structure->parts[$i]->ifparameters) {
						foreach($structure->parts[$i]->parameters as $object) {
							if(strtolower($object->attribute) == 'name') {
								$attachment['is_attachment'] = true;
								$attachment['name'] = $object->value;
							}
						}
					}
					if($attachment['is_attachment']) {

						$cachedAttachment = $this->AttachmentDownloader->alreadySaved($attachment);
						if($cachedAttachment !== false){
							$attachments[] = $cachedAttachment;
							continue;
						}
						
						$attachment['attachment'] = imap_fetchbody($this->MailServer, $messageId, $i+1);
						if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
							$attachment['format'] = 'base64';
						}
						elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
							$attachment['attachment'] = quoted_printable_decode($attachment['attachment']);
							//$attachment['format'] = 'base64';
						}

						$attachment['type'] = strtolower($structure->parts[$i]->subtype);
						$attachment['mime_type'] = $this->_getMimeType($structure->parts[$i]);
						$attachment['size'] = $structure->parts[$i]->bytes;

						$attachments[] = $this->AttachmentDownloader->save($attachment);
					}
				}
			}

			return $attachments;
		}




		/**
		 * get a usable uuid for use in the code
		 * 
		 * @param string $uuid in the format <.*@.*> from the email
		 *
		 * @return mixed on imap its the unique id (int) and for others its a base64_encoded string
		 */
		private function __getId($uuid){
			switch($this->__connectionType){
				case 'imap':
					return imap_uid($this->MailServer, $uuid);
					break;

				default:
					return str_replace(array('<', '>'), '', base64_encode($mail->message_id));
					break;
			}
		}
		

		/**
		 * get the count of mails for the given conditions and params
		 *
		 * @todo conditions / order other find params
		 *
		 * @param array $query conditions for the query
		 * @return int the number of emails found
		 */
		protected function _mailCount($query) {			
			return isset($this->Server->mailStats['totalCount']) ? $this->Server->mailStats['totalCount'] : 0;
		}

		/**
		 * used to check / get the attachements in an email.
		 *
		 * @param object $structure the structure of the email
		 * @param bool $count count them (true), or get them (false)
		 *
		 * @return mixed, int for check (number of attachements) / array of attachements
		 */
		protected function _attachement($messageId, $structure, $count = true) {
			$has = 0;
			$attachments = array();
			if (isset($structure->parts)) {
				foreach ($structure->parts as $partOfPart) {
					if ($count) {
						$has += ($this->_attachement($messageId, $partOfPart, $count) == true) ? 1 : 0;
					}

					else {
						$attachment = $this->_attachement($messageId, $partOfPart, $count);
						if(!empty($attachment)){
							$attachments[] = $attachment;
						}
					}
				}
			}

			else {
				if (isset($structure->disposition)) {
					if (strtolower($structure->disposition) == 'attachment') {
						if ($count) {
							return true;
						}

						else {							
							return array(
								'type' => $structure->type,
								'subtype' => $structure->subtype,
								'file' => $structure->dparameters[0]->value,
								'size' => $structure->bytes
							);
						}
					}
				}
			}

			if ($count) {
				return (int)$has;
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
			$query['page'] = ($query['page'] <= $pages) ? $query['page'] : $pages; // dont let the page be more than available pages

			if($query['page'] != 1){
				$count = ($pages - $query['page'] + 1) * $query['limit']; // start at the end - x pages
			}
			$return = array('start' =>  $count);

			$return['end'] = ($query['limit'] >= $count) ? 0 : $return['start'] - $query['limit'];
			$return['end'] = ($return['end'] >= 0) ? $return['end'] : 0;

			if (isset($query['order']['date']) && $query['order']['date'] == 'asc') {
				return array(
					'start' => $return['end'],
					'end' => $return['start'],
				);
			}

			return $return;
		}

		protected function _getMimeType($structure) {
			$primaryMimeType = array('TEXT', 'MULTIPART', 'MESSAGE', 'APPLICATION', 'AUDIO', 'IMAGE', 'VIDEO', 'OTHER');
			if ($structure->subtype) {
				return $primaryMimeType[(int) $structure->type] . '/' . $structure->subtype;
			}
			
			return 'TEXT/PLAIN';
		}

		protected function _getPart($msgNumber, $mimeType, $structure = null, $partNumber = false) {
			$prefix = null;
			if (!$structure) {
				return false;
			}

			if ($mimeType == $this->_getMimeType($structure)) {
				$partNumber = ($partNumber > 0) ? $partNumber : 1;

				return imap_fetchbody($this->MailServer, $msgNumber, $partNumber);
			}
			
			/* multipart */
			if ($structure->type == 1) {
				foreach($structure->parts as $index => $subStructure){
					if ($partNumber) {
						$prefix = $partNumber . '.';
					}

					$data = $this->_getPart($msgNumber, $mimeType, $subStructure, $prefix . ($index + 1));
					if ($data) {
						return quoted_printable_decode($data);
					}
				}
			}
		}

		/**
		 * Figure out how many emails there are in the thread for this mail.
		 *
		 * @param object $mail the imap header of the mail
		 * @return int the number of mails in the thred
		 */
		protected function _getThreadCount($mail){
			if(isset($mail->reference) || isset($mail->in_reply_to)){
				return '?';
			}
			
			return 0;
		}
	}