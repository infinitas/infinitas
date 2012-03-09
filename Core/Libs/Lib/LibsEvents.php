<?php
	class LibsEvents extends AppEvents {
		public function onSetupExtensions(){
			return array(
				'json'
			);
		}

		public function onAttachBehaviors($event) {
			if($event->Handler->shouldAutoAttachBehavior()) {

				// attach the expandable (eva) behavior if there is a table for it
				$attributesTable = Inflector::singularize($event->Handler->tablePrefix.$event->Handler->table) . '_attributes';
				if(in_array($attributesTable, $event->Handler->getTables($event->Handler->useDbConfig))){
					$event->Handler->bindModel(
						array(
							'hasMany' => array(
								$event->Handler->name.'Attribute' => array(
									'className' => Inflector::camelize($attributesTable),
									'foreignKey' => Inflector::underscore($event->Handler->name).'_id',
									'dependent' => true
								)
							)
						),
						false
					);

					$event->Handler->Behaviors->attach('Libs.Expandable');
				}

				if ($event->Handler->shouldAutoAttachBehavior('Libs.Sluggable', array('slug'))) {
					$event->Handler->Behaviors->attach(
						'Libs.Sluggable',
						array(
							'label' => array($event->Handler->displayField)
						)
					);
				}

				if ($event->Handler->shouldAutoAttachBehavior('Libs.Sequence', array('ordering'))) {
					$event->Handler->Behaviors->attach('Libs.Sequence');
				}

				if ($event->Handler->shouldAutoAttachBehavior('Libs.Rateable', array('rating'))) {
					$event->Handler->Behaviors->attach('Libs.Rateable');
				}

				if($event->Handler->shouldAutoAttachBehavior('Tree', array('lft', 'rght')) && $event->Handler->shouldAutoAttachBehavior('InfiniTree', array('lft', 'rght'))) {
					$event->Handler->Behaviors->attach('Tree');
				}

				if($event->Handler->shouldAutoAttachBehavior('Libs.Validation')) {
					$event->Handler->Behaviors->attach('Libs.Validation');
				}
			}
		}

		public function onRequireComponentsToLoad($event){
			return array(
				'Libs.Infinitas',
				'Paginator',
				'Session',
				'RequestHandler',
				'Auth',
				'Acl',
				'Security' => array(
					'csrfCheck' => false
				),
				'Libs.MassAction',
				'Libs.InfinitasActions'
			);
		}

		public function onRequireHelpersToLoad($event) {
			return array(
				'Html', 'Form', 'Js', 'Session', 'Time', 'Text', // core general things from cake
				'Libs.Infinitas',
				'Libs.Image', 'Libs.Design'
			);
		}

		public function onRequireCssToLoad(){
			return array(
				'Assets.jquery_ui'
			);
		}
	}