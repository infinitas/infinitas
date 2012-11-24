<?php
/**
 * TicketableBehavior
 *
 * @package Infinitas.Libs.Model.Behavior
 */

/**
 * TicketableBehavior
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class TicketableBehavior extends ModelBehavior {
/**
 * Initiate behavior for the model using specified settings.
 * Available settings:
 *
 * - timeout :: the date/time when the ticket will expire
 *
 * @param Model $Model the model using the behaviour
 * @param array $settings Settings to override for model.
 *
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		$default = array(
			'expires' => date('Y-m-d H:i:s', time() + (24 * 60 * 60))
		);

		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $default;
		}

		$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], (array)$settings);
		$this->Ticket = ClassRegistry::init('Management.Ticket');
	}

/**
 * Create a new ticket.
 *
 * Create a new ticket by providing the data to be stored in the ticket.
 * If no information is passed false is returned, or the ticket id is returned.
 *
 * @param Model $Model
 * @param string $info the information to save. will be serialized so arrays are fine
 *
 * @return string|boolean
 */
	public function createTicket(Model $Model, $info = null) {
		$this->removeOldTickets();

		if (!$info) {
			return false;
		}

		$data[$this->Ticket->alias]['data']	= serialize($info);
		$data[$this->Ticket->alias]['expires'] = $this->__settings[$Model->alias]['expires'];

		$this->Ticket->create();
		$return = $this->Ticket->save($data);
		if ($this->Ticket->id > 0) {
			return $this->Ticket->id;
		}

		return false;
	}

/**
 * Get a ticket.
 *
 * If something is found return the data that was saved or return false
 * if there is something wrong.
 *
 * @param Model $Model
 * @param string $ticket the ticket uuid
 *
 * @return array|boolean
 */
	public function getTicket(Model $Model, $ticket = null) {
		$this->cleanup();
		if (!$ticket) {
			return false;
		}

		$data = $this->Ticket->find('first', array(
			'conditions' => array(
				$this->Ticket->alias . '.id' => $ticket
			)
		));

		if (isset($data[$this->Ticket->alias]) && is_array($data[$this->Ticket->alias])) {
			if ($this->Ticket->delete($ticket)) {
				return unserialize($data[$this->Ticket->alias]['data']);
			}
		}

		return false;
	}

/**
 * Remove old tickets
 *
 * When things are done, remove old tickets that have expired.
 *
 * @return void
 */
	public function removeOldTickets() {
		$this->Ticket->deleteAll(
			array(
				$this->Ticket->alias . '.expires < ' => date('Y-m-d H:i:s')
			)
		);
	}

}