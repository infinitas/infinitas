<?php
	/**
	 *
	 *
	 */
	class Relation extends ManagementAppModel{
		var $name = 'Relation';
		var $tablePrefix = 'relation_';

		var $belongsTo = array(
			//'Management.RelationType'
		);

		function getRelations($bind = true){
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