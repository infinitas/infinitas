<?php
    class TicketableBehavior extends ModelBehavior{
    	/**
    	 * Initiate behavior for the model using specified settings.
		 * Available settings:
		 *
		 * - timeout :: the date/time when the ticket will expire
		 *
		 * @param object $Model Model using the behaviour
		 * @param array $settings Settings to override for model.
		 * @access public
		 */
		function setup(&$Model, $settings = array()) {
			$default = array(
				'timeout' => date('Y-m-d H:i:s', time() + (24 * 60 * 60))
			);

			if (!isset($this->__settings[$Model->alias])) {
				$this->__settings[$Model->alias] = $default;
			}

			$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], ife(is_array($settings), $settings, array()));
			$this->Ticket = ClassRegistry::init('Management.Ticket');
		}

        /**
         * Create a new ticket.
         *
         * Create a new ticket by providing the data to be stored in the ticket.
         * If no information is passed false is returned, or the ticket id is returned.
         *
         * @param string $info the information to save. will be serialized so arrays are fine
         */
        function createTicket($info = null){
            if (!$info){
                return false;
            }

            $data['Ticket']['hash'] = md5(Configure::read('Security.salt').microtime(true));
            $data['Ticket']['data'] = serialize($info);

            $this->Ticket->create();
            if ($this->Ticket->save($data)){
                return $data['Ticket']['hash'];
            }
        }

        /**
         * Get a ticket.
         *
         * If something is found return the data that was saved or return false
         * if there is something wrong.
         *
         * @param string $ticket the ticket uuid
         */
        function getTicket($ticket = null){
            if (!$ticket){
                return false;
            }

            $data = $this->Ticket->find(
            	'first',
            	array(
            		'conditions' => array(
            			'Ticket.hash' => $this->Ticket
            		)
            	)
            );

            if (isset($data['Ticket']) && is_array($data['Ticket'])){
                if($this->Ticket->delete($ticket)){
                	return unserialize($data['Ticket']['data']);
                }
            }

            return false;
        }

        /**
         * Remove old tickets
         *
         * When things are done, remove old tickets that have expired.
         */
        function __destruct(){
            $this->Ticket->deleteAll(
            	array(
            		'Ticket.created < ' => date('Y-m-d H:i:s')
            	)
            );
        }
    }
?>