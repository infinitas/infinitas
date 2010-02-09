<?php
	/**
	 *
	 *
	 */
	class InfinitasHelper extends AppHelper{
		var $helpers = array(
			'Html',
			'Libs.Image'
		);

		/**
		* JSON errors.
		*
		* Set up some errors for json.
		* @access public
		*/
		var $_json_errors = array(
		    JSON_ERROR_NONE      => 'No error',
		    JSON_ERROR_DEPTH     => 'The maximum stack depth has been exceeded',
		    JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		    JSON_ERROR_SYNTAX    => 'Syntax error',
		);

		var $_menuData = '';
		var $_menuLevel = 0;

		/**
		* Module Loader.
		*
		* This is used to load modules. it generates a wrapper div with the class
		* set as the module name for easy styleing, and will create a header if set
		* in the backend.
		*
		* @params string $position this is the possition that is to be loaded, can be anything from the database
		* @params bool $admin if true its a admin module that should be loaded.
		*/
		function loadModules($position = null, $admin = false){
			if (!$position) {
				$this->errors[] = 'No position selected to load modules';
				return false;
			}

			$modules = ClassRegistry::init('Management.Module')->getModules($position, $admin);

			$out = '<div class="modules '.$position.'">';
				$currentRoute = Router::currentRoute();

				foreach($modules as $module){
					if ($module['Theme']['name'] != '' && $module['Theme']['name'] != $this->theme) {
						//skip modules that are not for the current theme
						continue;
					}

					$moduleOut = '<div class="module '.$module['Module']['module'].'">';
						if ($module['Module']['show_heading']) {
							$moduleOut .= '<h2>'.__($module['Module']['name'],true).'</h2>';
						}

						if (!empty($module['Module']['module'])) {
							$View = ClassRegistry::getObject('View');
							$path = 'modules/';
							if ($admin) {
								$path .= 'admin/';
							}
							$moduleOut .= $View->element($path.$module['Module']['module'], array('config' => $this->_moduleConfig($module['Module'])), true);
						}
						else if (!empty($module['Module']['content'])) {
							$moduleOut .= $module['Module']['content'];
						}
					$moduleOut .= '</div>';

					if (!empty($module['Route']) && is_object($currentRoute)){
						foreach($module['Route'] as $route){
							if ($route['url'] == $currentRoute->template) {
								$out .= $moduleOut;
							}
						}
					}
					else if (empty($module['Route'])) {
						$out .= $moduleOut;
					}
				}
			$out .= '</div>';

			return $out;
		}

		/**
		* Module Config.
		*
		* This method works out params from JSON data in the module. if there is something
		* wrong with the JSON code that is submitted it will return an empty array(), or it
		* will return an array with the config.
		*
		* @access protected
		* @params string $config some JSON data to be decoded.
		*/
		function _moduleConfig($config = ''){
			if (empty($config['config'])) {
				return array();
			}

			$json = json_decode($config['config'], true);

			if (!$json) {
				$this->errors[] = 'module ('.$config['name'].'): '.$this->_json_errors[json_last_error()];
				return array();
			}

			return $json;
		}

		function generateDropdownMenu($data = array(), $type = 'horizontal'){
			if (empty($data)) {
				$this->errors[] = 'There are no items to make the menu with';
				return false;
			}
			$this->_menuData = '<ul class="pureCssMenu pureCssMenum0">';
				foreach( $data as $k => $v ){
					$this->_menuLevel = 0;
					$this->__buildDropdownMenu($v, 'MenuItem');
				}
			$this->_menuData .= '</ul>';

			return $this->_menuData;
		}

		function __buildDropdownMenu($array = array(), $model = ''){
			if (empty($array['MenuItem']) || $model = '') {
				$this->errors[] = 'nothing passed to generate';
				return false;
			}

			$suffix = '';
			if ($this->_menuLevel == 0) {
				$suffix = '0';
			}

			$linkName = __($array['MenuItem']['name'], true);
			if (!empty($array['children'])) {
				$linkName = '<span>'.$linkName.'</span>';
			}

			$this->_menuData .= '<li class="pureCssMenui'.$suffix.'">';
				$menuLink = $array['MenuItem']['link'];
				if (empty($array['MenuItem']['link'])) {
					$_items = $array['MenuItem'];

					$menuLink['prefix']     = (!empty($array['MenuItem']['prefix'])     ? $array['MenuItem']['prefix']     : null);
					$menuLink['plugin']     = (!empty($array['MenuItem']['plugin'])     ? $array['MenuItem']['plugin']     : null);
					$menuLink['controller'] = (!empty($array['MenuItem']['controller']) ? $array['MenuItem']['controller'] : null);
					$menuLink['action']     = (!empty($array['MenuItem']['action'])     ? $array['MenuItem']['action']     : 'index');
					$menuLink[]             = (!empty($array['MenuItem']['params'])     ? $array['MenuItem']['params']     : null);

					foreach($menuLink as $key => $value ){
						if (empty($value)) {
							unset($menuLink[$key]);
						}
					}

					if ($array['MenuItem']['force_backend']) {
						$menuLink['admin'] = true;
					}
					else if ($array['MenuItem']['force_frontend']) {
						$menuLink['admin'] = false;
					}
				}

				$this->_menuData .= $this->Html->link(
					$linkName,
					$menuLink,
					array(
						'class' => 'pureCssMenui'.$suffix,
						'escape' => false
					)
				);

				if (!empty($array['children'])) {
					$this->_menuData .= '<ul class="pureCssMenum">';
						foreach( $array['children'] as $k => $v ){
							$this->_menuLevel = 1;
							$this->__buildDropdownMenu($v, $model);
						}
					$this->_menuData .= '</ul>';
				}
				$this->_menuData .= '</a>';

			$this->_menuData .= '</li>';
		}

		var $external = true;

		function status( $status = null )
		{
			$image = false;
			$params = array();

			switch ( strtolower( $status ) )
			{
				case 1:
				case 'yes':
				case 'on':
					if ( $this->external )
					{
						$params['title'] = __( 'Active', true );
					}

					$image = $this->Html->image(
					    $this->Image->getRelativePath( 'status', 'active' ),
					    $params + array(
					        'width' => '16px',
					        'alt' => __( 'On', true )
					    )
					);
					break;

				case 0:
				case 'no':
				case 'off':
					if ( $this->external )
					{
						$params['title'] = __( 'Disabled', true );
					}

					$image = $this->Html->image(
					    $this->Image->getRelativePath( 'status', 'inactive' ),
					    $params + array(
					        'width' => '16px',
					        'alt' => __( 'Off', true )
					    )
					);
					break;
			}

			return $image;
		}

		/**
		 * Toogle button
		 *
		 * Uses Infinitas::status to get the image and then creates a link based on
		 * the $method param
		 */
		function toggle( $status = null, $id = null, $url = array( 'action' => 'toggle' ) )
		{
			$params = array();

			switch( $status )
			{
				case 0:
				case 'off':
				case 'no':
					$params['title'] = __( 'Click to activate', true );
					$params['alt'] = __( 'Disabled', true );
					break;

				case 1:
				case 'yes':
				case 'on':
					$params['title'] = __( 'Click to disable', true );
					$params['alt'] = __( 'Active', true );
					break;
				default:
				;
			} // switch

			$this->external = false;

			$link = $this->Html->link(
			    $this->status( $status ),
			    (array)$url + (array)$id
			    ,
			    $params + array(
			        'escape' => false
			    )
			);

			return $link;
		}

		function locked( $item = array(), $model = null )
		{
			if ( !$model || empty( $item ) || empty( $item[$model] ) )
			{
				$this->errors[] = 'you missing some data there.';
				return false;
			}

			switch ( strtolower( $item[$model]['locked'] ) ){
				case 1:
					$this->Time = new TimeHelper();
					$image = $this->Html->image(
					    $this->Image->getRelativePath( 'status', 'locked' ),
					    array(
					        'alt' => __( 'Locked', true ),
					        'width' => '16px',
					        'title' => sprintf(
					            __( 'This record was locked %s by %s', true ),
					            $this->Time->timeAgoInWords( $item[$model]['locked_since'] ),
					            $item['Locker']['username'] )
					    )
					);
					unset($this->Time);
					break;

				case 0:
					$image = $this->Html->image(
					    $this->Image->getRelativePath( 'status', 'not-locked' ),
					    array(
					        'alt' => __( 'Not Locked', true ),
					        'width' => '16px',
					        'title' => __( 'This record is not locked', true )
					    )
					);
					break;
			}

			return $image;
		}

		function featured( $record = array(), $model = 'Feature' )
		{
			if ( empty( $record[$model] ) )
			{
				$this->messages[] = 'This has no featured items.';

				return $this->Html->image(
				    $this->Image->getRelativePath( 'status', 'not-featured' ),
				    array(
				        'alt'   => __( 'No', true ),
				        'title' => __( 'Not a featured item', true ),
				        'width' => '16px'
				    )
				);
			}

			return $this->Html->image(
			    $this->Image->getRelativePath( 'status', 'featured' ),
			    array(
			        'alt'   => __( 'Yes', true ),
			        'title' => __( 'Featured Item', true ),
			        'width' => '16px'
			    )
			);
		}
	}
?>