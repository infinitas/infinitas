<?php
App::uses('ModelBehavior', 'Model');
/**
	 * CakePHP Tags Plugin
	 *
	 * Copyright 2009 - 2010, Cake Development Corporation
	 *						1785 E. Sahara Avenue, Suite 490-423
	 *						Las Vegas, Nevada 89104
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
	 * @link	  http://github.com/CakeDC/Tags
	 * @package   plugins.tags
	 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */

	/**
	 * Short description for class.
	 *
	 * @package		plugins.tags
	 * @subpackage	plugins.tags.models.behaviors
	 */

	class TaggableBehavior extends ModelBehavior {

	/**
	 * Settings array
	 *
	 * @var array
	 * @access public
	 */
		public $settings = array();

	/**
	 * Default settings
	 *
	 * separator				- separator used to enter a lot of tags, comma by default
	 * tagAlias					- model alias for Tag model
	 * tagClass					- class name of the table storing the tags
	 * taggedClass				- class name of the HABTM association table between tags and models
	 * field					- the fieldname that contains the raw tags as string
	 * foreignKey				- foreignKey used in the HABTM association
	 * associationForeignKey	- associationForeignKey used in the HABTM association
	 * automaticTagging			- if set to true you don't need to use saveTags() manually
	 * language					- only tags in a certain language, string or array
	 *
	 * @var array
	 * @access protected
	 */
		protected $_defaults = array(
			'separator' => ',',
			'field' => 'tags',
			'tagAlias' => 'GlobalTag',
			'tagClass' => 'Contents.GlobalTag',
			'taggedClass' => 'Contents.GlobalTagged',
			'foreignKey' => 'foreign_key',
			'associationForeignKey' => 'tag_id',
			'cacheWeight' => true,
			'automaticTagging' => true,
			'unsetInAfterFind' => false,
			'resetBinding' => false
		);

	/**
	 * Setup
	 *
	 * @param AppModel $Model
	 * @param array $settings
	 * @access public
	 */
		public function setup(Model $Model, $settings = array()) {
			if (!isset($this->settings[$Model->alias])) {
				$this->settings[$Model->alias] = $this->_defaults;
			}

			$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
			$this->settings[$Model->alias]['withModel'] = $this->settings[$Model->alias]['taggedClass'];

			$Model->bindModel(
				array(
					'hasAndBelongsToMany' => array(
						'GlobalTag' => array(
							'className' => $this->settings[$Model->alias]['tagClass'],
							'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
							'associationForeignKey' => $this->settings[$Model->alias]['associationForeignKey'],
							'unique' => true,
							'conditions' => array(
								'GlobalTagged.model' => $Model->modelName()
							),
							'fields' => '',
							'dependent' => true,
							'with' => $this->settings[$Model->alias]['withModel']
						)
					)
				),
				$this->settings[$Model->alias]['resetBinding']
			);
		}

	/**
	 * Saves a string of tags
	 *
	 * @param AppModel $Model
	 * @param string $string
	 * @param string $foreignKey
	 * @param boolean true will remove tags that are not in the $string, false wont
	 * do this and just add new tags without removing existing tags associated to
	 * the current set foreign key
	 * @return array
	 * @access public
	 */
		public function saveTags(Model $Model, $string = null, $foreignKey = null, $update = true) {
			if (is_string($string) && !empty($string) && (!empty($foreignKey) || $foreignKey === false)) {
				$tagClass = $this->settings[$Model->alias]['tagAlias'];
				$tagModel = $Model->GlobalTag;
				$array = explode($this->settings[$Model->alias]['separator'], $string);

				$keys = array();
				foreach ($array as $tag) {
					$tag = trim($tag);
					if (!empty($tag)) {
						$key = $this->multibyteKey($Model, $tag);
						if (!in_array($key, $keys)) {
							$tags[] = $tag;
							$keys[] = $key;
						}
					}
				}

				if (!empty($tags)) {
					$existingTags = $tagModel->find('all', array(
						'contain' => array(),
						'conditions' => array(
							'GlobalTag.keyname' => $keys),
						'fields' => array(
							'GlobalTag.keyname',
							'GlobalTag.name',
							'GlobalTag.id')));

					if (!empty($existingTags)) {
						$existingTagKeyNames = Set::extract($existingTags, '{n}.GlobalTag.keyname');
						$existingTagIds = Set::extract($existingTags, '{n}.GlobalTag.id');
						$newTags = array();
						foreach($tags as $possibleNewTag) {
							$key = $this->multibyteKey($Model, $possibleNewTag);
							if (!in_array($key, $existingTagKeyNames)) {
								$newTags[] = $possibleNewTag;
							}
						}
					} else {
						$existingTagIds = array();
						$newTags = $tags;
					}

					foreach ($newTags as $newTag) {
						$identifier = null;
						if (strpos($newTag, ':') !== false) {
							$t = explode(':', $newTag);
							$identifier = trim($t[0]);
							$newTag = trim($t[1]);
						}
						$data[$tagClass]['name'] = $newTag;
						$data[$tagClass]['identifier'] = $identifier;
						$data[$tagClass]['keyname'] = $this->multibyteKey($Model, $newTag);
						$tagModel->create();
						$tagModel->save($data);
						$newTagIds[] = $tagModel->id;
					}

					if ($foreignKey !== false) {
						if (!empty($newTagIds)) {
							$existingTagIds = array_merge($existingTagIds, $newTagIds);
						}

						$tagged = $tagModel->GlobalTagged->find('all', array(
							'contain' => array(),
							'conditions' => array(
								'GlobalTagged.model' => $Model->modelName(),
								'GlobalTagged.foreign_key' => $foreignKey,
								'GlobalTagged.language' => Configure::read('Config.language'),
								'GlobalTagged.tag_id' => $existingTagIds),
							'fields' => 'GlobalTagged.tag_id'));

						$deleteAll = array(
							'GlobalTagged.foreign_key' => $foreignKey,
							'GlobalTagged.model' => $Model->modelName());

						if (!empty($tagged)) {
							$alreadyTagged = Set::extract($tagged, '{n}.GlobalTagged.tag_id');
							$existingTagIds = array_diff($existingTagIds, $alreadyTagged);

							$deleteAll['NOT'] = array('GlobalTagged.tag_id' => $alreadyTagged);
						}

						if ($update == true) {
							$tagModel->GlobalTagged->deleteAll($deleteAll, false);
						}

						foreach ($existingTagIds as $tagId) {
							$data['GlobalTagged']['tag_id'] = $tagId;
							$data['GlobalTagged']['model'] = $Model->modelName();
							$data['GlobalTagged']['foreign_key'] = $foreignKey;
							$data['GlobalTagged']['language'] = Configure::read('Config.language');
							$tagModel->GlobalTagged->create($data);
							$tagModel->GlobalTagged->save();
						}
					}
				}
				return true;
			}
			return false;
		}

	/**
	 * Creates a multibyte safe unique key
	 *
	 * @param object Model instance
	 * @param string Tag name string
	 * @return string Multibyte safe key string
	 * @access public
	 */
		public function multibyteKey(Model $Model, $string = null) {
			$str = mb_strtolower($string);
			$str = preg_replace('/\xE3\x80\x80/', ' ', $str);
			$str = str_replace(array('_', '-'), '', $str);
			$str = preg_replace( '#[:\#\*"()~$^{}`@+=;,<>!&%\.\]\/\'\\\\|\[]#', "\x20", $str );
			$str = str_replace('?', '', $str);
			$str = trim($str);
			$str = preg_replace('#\x20+#', '', $str);
			return $str;
		}

	/**
	 * Generates comma-delimited string of tag names from tag array(), needed for
	 * initialization of data for text input
	 *
	 * Example usage (only 'GlobalTag.name' field is needed inside of method):
	 * <code>
	 * $this->Blog->hasAndBelongsToMany['GlobalTag']['fields'] = array('name', 'keyname');
	 * $blog = $this->Blog->read(null, 123);
	 * $blog['Blog']['tags'] = $this->Blog->GlobalTag->tagArrayToString($blog['GlobalTag']);
	 * </code>
	 *
	 * @param array $string
	 * @return string
	 * @access public
	 */
		public function tagArrayToString(Model $Model, $data = null) {
			if ($data) {
				return join($this->settings[$Model->alias]['separator'].' ', Set::extract($data, '{n}.name'));
			}
			return '';
		}

	/**
	 * afterSave callback
	 *
	 * @param AppModel $Model
	 */
		public function afterSave(Model $Model) {
			if ($this->settings[$Model->alias]['automaticTagging'] == true && !empty($Model->data[$Model->alias][$this->settings[$Model->alias]['field']])) {
				$this->saveTags($Model, $Model->data[$Model->alias][$this->settings[$Model->alias]['field']], $Model->id);
			}
		}

	/**
	 * beforeSave callback
	 *
	 * @param AppModel $Model
	 * @param array $results
	 * @param mixed
	 * @return array
	 */
		public function afterFind(Model $Model, $results, $primary) {
			extract($this->settings[$Model->alias]);
			foreach ($results as $key => $row) {
				if (isset($row[$tagAlias]) && !empty($row[$tagAlias])) {
					$row[$Model->alias][$field] = '';
					$row[$Model->alias][$field] = $this->tagArrayToString($Model, $row[$tagAlias]);
					if ($unsetInAfterFind == true) {
						unset($row[$tagAlias]);
					}
				}
				$results[$key] = $row;
			}
			return $results;
		}
	}