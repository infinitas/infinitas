<?php
try {
	throw new Exception('Please use the sequence behavior, this ones going.');
} catch (Exception $e) {
	echo 'Depreciated: ',  $e->getMessage(), ' Where: ', __METHOD__, ' Line: ',  __LINE__, "\n";
}
exit;
/**
* OrderedBehavior
*
* @developer Alexander Morland ( aka. alkemann)
* @license MIT
* @version 2.1
* @modified 27. august 2008
*
* This behavior lets you order items in a very similar way to the tree
* behavior, only there is only 1 level. You can however have many
* independent lists in one table. Usually you use a foreign key to
* set / see what list you are in (see example bellow) or if you have
* just one list (or several lists, but no association) you can just
* use a field called "order_id" and set it manually.
*
* What it does:
*
* It manages the creation and updating of the order field. It
* also sets the models order property to this field. When adding new
* nodes or deleting old ones, this behavior will do the necisary changes
* to keep the list working properly. It is build to be completely
* automagic after the initial configuration by letting it know
* your foreign_key and weight fields.
*
* Usage example :
*
* Lets say you have books with pages and want the pages ordered
* by page number (obviously a book sorted alphabetically would be
* silly). So you have these models:
*
* Book hasMany Page
* Page belongsTo Book
*
* The Page model has fields :
*
* id
* content
* book_id
* page_number
*
* To set up this behavior we add this property to the Page model :
*
* var $actsAs = array('Ordered' => array(
*                'field'         => 'page_number',
*                'foreign_key'     => 'book_id'
*            ));
*
* Now when you save a new page (no changes needed to action or view,
* but leave page_number out of the form), it will be added to the end
* of the book.
*
* When deleting, the weights will automatically be adjusted to fill in
* the vacum.
*
* NB! Note that if using Model::deleteAll() it is VERY important that you
* assign it to use callbacks 'beforeDelete' and 'afterDelete', like this:
*
* // in controller action
* $this->Page->deleteAll(array('user_id'=>22),true,array('beforeDelete','afterDelete'));
*
* Now lets say the last two pages to be created got made in the wrong
* order, so you want to move the last page "up" one space. With the
* a simple controller call to the model like this that can be achieved:
*
* // in a controller action :
* $this->Page->moveup($id);
* // the id here is the id of the newest page
*
* You find that the first page you made is suppose to be the 5 pages later:
*
* // in a controller action :
* $this->Page->movedown($id, 5);
*
* Also you discovered that in the first page got put in the middle. This
* can easily be moved first by doing this :
*
* // in a controller action :
* $this->Page->moveup($id,true);
* // true will move it to the extre in that direction
*
* You can also use actions to find out if the node is first or last page :
*
*     - isfirst($id)
*     - islast($id)
*
* And a last feature is the ability to sort the list by any field
* you want and have it set weights based on that. You do that like this :
*
* //in controller action :
* $this->Page->sortby('content DESC', $book_id);
* // dont ask me why you would sort the pages of a book by its content lol
*
* Note that this behaviour will also let you sort an entire table as one list.
* To do that you simply set the 'foreign_key' to false (and dont create the field
* in the table). Now there will only be one set of weights. (Note you need the weight
* field as normal)
*/
class OrderedBehavior extends ModelBehavior {
	var $name = 'Ordered';

	/**
	* field : (string) The field to be ordered by.
	*
	* foreign_key : (string) The field to identify one SET by.
	*                     Each set has their own order (ie they start at 1).
	*                  Set to FALSE to not use this feature (and use only 1 set)
	*/
	var $_defaults = array(
		'field' => 'ordering',
		'foreign_key' => 'order_id'
		);

	function setup(&$Model, $config = array()) {
		if (!is_array($config)) {
			$config = array();
		}
		$this->settings = array_merge($this->_defaults, $config);
	}

	function beforedelete(&$Model) {
		$Model->data = $Model->read();
		$highest = $this->_highest($Model);
		if ($Model->data[$Model->alias][$Model->primaryKey] == $highest[$Model->alias][$Model->primaryKey]) {
			$Model->data = null;
		}
	}

	function afterdelete(&$Model) {
		if ($Model->data) {
			// What was the weight of the deleted model?
			$old_weight = $Model->data[$Model->alias][$this->settings['field']];
			// update the weight of all models of higher weight by
			$action = array($Model->alias . '.' . $this->settings['field'] => $Model->alias . '.' . $this->settings['field'] . ' - 1');
			$conditions = array($Model->alias . '.' . $this->settings['field'] . ' >' => $old_weight);
			if ($this->settings['foreign_key']) {
				$conditions[$this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
			}
			// decreasing them by 1
			return $Model->updateAll($action, $conditions);
		}
		return true;
	}

	/**
	* Sets the weight for new items so they end up at end
	*
	* @todo add new model with weight. clean up after
	* @param Model $Model
	*/
	function beforesave(&$Model) {
		// Check if weight id is set. If not add to end, if set update all
		// rows from ID and up
		if (!isset($Model->data[$Model->alias][$Model->primaryKey])) {
			// get highest current row
			$highest = $this->_highest($Model);
			// set new weight to model as last by using current highest one + 1
			$Model->data[$Model->alias][$this->settings['field']] = $highest[$Model->alias][$this->settings['field']] + 1;
		}
		return true;
	}

	/**
	* Moving a node to specific weight, it will shift the rest of the table to make room.
	*
	* @param Object $Model
	* @param int $id The id of the node to move
	* @param int $new_weight the new weight of the node
	* @return boolean True of move successful
	*/
	function moveto(&$Model, $id = null, $new_weight = null) {
		if (!$id || !$new_weight || $new_weight < 1) {
			return false;
		}
		// fetch the model and its old weight
		$old_weight = $this->_read($Model, $id);
		if (empty($Model->data)) {
			return false;
		}
		// check if new weight is too big
		$highest = $this->_highest($Model);
		if ($new_weight > $highest[$Model->alias][$this->settings['field']]) {
			return false;
		}
		$conditions = array();
		if ($this->settings['foreign_key']) {
			$conditions[$this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
		}
		// give Model new weight
		$Model->data[$Model->alias][$this->settings['field']] = $new_weight;
		if ($new_weight == $old_weight) {
			// move to same location?
			return false;
		} elseif ($new_weight > $old_weight) {
			// move all nodes that have weight > old_weight AND <= new_weight up one (-1)
			$action = array($this->settings['field'] => $this->settings['field'] . ' - 1');
			$conditions[$this->settings['field'] . ' <='] = $new_weight;
			$conditions[$this->settings['field'] . ' >' ] = $old_weight;
		} else { // $new_weight < $old_weight
			// move all where weight >= new_weight AND < old_weight down one (+1)
			$action = array($this->settings['field'] => $this->settings['field'] . ' + 1');
			$conditions[$this->settings['field'] . ' >='] = $new_weight;
			$conditions[$this->settings['field'] . ' <' ] = $old_weight;
		}
		$Model->updateAll($action, $conditions);
		return $Model->save(null, false);
	}

	/**
	* Take in an order array and sorts the list based on that order specification
	* and creates new weights for it. If no foreign key is supplied, all lists
	* will be sorted.
	*
	* @todo foreign key independent
	* @param Object $Model
	* @param array $order
	* @param mixed $foreign_key $returns boolean true if successfull
	*/
	function sortby(&$Model, $order, $foreign_key = null) {
		$fields = array($Model->primaryKey, $this->settings['field']);
		$conditions = array(1 => 1);
		if ($this->settings['foreign_key']) {
			if (!$foreign_key) {
				return false;
			}
			$fields[] = $this->settings['foreign_key'];
			$conditions = array($this->settings['foreign_key'] => $foreign_key);
		}

		$Model->recursive = - 1;
		$all = $Model->find('all', array(
				'fields' => $fields,
				'conditions' => $conditions,
				'order' => $order));
		$i = 1;
		foreach ($all as $key => $one) {
			$all[$key][$Model->alias][$this->settings['field']] = $i++;
		}
		return $Model->saveAll($all);
	}

	/**
	* Reorder the node, by moving it $number spaces up. Defaults to 1
	*
	* If the node is the first node (or less then $number spaces from first)
	* this method will return false.
	*
	* @param AppModel $Model
	* @param mixed $id The ID of the record to move
	* @param mixed $number how many places to move the node or true to move to last position
	* @return boolean true on success, false on failure
	* @access public
	*/
	function moveup(&$Model, $id = null, $number = 1) {
		if (!$id) {
			if ($Model->id) {
				$id = $Model->id;
			} elseif (!empty($Model->data) && isset($Model->data[$Model->alias][$Model->primaryKey])) {
				$id = $Model->data[$Model->alias][$Model->primaryKey];
			} else {
				return false;
			}
		}
		$old_weight = $this->_read($Model, $id);
		if (empty($Model->data)) {
			return false;
		}
		if (is_numeric($number)) {
			if ($number == 1) { // move 1 space
				$previous = $this->_previous($Model);
				if (!$previous) {
					return false;
				}
				$Model->data[$Model->alias][$this->settings['field']] = $previous[$Model->alias][$this->settings['field']];

				$previous[$Model->alias][$this->settings['field']] = $old_weight;

				$data[0] = $Model->data;
				$data[1] = $previous;

				return $Model->saveAll($data, array('validate' => false));
			} elseif ($number < 1) { // cant move 0 or negative spaces
				return false;
			} else { // move Model up N spaces UP
				if ($this->settings['foreign_key']) {
					$conditions = array($this->settings['foreign_key'] => $Model->data[$Model->alias][$this->settings['foreign_key']]
						);
				} else {
					$conditions = array();
				}
				// find the one occupying new space and its weight
				$new_weight = $Model->data[$Model->alias][$this->settings['field']] - $number;
				// check if new weight is possible. else move last
				if (!$this->_findByWeight($Model, $new_weight)) {
					return false;
				}
				$conditions[$this->settings['field'] . ' >='] = $new_weight;
				$conditions[$this->settings['field'] . ' <'] = $old_weight;
				// increase weight of all where weight > new weight and id != Model.id
				$Model->updateAll(array($this->settings['field'] => $this->settings['field'] . ' + 1'), $conditions);
				// set Model weight to new weight and save it
				$Model->data[$Model->alias][$this->settings['field']] = $new_weight;
				return $Model->save(null, false);
			}
		} elseif (is_bool($number) && $number && $Model->data[$Model->alias][$this->settings['field']] != 1) { // move Model FIRST;
			if ($this->settings['foreign_key']) {
				$conditions = array($this->settings['field'] . ' <' => $old_weight,
					$this->settings['foreign_key'] => $Model->data[$Model->alias][$this->settings['foreign_key']]
					);
			} else {
				$conditions = array($this->settings['field'] . ' <' => $old_weight);
			}
			$Model->id = $Model->data[$Model->alias][$Model->primaryKey];
			$Model->saveField($this->settings['field'], 0);

			$Model->updateAll(
				array(// update
					$this->settings['field'] => $this->settings['field'] . ' + 1'
					),
				$conditions
				);

			return true;
		} else { // $number is neither a number nor a bool
			return false;
		}
	}

	/**
	* Reorder the node, by moving it $number spaces down. Defaults to 1
	*
	* If the node is the last node (or less then $number spaces from last)
	* this method will return false.
	*
	* @param AppModel $Model
	* @param mixed $id The ID of the record to move
	* @param mixed $number how many places to move the node or true to move to last position
	* @return boolean true on success, false on failure
	* @access public
	*/
	function movedown(&$Model, $id = null, $number = 1) {
		if (!$id) {
			if ($Model->id) {
				$id = $Model->id;
			} elseif (!empty($Model->data) && isset($Model->data[$Model->alias][$Model->primaryKey])) {
				$id = $Model->data[$Model->alias][$Model->primaryKey];
			} else {
				return false;
			}
		}
		$old_weight = $this->_read($Model, $id);
		if (empty($Model->data)) {
			return false;
		}
		if (is_numeric($number)) {
			if ($number == 1) { // move node 1 space down
				$next = $this->_next($Model);
				if (!$next) { // it is the last node
					return false;
				}
				// switch the node's weight around
				$Model->data[$Model->alias][$this->settings['field']] = $next[$Model->alias][$this->settings['field']];

				$next[$Model->alias][$this->settings['field']] = $old_weight;
				// create an array of the two nodes and save them
				$data[0] = $Model->data;
				$data[1] = $next;
				return $Model->saveAll($data, array('validate' => false));
			} elseif ($number < 1) { // cant move 0 or negative number of spaces
				return false;
			} else { // move Model up N spaces DWN
				if ($this->settings['foreign_key']) {
					$conditions = array($this->settings['foreign_key'] => $Model->data[$Model->alias][$this->settings['foreign_key']]
						);
				} else {
					$conditions = array();
				}
				// find the one occupying new space and its weight
				$new_weight = $Model->data[$Model->alias][$this->settings['field']] + $number;
				// check if new weight is possible. else move last
				if (!$this->_findByWeight($Model, $new_weight)) {
					return false;
				}
				// increase weight of all where weight > new weight and id != Model.id
				$conditions[$this->settings['field'] . ' <='] = $new_weight;
				$conditions[$this->settings['field'] . ' >'] = $old_weight;

				$Model->updateAll(array($this->settings['field'] => $this->settings['field'] . ' - 1'), $conditions);
				// set Model weight to new weight and save it
				$Model->data[$Model->alias][$this->settings['field']] = $new_weight;
				return $Model->save(null, false);
			}
		} elseif (is_bool($number) && $number) { // move Model LAST;
			if ($this->settings['foreign_key']) {
				$conditions = array($this->settings['field'] . ' >' => $old_weight,
					$this->settings['foreign_key'] => $Model->data[$Model->alias][$this->settings['foreign_key']]
					);
			} else {
				$conditions = array($this->settings['field'] . ' >' => $old_weight);
			}
			// get highest weighted row
			$highest = $this->_highest($Model);
			// check of Model is allready highest
			if ($highest[$Model->alias][$Model->primaryKey] == $Model->data[$Model->alias][$Model->primaryKey]) {
				return false;
			}
			// Save models as highest +1
			$Model->saveField($this->settings['field'], $highest[$Model->alias][$this->settings['field']] + 1);
			// updated all by taking off 1
			$Model->updateAll(
				array(// action
					$this->settings['field'] => $this->settings['field'] . ' - 1'
					),
				$conditions
				);

			return true;
		} else { // $number is neither a number nor a bool
			return false;
		}
	}

	/**
	* Returns true if the specified item is the first item
	*
	* @param Model $Model
	* @param Int $id
	* @return Boolean , true if it is the first item, false if not
	*/
	function isfirst(&$Model, $id = null) {
		if (!$id) {
			if ($Model->id) {
				$id = $Model->id;
			} elseif (!empty($Model->data) && isset($Model->data[$Model->alias][$Model->primaryKey])) {
				$id = $Model->id = $Model->data[$Model->alias][$Model->primaryKey];
			} else {
				return false;
			}
		} else {
			$Model->id = $id;
		}
		$Model->read();

		$first = $this->_read($Model, $id);
		if ($Model->data[$Model->alias][$this->settings['field']] == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Returns true if the specified item is the last item
	*
	* @param Model $Model
	* @param Int $id
	* @return Boolean , true if it is the last item, false if not
	*/
	function islast(&$Model, $id = null) {
		if (!$id) {
			if ($Model->id) {
				$id = $Model->id;
			} elseif (!empty($Model->data) && isset($Model->data[$Model->alias][$Model->primaryKey])) {
				$id = $Model->id = $Model->data[$Model->alias][$Model->primaryKey];
			} else {
				return false;
			}
		} else {
			$Model->id = $id;
		}
		$Model->read();
		$last = $this->_highest($Model);
		if ($last[$Model->alias][$Model->primaryKey] == $id) {
			return true;
		} else {
			return false;
		}
	}

	function _findbyweight(&$Model, $weight) {
		$conditions = array($this->settings['field'] => $weight);
		$fields = array($Model->primaryKey, $this->settings['field']);
		if ($this->settings['foreign_key']) {
			$conditions[$this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
			$fields[] = $this->settings['foreign_key'];
		}
		return $Model->find('first', array(
				'conditions' => $conditions,
				'order' => $this->settings['field'] . ' DESC',
				'fields' => $fields
				));
	}

	function _highest(&$Model) {
		$conditions = array(1 => 1);
		$fields = array($Model->primaryKey, $this->settings['field']);
		if ($this->settings['foreign_key']) {
			$conditions[$Model->alias . '.' . $this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
			$fields[] = $this->settings['foreign_key'];
		}

		return $Model->find('first', array(
				'conditions' => $conditions,
				'order' => $this->settings['field'] . ' DESC',
				'fields' => $fields
				));
	}

	function _previous(&$Model) {
		$conditions = array($this->settings['field'] => $Model->data[$Model->alias][$this->settings['field']] - 1);
		$fields = array($Model->primaryKey, $this->settings['field']);
		if ($this->settings['foreign_key']) {
			$conditions[$this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
			$fields[] = $this->settings['foreign_key'];
		}
		return $Model->find('first', array(
				'conditions' => $conditions,
				'order' => $this->settings['field'] . ' DESC',
				'fields' => $fields
				));
	}

	function _next(&$Model) {
		$conditions = array($this->settings['field'] => $Model->data[$Model->alias][$this->settings['field']] + 1);
		$fields = array($Model->primaryKey, $this->settings['field']);
		if ($this->settings['foreign_key']) {
			$conditions[$this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
			$fields[] = $this->settings['foreign_key'];
		}
		return $Model->find('first', array(
				'conditions' => $conditions,
				'order' => $this->settings['field'] . ' DESC',
				'fields' => $fields
				));
	}

	function _all(&$Model) {
		$conditions = array(1 => 1);
		$fields = array($Model->primaryKey, $this->settings['field']);
		if ($this->settings['foreign_key']) {
			$conditions[$this->settings['foreign_key']] = $Model->data[$Model->alias][$this->settings['foreign_key']];
			$fields[] = $this->settings['foreign_key'];
		}
		return $Model->find('all', array(
				'conditions' => $conditions,
				'order' => $this->settings['field'] . ' DESC',
				'fields' => $fields
				));
	}

	function _read(&$Model, $id) {
		$Model->id = $id;
		$Model->recursive = - 1;
		$fields = array($Model->primaryKey, $this->settings['field']);
		if ($this->settings['foreign_key']) {
			$fields[] = $this->settings['foreign_key'];
		}
		$Model->data = $Model->find('first', array(
				'fields' => $fields,
				'conditions' => array($Model->primaryKey => $id)
				));
		return $Model->data[$Model->alias][$this->settings['field']];
	}
}

?>