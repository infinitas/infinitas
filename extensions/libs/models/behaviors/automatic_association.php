<?php
	/**
	 *
	 *
	 */
	class AutomaticAssociation extends ModelBehavior{
		var $defaults = array(
			'active' => true
		);

		var $AssocModel = null;

		function setup(&$model, $settings = array()) {

			$default = $this->defaults;

			if (!isset($this->__settings[$model->alias])) {
				$this->__settings[$model->alias] = $default;
			}

			$this->__settings[$model->alias] = array_merge($this->__settings[$model->alias], ife(is_array($settings), $settings, array()));

			$this->AssocModel = ClassRegistry::init('Association.Associations');

			if ($this->__settings[$model->alias]['active']) {
				$this->__autoBind();
				$this->__autoUnbindBind();
			}
		}

		/**
		* Auto bind models.
		*
		* Checks routes and model loaded to see if it needs to be done
		*/
		function __autoBind(){
			if (false /* 2 way bind */) {
				$this->__reverseBind();
			}
		}


		/**
		 * Auto unbind models.
		 *
		 * gets a list of models that can be unbinded, check the routes and
		 * unbind the correct ones
		 */
		function __autoUnbindBind(){

		}

		/**
		* public method to easily bind models.
		*/
		function bindModel($className = null){

		}

		/**
		 * public method to easily un-bind models.
		 */
		function unbindModel($className = null){

		}


		/**
		 * Get relations to bind.
		 *
		 * get all the items that need to be bound and cache them.
		 */
		function __getRelationsForBindinig(){

		}

		/**
		 * Get relations to un-bind.
		 *
		 * get all the items that need to be un=binded and cache them.
		 */
		function __getRelationsForUnBinding(){

		}
	}
?>