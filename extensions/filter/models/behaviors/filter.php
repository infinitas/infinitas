<?php
	class FilterBehavior extends ModelBehavior {
		/**
		 * Adapted from work by Brenton (http://bakery.cakephp.org/articles/view/habtm-searching)
		 * New function to help with searching where conditions involve HABTM.
		 * Nothing too fancy for now, just deals with first level (ex. no `with`), also, not sure how it'll
		 * react for multiple fields.
		 * So pretty much just best for `id` of a foreign key.
		 * For HABTM, association condition should not be on the join table, but association. So if:
		 *    User HABTM Interests, and searching for Users, should be Interest.id.
		 * TODO: End result uses the 'IN' operator for the query, which is equivalent to 'OR', and will
		 * eventually want 'AND' instead.
		 * TODO: Test in conditions where no 'with'
		 *
		 * @return array Modified queryData array
		 */
		function beforeFind(&$model, $queryData) {
			$ret_queryData = $queryData;

			// See if we've got conditions
			if (sizeof($queryData['conditions']) > 0) {

				$associated = $model->getAssociated();

				foreach ($queryData['conditions'] AS $key => $value) {
					if(strpos($value, 'LIKE')){
						$tmp = explode('LIKE', $value);
						$field = $tmp['0'];
						$search_value = 'LIKE ' . $tmp['1'];
					} else {
						$field = $key;
						$search_value = $value;
					}
					// Period indicates that not controller's own model
					if (strpos($field, '.')) {
						list($associatedModel, $column) = explode('.', $field);
						// See if it's an association
						if (array_key_exists($associatedModel, $associated)) {

							// Do stuff based on association type, so far only HABTM
							if ($associated[$associatedModel] == 'hasAndBelongsToMany') {
								$assoc = $model->hasAndBelongsToMany[$associatedModel];
								$condition = $model->{$associatedModel}->find('all',
																	array(
																		'fields' => 'DISTINCT id',
																		'conditions' => $field . ' ' . $search_value,
																		'recursive' => -1,
																		'callbacks' => false // because otherwise this `beforeFind` would be called again
																	));
								// So far can't find a way to nicely return a distinct/unique array using the 'list'
								// condition in `find()`, so we use 'all', and use `Set::combine()` (which is pretty
								// much what 'list' does anyway).
								// Another option would've been to still use 'list', but add a 'GROUP BY'
								// (ex: 'group' => $assoc['foreignKey']) onto the query; however, this is slower
								// for the database (arguably, what we're doing here could make up for that, so it's
								// really a preference thing). Maybe do some testing if it's a big issue.
								$i = 0;
								foreach($condition AS $k => $v){
									foreach($v AS $w => $x){
										foreach($x AS $y => $z){
											$conditions[$i++] = $w . '_' . $y . '=' . $z;
										}
									}
								}
								$result = $model->{$associatedModel}->{$assoc['with']}->find('all',
																array(
																	'fields' => 'DISTINCT '. $assoc['foreignKey'],
																	'conditions' => array('OR' => $conditions),
																	'recursive' => -1,
																	'callbacks' => false // because otherwise this `beforeFind` would be called again
																));
								$key_value = '{n}.'. $model->{$associatedModel}->{$assoc['with']}->name .'.'. $assoc['foreignKey'];

								$result = Set::combine($result, $key_value, $key_value);

								// TODO: somehow save this because some times (ex: pagination) we do a `SELECT COUNT(*)`, followed
								// by the actually query itself, so would be nice to avoid an extra query.
								$ids = array_keys($result);
								// set it in our return array
								$ret_queryData['conditions'][$model->name .'.id'] = $ids;
								// and unset the old one, since different id field and such
								unset($ret_queryData['conditions'][$key]);
							} else if ($associated[$associatedModel] == 'hasMany') {
								$assoc = $model->hasMany[$associatedModel];
								$condition = $model->{$associatedModel}->find('all',
																	array(
																		'fields' => 'DISTINCT id',
																		'conditions' => $field . ' ' . $search_value,
																		'recursive' => -1,
																		'callbacks' => false // because otherwise this `beforeFind` would be called again
																	));
								// So far can't find a way to nicely return a distinct/unique array using the 'list'
								// condition in `find()`, so we use 'all', and use `Set::combine()` (which is pretty
								// much what 'list' does anyway).
								// Another option would've been to still use 'list', but add a 'GROUP BY'
								// (ex: 'group' => $assoc['foreignKey']) onto the query; however, this is slower
								// for the database (arguably, what we're doing here could make up for that, so it's
								// really a preference thing). Maybe do some testing if it's a big issue.
								$i = 0;
								foreach($condition AS $k => $v){
									foreach($v AS $w => $x){
										foreach($x AS $y => $z){
											$conditions[$i++] = $y . '=' . $z;
										}
									}
								}
								$result = $model->{$associatedModel}->find('all',
																	array(
																		'fields' => 'DISTINCT ' . $assoc['foreignKey'],
																		'conditions' => array('OR' => $conditions),
																		'recursive' => -1,
																		'callbacks' => false // because otherwise this `beforeFind` would be called again
																	));
								$key_value = '{n}.'. $model->{$associatedModel}->name .'.'. $assoc['foreignKey'];

								$result = Set::combine($result, $key_value, $key_value);

								// TODO: somehow save this because some times (ex: pagination) we do a `SELECT COUNT(*)`, followed
								// by the actually query itself, so would be nice to avoid an extra query.
								$ids = array_keys($result);
								// set it in our return array
								$ret_queryData['conditions'][$model->name .'.id'] = $ids;
								// and unset the old one, since different id field and such
								unset($ret_queryData['conditions'][$key]);
							}
						}
					}
				}
			}

			return $ret_queryData;
		}
	}