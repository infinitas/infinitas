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

	class ImapSource extends DataSource{
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
		public function describe(&$model){
			return $model->schema;
		}

		/**
		 * listSources
		 *
		 * list the sources???
		 *
		 * @return array sources
		 */
		public function listSources(){
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
		public function read(&$Model, $query){
			if(!$this->__connectToServer($Model)){
				die('something wrong');
				exit;
			}
			
			switch($Model->findQueryType){
				case 'count':
					return array(
						array(
							$Model->alias => array(
								'count' => $this->__mailCount($query)
							)
						)
					);
					break;

				case 'all':
					$query['limit'] = $query['limit'] >= 1 ? $query['limit'] : 20;
					return $this->__getMails($Model, $query);
					break;
			}
			
			return $result;
		}

		public function calculate(&$Model, $func, $params = array()) {
			$params = (array)$params;
			switch (strtolower($func)) {
				case 'count':
					return 'count';
					break;
			}
		}

		/**
		 * connect to the mail server
		 */
		private function __connectToServer($Model){
			if($this->__isConnected){
				return true;
			}
			
			$Model->server['type'] = isset($Model->server['type']) && !empty($Model->server['type']) ? $Model->server['type'] : 'pop3';

			if($Model->server['type'] == 'default' || !in_array($Model->server['type'], array_keys($this->__baseConfigs))){
				// throw error bad config.
			}

			$config = array_merge($this->__baseConfigs['global'], $this->__baseConfigs[$Model->server['type']], $Model->server);
			$config['email'] = !empty($config['email']) ? $config['email'] : $config['username'];

			switch($config['type']){
				case 'imap':
					$this->__connectionString = sprintf(
						'{%s:%s%s}INBOX',
						$config['server'],
						$config['port'],
						$config['ssl'] === true ? '/ssl' : ''
					);
					break;

				case 'pop3':
					$this->__connectionString = sprintf(
						'{%s:%s/pop3%s/novalidate-cert/notls}INBOX',
						$config['server'],
						$config['port'],
						$config['ssl'] === true ? '/ssl' : ''
					);
					break;
			}
			
			try{
				$this->MailServer = imap_open($this->__connectionString, $config['username'], $config['password']);
			}
			catch(Exception $error){
				pr(imap_last_error());
				pr($error);
				exit;
			}

			return $this->__isConnected = true;
		}

		/**
		 * get the count of mails for the given conditions and params
		 *
		 * @todo conditions / order other find params
		 *
		 * @param array $query conditions for the query
		 * @return int the number of emails found
		 */
		private function __mailCount($query){
			return imap_num_msg($this->MailServer);
		}

		/**
		 * Figure out how many and from where emails should be returned. Uses the
		 * current page and the limit set to figure out what to send back
		 * 
		 * @param array $query the current query
		 * @return array of start / end int for the for() loop in the email find
		 */
		private function __figurePagination($query){
			$count = $this->__mailCount($query); // total mails
			$pages = ceil($count / $query['limit']); // total pages
			$query['page'] = $query['page'] <= $pages ? $query['page'] : $pages; // dont let the page be more than available pages

			$return = array(
				'start' => $query['page'] == 1
					? $count										// start at the end
					: ($pages - $query['page'] + 1) * $query['limit'],	// start at the end - x pages
				
				'end' => $query['limit'] >= $count 
					? 0
					: ($pages - $query['page']) * $query['limit']
			);

			return $return;
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
		private function __getMails($Model, $query){
			$pagination = $this->__figurePagination($query);
			$mails = array();
			for($i = $pagination['start']; $i > $pagination['end']; $i--){
				$mail = imap_header($this->MailServer, $i);
				$structure = imap_fetchstructure($this->MailServer, $mail->Msgno);

				$toName      = isset($mail->to[0]->personal)       ? $mail->to[0]->personal       : $mail->to[0]->mailbox;
				$fromName    = isset($mail->from[0]->personal)     ? $mail->from[0]->personal     : $mail->from[0]->mailbox;
				$replyToName = isset($mail->reply_to[0]->personal) ? $mail->reply_to[0]->personal : $mail->reply_to[0]->mailbox;
				$senderName  = isset($mail->sender[0]->personal)   ? $mail->sender[0]->personal   : $mail->sender[0]->mailbox;
				
				$mails[][$Model->alias] = array(
					'id' => $mail->message_id,
					'email_number' => $mail->Msgno,
					'created' => $mail->date,
					'subject' => $mail->subject,
					'size' => $mail->Size,
					'recent' => $mail->Recent,
					'unseen' => $mail->Unseen,
					'flagged' => $mail->Flagged,
					'answered' => $mail->Answered,
					'draft' => $mail->Draft,
					'deleted' => $mail->Deleted,
					'to' => array(
						'pretty' => sprintf('% <%s>', $toName, $mail->toaddress),
						'name' => $toName,
						'email' => $mail->toaddress
					),
					'from' => array(
						'pretty' => sprintf('% <%s@%s>', $fromName, $mail->from[0]->mailbox, $mail->from[0]->host),
						'name' => $fromName,
						'email' => $mail->fromaddress
					),
					'reply_to' => array(
						'pretty' => sprintf('% <%s@%s>', $replyToName, $mail->reply_to[0]->mailbox, $mail->reply_to[0]->host),
						'name' => $replyToName,
						'email' => $mail->reply_toaddress
					),
					'sender' => array(
						'pretty' => sprintf('% <%s@%s>', $senderName, $mail->sender[0]->mailbox, $mail->sender[0]->host),
						'name' => $replyToName,
						'email' => $mail->senderaddress
					),
					'attachments' => $this->__attachement($structure)
				);
			}

			unset($mail);

			return $mails;
		}

		private function __attachement($structure, $check = true){
			$has = false;
			$attachments = array();
			if (isset($structure->parts)){
				foreach ($structure->parts as $partOfPart){
					if($check){
						$has = $has || $this->__attachement($partOfPart, $check);
					}
					else{
						$attachments[] = $this->__attachement($partOfPart, $check);
					}
				}
			}
			else{
				if (isset($structure->disposition)){
					if (strtolower($structure->disposition) == 'attachment'){
						if($check){
							return true;
						}
						else{
							return array(
								'type' => $structure->subtype,
								'file' => $structure->dparameters[0]->value,
								'size' => $structure->bytes
							);
						}
					}
				}
			}

			if($check){
				return (bool)$has;
			}

			return $attachments;
		}
	}