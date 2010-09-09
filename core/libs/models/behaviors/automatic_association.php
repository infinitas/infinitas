<?php
	/**
	 *
	 *
	 */
	class AutomaticAssociationBehavior extends ModelBehavior{
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

			$this->AssocType = ClassRegistry::init('Management.Relation');

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
			$relations = $this->AssocType->getRelations();
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
	}