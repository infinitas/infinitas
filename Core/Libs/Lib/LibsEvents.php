<?php
/**
 * LibsEvents
 *
 * @package Infinitas.Libs.Lib
 */

/**
 * LibsEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class LibsEvents extends AppEvents {
/**
 * Load general behaviors
 *
 * @param Event $Event
 *
 * @return void
 */
	public function onAttachBehaviors(Event $Event) {
		if($Event->Handler->shouldAutoAttachBehavior()) {

			// attach the expandable (eva) behavior if there is a table for it
			$attributesTable = Inflector::singularize($Event->Handler->tablePrefix.$Event->Handler->table) . '_attributes';
			if(in_array($attributesTable, $Event->Handler->getTables($Event->Handler->useDbConfig))) {
				$Event->Handler->bindModel(
					array(
						'hasMany' => array(
							$Event->Handler->name.'Attribute' => array(
								'className' => Inflector::camelize($attributesTable),
								'foreignKey' => Inflector::underscore($Event->Handler->name).'_id',
								'dependent' => true
							)
						)
					),
					false
				);

				$Event->Handler->Behaviors->attach('Libs.Expandable');
			}

			if ($Event->Handler->shouldAutoAttachBehavior('Libs.Sluggable', array('slug'))) {
				$Event->Handler->Behaviors->attach(
					'Libs.Sluggable',
					array(
						'label' => array($Event->Handler->displayField)
					)
				);
			}

			if ($Event->Handler->shouldAutoAttachBehavior('Libs.Sequence', array('ordering'))) {
				$Event->Handler->Behaviors->attach('Libs.Sequence');
			}

			if ($Event->Handler->shouldAutoAttachBehavior('Libs.Rateable', array('rating'))) {
				$Event->Handler->Behaviors->attach('Libs.Rateable');
			}

			if($Event->Handler->shouldAutoAttachBehavior('Tree', array('lft', 'rght')) && $Event->Handler->shouldAutoAttachBehavior('InfiniTree', array('lft', 'rght'))) {
				$Event->Handler->Behaviors->attach('Tree');
			}

			if($Event->Handler->shouldAutoAttachBehavior('Libs.Validation')) {
				$Event->Handler->Behaviors->attach('Libs.Validation');
			}
		}
	}

/**
 * Configure extensions for the router to parse
 *
 * @return array
 */
	public function onSetupExtensions() {
		return array(
			'json'
		);
	}

/**
 * Load general components
 *
 * @param Event $Event the event being triggered
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
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

/**
 * Load general helpers
 *
 * @param Event $Event the event being triggered
 *
 * @return array
 */
	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Html', 'Form', 'Js', 'Session', 'Time', 'Text', // core general things from cake
			'Libs.Infinitas',
			'Libs.Image', 'Libs.Design', 'Libs.Gravatar'
		);
	}

/**
 * Load general css
 *
 * @return array
 */
	public function onRequireCssToLoad() {
		return array(
			'Assets.jquery_ui'
		);
	}

}