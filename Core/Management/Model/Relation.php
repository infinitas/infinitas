<?php
	/**
	 *
	 *
	 */
	class Relation extends ManagementAppModel {
		public $tablePrefix = 'relation_';

		public $belongsTo = array(
			//'Management.RelationType'
		);

		public function getRelations($bind = true) {
			$return =  $this->find(
				'all',
				array(
					'fields' => array(
					),
					'contain' => array(
						'RelationType'
					)
				)
			);

			pr( $return );
			exit;
		}
	}