<?php
    class FilterHelper extends AppHelper{
    	public $helpers = array(
    	    'Form', 'Html'
    	);

        public $count = 0;

    	public function form($model, $filter = array()){
    		if (empty($filter) || !isset($filter['fields'])){
    			$this->errors[] = 'There is no filters';
    			return false;
    		}

    		$output = '<div class="filter-form"><h1>'.__('Search', true).'</h1>';
       		foreach( $filter['fields'] as $field => $options){
       			if (is_array($options)){
					switch($field){
						case 'active':
							$emptyText = __('status', true);
							break;
						
						default:
							$emptyText = __($field, true);
							break;
					}

					$emptyText = sprintf(__('Select the %s', true), Inflector::humanize(str_replace('_id', '', $emptyText)));
					$output .= $this->Form->input(
						$field,
	    				array(
		    				'type' => 'select',
		    				'div' => false,
		    				'options' => $options,
		    				'empty' => $emptyText,
							'label' => false
		    			)
	    			);
				}
				else{
					$output .= $this->Form->input(
						$options,
						array(
							'type' => 'text',
							'div' => false,
							'label' => false,
							'value' => $options
						)
					);
				}
       		}

			$output .= $this->Form->button(
				$this->Html->image(
					$this->Image->getRelativePath(array('actions'), 'filter'),
					array(
						'width' => '16px'
					)
				),
				array(
					'value' => 'filter',
					'name' => 'action',
					'title' => $this->niceTitleText('Filter results'),
					'div' => false
				)
			);
    		$output .= '</div>';
    		return $output;
    	}

        /**
         * Comment Template.
         *
         * @todo -c Implement .this needs to be sorted out.
         *
         * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
         *
         * Licensed under The MIT License
         * Redistributions of files must retain the above copyright notice.
         *
         * @filesource
         * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
         * @link          http://infinitas-cms.org
         * @package       sort
         * @subpackage    sort.comments
         * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
         * @since         0.5a
         */
        public function clear($filter, $div = false){
            if (!isset($filter['url'][0]) || empty($filter['url'][0]) || $filter['url'][0] == '/'){
                $filter['url'][0] = '/';
            }

        	$out = '';
            if ($div){
                $out .= '<div class="filter">';
            }

            $out .= '<div class="wrap">';
                $parts = explode( '/', $filter['url'][0] );
                $done = array();

                foreach($parts as $_f){
                    if (empty($_f) || in_array($_f, $done)){
                        continue;
                    }

                    $done[] = $_f;

                    $text = explode(':', $_f);
                    $text = explode('.', $text[0]);
                    $text = count($text ) > 1 ? $text[1] : $text[0];

                    $out .= '<div class="left">'.
                                '<div class="remove">'.
                                        $this->Html->link(
                                        Inflector::humanize($text),
                                        str_replace($_f, '', '/' . $this->params['url']['url'])
                                    ).
                                '</div>'.
                            '</div>';
                }
            $out .= '</div>';
            if ($div){
                $out .= '</div>';
            }

            return $out;
        }

		/**
		 * bulid a list of leters and numbers with filtered links to rows that
		 * start with that letter or number
		 *
		 * @return string ul->li list of things found
		 */
		public function alphabetFilter(){
			if(empty($this->params['models'])){
				return false;
			}
			$model = current($this->params['models']);
			$letters = ClassRegistry::init($model)->getLetterList();
			$return = array();
			foreach($letters as $key => $value){
				$url = $value == true ? $this->__filterLink($key) : $key;
				if(is_array($url)){
					$url = $this->Html->link(
						$key,
						Router::url($url),
						array(
							'title' => sprintf(__('Rows starting with "%s"', true), $key)
						)
					);
				}
				$return[] = sprintf('<li>%s</li>', $url);
			}

			$return[] = sprintf('<li>%s</li>', $this->Html->link('All', $this->cleanCurrentUrl()));

			return '<div class="alphabet-filter"><ul>' . implode('', $return) . '</ul></div>';
		}

		/**
		 * create a link for some text against the display field
		 *
		 * @param string $text the text to show/filter with
		 * @return array the url cake style
		 */
		private function __filterLink($text = null){
			if(!$text){
				return false;
			}
			$model = current($this->params['models']);

			$filter = array(
				ClassRegistry::init($model)->alias . '.' . ClassRegistry::init($model)->displayField => $text
			);

			$params = array_merge(parent::cleanCurrentUrl(), $this->params['named'], $this->params['pass'], $filter);
			
			return $params;
		}
    }