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
	class ImapSource extends DataSource {
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
				$this->thread = imap_thread($this->MailServer, SE_UID);
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

			unset($mail);

			return $mails;
		}

		/**
		 * get the basic details like sender and reciver with flags like attatchments etc
		 *
		 * @param int $message_id the id of the message
		 * @return array empty on error/nothing or array of formatted details
		 */
		private function __getFormattedMail($Model, $message_id) {
			$mail = imap_headerinfo($this->MailServer, $message_id);
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
			
			$return[$Model->alias] = array(
				'id' => $this->__getId($mail->Msgno),
				'message_id' => $mail->message_id,
				'email_number' => $mail->Msgno,
				'subject' => htmlspecialchars($mail->subject),
				'size' => $mail->Size,
				'recent' => $mail->Recent,
				'unread' => (int)(bool)trim($mail->Unseen),
				'flagged' => (int)(bool)trim($mail->Flagged),
				'answered' => $mail->Answered,
				'draft' => $mail->Draft,
				'deleted' => $mail->Deleted,
				'thread_count' => $this->_getThreadCount($mail),
				'attachments' => $this->_attachement($mail->Msgno, $structure),
				'in_reply_to' => isset($mail->in_reply_to) ? $mail->in_reply_to : false,
				'reference' => isset($mail->references) ? $mail->references : false,
				'new' => !isset($mail->in_reply_to) ? true : false,
				'created' => $mail->date
			);

			$return['To'] = array(
				'name' => $toName,
				'email' => $mail->toaddress				
			);

			$return['From'] = array(
				'name' => $fromName,
				'email' => sprintf('%s@%s', $mail->from[0]->mailbox, $mail->from[0]->host)				
			);

			$return['ReplyTo'] = array(
				'name' => $replyToName,
				'email' => sprintf('%s@%s', $mail->reply_to[0]->mailbox, $mail->reply_to[0]->host)				
			);
			
			$return['Sender'] = array(
				'name' => $replyToName,
				'email' => sprintf('%s@%s', $mail->sender[0]->mailbox, $mail->sender[0]->host)				
			);

			$return['Email'] = array(
				'html' => $this->_getPart($message_id, 'TEXT/HTML', $structure),
				'text' => $this->_getPart($message_id, 'TEXT/PLAIN', $structure)
			);

			App::import('Lib', 'Email.AttachmentDownloader');
			$this->AttachmentDownloader = new AttachmentDownloader($message_id);
			$return['Attachment'] = $this->_getAttachments($structure, $message_id);

			return $return;
		}

		/**
		 * Get any attachments for the current message, images, documents etc
		 * 
		 * @param <type> $structure
		 * @param <type> $message_id
		 * @return <type>
		 */
		protected function _getAttachments($structure, $message_id){
			$attachments = array();
			if(isset($structure->parts) && count($structure->parts)) {
				for($i = 0; $i < count($structure->parts); $i++) {

					$attachment = array(
						'message_id' => $message_id,
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
						
						$attachment['attachment'] = imap_fetchbody($this->MailServer, $message_id, $i+1);
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
			return imap_num_msg($this->MailServer);
		}

		/**
		 * used to check / get the attachements in an email.
		 *
		 * @param object $structure the structure of the email
		 * @param bool $count count them (true), or get them (false)
		 *
		 * @return mixed, int for check (number of attachements) / array of attachements
		 */
		protected function _attachement($message_id, $structure, $count = true) {
			$has = 0;
			$attachments = array();
			if (isset($structure->parts)) {
				foreach ($structure->parts as $partOfPart) {
					if ($count) {
						$has += $this->_attachement($message_id, $partOfPart, $count) == true ? 1 : 0;
					}

					else {
						$attachment = $this->_attachement($message_id, $partOfPart, $count);
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

		protected function _getPart($msg_number, $mime_type, $structure = null, $part_number = false) {
			$prefix = null;
			if (!$structure) {
				return false;
			}

			if ($mime_type == $this->_getMimeType($structure)) {
				$part_number = $part_number > 0 ? $part_number : 1;

				return imap_fetchbody($this->MailServer, $msg_number, $part_number);
			}
			
			/* multipart */
			if ($structure->type == 1) {
				foreach($structure->parts as $index => $sub_structure){
					if ($part_number) {
						$prefix = $part_number . '.';
					}

					$data = $this->_getPart($msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
					if ($data) {
						return $data;
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