<?php
	/**
	 * @page AppHelper AppHelper
	 *
	 * @section app_helper-overview What is it
	 *
	 * AppHelper is the base helper class that other helpers may extend to inherit
	 * some methods and functionality. If for some reason you do not want to
	 * inherit from this class just extend Helper.
	 *
	 * @section app_helper-usage How to use it
	 *
	 * Usage is simple, extend your SomethingHelper from this class Example below:
	 *
	 * @code
	 *	// in APP/plugins/my_plugin/views/helpers/something.php create
	 *	class SomethingHelper extends AppHelper{
	 *		
	 *	}
	 * @endcode
	 *
	 * After that you will be able to directly access the public methods that
	 * are available from this class as if they were in your helper. There are
	 * two different ways that the methods can be accessed
	 *
	 * @code
	 *	// from within the Something helper
	 *	$this->someMethod();
	 *
	 *	// from a view file
	 *	$this->Something->someMethod();
	 * @endcode
	 *
	 * @section app_helper-see-also Also see
	 * @li InfinitasHelper
	 */


	/**
	 * @brief AppHelper is the base helper class that other helpers can extend
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 * 
	 * Url Caching
	 * Copyright (c) 2009 Matt Curry
	 * @link http://github.com/mcurry/url_cache
	 * @link http://www.pseudocoder.com/archives/how-to-save-half-a-second-on-every-cakephp-requestand-maintain-reverse-routing
	 * @author      Matt Curry <matt@pseudocoder.com>
	 * @since 0.7a
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class AppHelper extends Helper {
		/**
		 * Internal counter of the row number to do zebra striped tables
		 *
		 * @var int
		 * @access public
		 */
		public $rowClassCounter = 0;

		/**
		 * The pagination string
		 *
		 * @var string
		 * @access public
		 */
		public $paginationCounterFormat = 'Page %page% of %pages%.';

		/**
		 * array of errors for debugging
		 *
		 * To keep track of what errors are happening you can add them to the
		 * error stack from your helpers. Then you can use pr() to see what
		 * error have happend up until that point.
		 *
		 * @code
		 *	// add errors from the helper
		 *	$this->errors[] = 'something bad happend ' . __LINE__;
		 *
		 *	// see errors in the helper
		 *	pr($this->errors);
		 *
		 *	// see errors in the view
		 *	pr($this->Something->errors);
		 *
		 * @var array
		 * @access public
		 */
		public $errors = array();

		/**
		 * @deprecated
		 *
		 * @todo need to make a wysiwyg engine like ChartsHelper
		 *
		 * @var string
		 */
		public $wysiwyg = 'fck';

		/**
		 * Additional helpers to load
		 *
		 * @var array
		 * @access public
		 */
		public $helpers = array(
			'Html', 'Form',
			'Libs.Wysiwyg', 'Libs.Image'
		);

		/**
		 * @deprecated
		 *
		 * this should be removed
		 */
		function niceBox($n = null){}

		/**
		 * @deprecated
		 *
		 * this should be removed
		 */
		function niceBoxEnd($n = null){}

		/**
		 * @brief create some bread crumbs
		 *
		 * This is used in the admin backend to generate bread crumbs of where
		 * the user is in the site. Its no very smart so some of the links will
		 * be wrong if you dont have what is expected.
		 *
		 * @param array $here is $this from the view.
		 * @access public
		 *
		 * @return string the markup for the bread crumbs
		 */
		public function breadcrumbs($seperator = ' :: ') {
			$action = '';
			if(strstr($this->params['action'], 'mass') === false){
				$action = str_replace('admin_', '', $this->params['action']);
			}
			
			else{
				$action = $this->params['form']['action'];
			}
			
			$breadcrumbs = array(
				$this->Html->link(
					__($this->params['plugin'], true),
					array(
						'plugin' => $this->params['plugin'],
						'controller' => false,
						'action' => false
					)
				),
				$this->Html->link(
					__(strtolower(prettyName($this->params['controller'])), true),
					array(
						'plugin' => $this->params['plugin'],
						'controller' => Inflector::underscore($this->params['controller']),
						'action' => false
					)
				),
				$action
			);

			$_prefix = isset($this->params['prefix']) ? $this->params['prefix'] : false;

			if ($_prefix !== false) {
				$breadcrumbs = array_merge(
					(array)$this->Html->link(
						__($_prefix, true),
						'/'.$_prefix
					),
					$breadcrumbs
				);
			}

			return implode($seperator, $breadcrumbs);
		}

		/**
		 * @brief get the current url with no params
		 *
		 * This will give you an array or url of the current page with no params.
		 * Good for resetting search fields and filters.
		 *
		 * @param bool $array return array (true) or string (false)
		 * @access public
		 *
		 * @return mixed the clean url
		 */
		public function cleanCurrentUrl($array = true){
			$params = array(
				'prefix' => $this->params['prefix'],
				'plugin' => $this->params['plugin'],
				'controller' => $this->params['controller'],
				'action' => $this->params['action']
			);

			if($array){
				return $params;
			}

			return Ruter::url($params);
		}

		/**
		 * @brief switch the class for table rows
		 *
		 * Used to make the zebra striping in the admin backend. This should be
		 * removed from admin in favour of CSS3 pesudo selectors but the
		 * method can remain for frontend use.
		 *
		 * @todo remove usage from admin backend
		 *
		 * @param string $class1 class 1 highlight
		 * @param string $class2 class 2 highlight
		 * @access public
		 *
		 * @return string the class
		 */
		public function rowClass($class1 = 'bg', $class2 = '') {
			return (($this->rowClassCounter++ % 2) ? $class1 : $class2);
		}

		/**
		 * @brief Admin page heading
		 *
		 * Generates a heading based on the controller and adds a bread crumb
		 *
		 * @access public
		 *
		 * @return string the markup for the page header
		 */
		public function adminPageHead() {
			return '<h1>' . sprintf(__('%s Manager', true), prettyName($this->urlExtras['controller'])) . '<small>' . $this->breadcrumbs() . '</small></h1>';
		}

		/**
		 * @brief Creates table headers for admin.
		 *
		 * If the format is just array( 'head1', 'head2' ... ) it will output a
		 * normal table with TH that have no classes/styles applied.  you can
		 * also pass things like array ( 'head1' => array( 'class' => 'something' ) )
		 * to get out put like <th class="something">head1</th>
		 *
		 * @param array $data an array of items for the head.
		 * @param bool $footer if you want to show the table footer or not.
		 * @access public
		 *
		 * @return string the thead and tfoot html
		 */
		public function adminTableHeader($data, $footer = true) {
			$out = '';
			foreach($data as $field => $params) {
				$atributes = '';

				if (is_int($field) && !is_array($params)) {
					$field = $params;
					$params = '';
				}

				else {
					foreach($params as $type => $param) {
						$atributes = '';
						$atributes .= $type . '="' . $param . '" ';
					}

					$atributes .= $atributes;
				}

				if ($atributes != '') {
					$params = $atributes;
				}

				$out .= '<th ' . $params . '>' . $field . '</th>';
			}
			return '<thead>'.$out.'<thead>'. (($footer) ? '<tfoot>'.$out.'</tfoot>' : '');
		}

		/**
		 * @brief lazy way to create the admin index page headers
		 * 
		 * @param array $filterOptions the filters to show
		 * @param array $massActions the mass actions to show
		 * @access public
		 * 
		 * @return string the markup generated
		 */
		public function adminIndexHead($filterOptions = array(), $massActions = null) {
			if(!class_exists('FilterHelper')){
				App::import('Helper', 'Filter.Filter');
			}

			return sprintf(
				'<div class="adminTopBar">%s%s</div><div class="filters">%s</div>',
				$this->adminPageHead(),
				$massActions,
				FilterHelper::form('Post', $filterOptions) . FilterHelper::clear($filterOptions)
			);
		}

		/**
		 * @brief lazy page for general admin pages with no mass actions
		 * 
		 * @param array $massActions the mass actions as generated by
		 * @access public
		 *
		 * @return string the markup for the page
		 */
		public function adminOtherHead($massActions = null) {
			return sprintf(
				'<div class="adminTopBar">%s%s</div>',
				$this->adminPageHead(),
				$massActions
			);
		}

		/**
		 * @brief lazy method to create the admin head for editing pages
		 * 
		 * @param array $actions the actions to create buttons for
		 * @access public
		 *
		 * @return string the markup for the page
		 */
		public function adminEditHead($actions = array('save', 'cancel')){
	        return $this->adminOtherHead(
				$this->massActionButtons($actions)
	        );
		}

		/**
		 * @brief generate links with little code
		 *
		 * Generate a default edit link for use insde admin with no routing. just
		 * pass the array like $row['Model'] to this method and if you want something
		 * other than action => edit (maybe a different controller) pass that also
		 *
		 * @code
		 *	// for the current model
		 *	$this->Html->adminQuickLink($user['User']);
		 *
		 *	// for related data
		 *	$this->Html->adminQuickLink($user['Group'], array(), 'Group');
		 * 
		 *	// to a different page
		 *	$this->Html->adminQuickLink($user['User'], array('action' => 'view'));
		 * @endcode
		 *
		 * @param array $row the row $row['Model'] data
		 * @param mixed $url normal cake url array/string
		 * @param array $models if you want to link to a related model
		 * @access public
		 *
		 * @return string borked on error, html link when all is good
		 */
		public function adminQuickLink($row = array(), $url = array(), $model = '', $urlOnly = false){
			$id = $text = null;

			if(is_array($url)){
				$url = array_merge(array('action' => 'edit'), $url);
			}

			if(isset($this->params['models'][0]) || !empty($model)){
				$model = !empty($model) ? $model : $this->params['models'][0];

				$id   = $row[ClassRegistry::init($model)->primaryKey];
				$text = $row[ClassRegistry::init($model)->displayField];
			}

			else{
				$id   = isset($row['id']) ? $row['id'] : null;
				$text = isset($row['name']) ? $row['name'] : null;
				$text = !$text && isset($row['title']) ? $row['title'] : null;
			}

			if(!$id){
				return __('Borked', true);
			}

			if(is_array($url)){
				$url = array_merge((array)$url, array(0 => $id));
			}
			else{
				$url .= '/'.$id;
			}

			if($urlOnly){
				return $url;
			}

			$link = '';
			if(!$text){
				$link = $id;
			}
			else{
				$link = $text;
			}
			return $this->Html->link($link, $url);
		}

		/**
		 * @brief generate links for ordering normal tables
		 *
		 * creates links to the mass actions for ordering rows. This is for
		 * models that use the SequenceBehavior.
		 *
		 * @see AppHelper::treeOrdering()
		 *
		 * @param string $id the id of the row
		 * @param int $currentPosition the current order
		 * @param string $modelName the model
		 * @param array $results the row being ordered
		 * @access public
		 * 
		 * @return string markup for the links to order them
		 */
		public function ordering($id = null, $currentPosition = null, $modelName = null, $results = null) {
			if (!$id) {
				$this->errors[] = 'How will i know what to move?';
			}

			if (!$currentPosition) {
				$this->errors[] = 'The new order was not passed';
			}

			if($results != null && $modelName) {
				$maxPosition = max(Set::extract('/' . $modelName . '/ordering', $results));
			}

			$out = '';

			if ($currentPosition > 1) {
				$out .= $this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'arrow-up'),
						array(
							'alt' => __('Up', true),
							'title' => __('Move up', true),
							'width' => '16px',
							'class' => 'arrow-up'
						)
					),
					array(
						'action' => 'reorder',
						'position' => $currentPosition - 1,
						$id
					),
					array(
						'escape' => false,
					)
				);
			}

			if($results == null || $currentPosition < $maxPosition) {
				$out .= $this->Html->link($this->Html->image($this->Image->getRelativePath('actions', 'arrow-down'),
						array(
							'alt' => __('Down', true),
							'title' => __('Move down', true),
							'width' => '16px',
							'class' => 'arrow-down'
						)
					),
					array(
						'action' => 'reorder',
						'position' => $currentPosition + 1,
						$id
					),
					array(
						'escape' => false,
					)
				);
			}

			return $out;
		}

		/**
		 * @brief generate icons and links for ordering mptt tables
		 *
		 * Generates links for ordering mptt rows with the TreeBehavior
		 *
		 * options:
		 * - firstChild: Pass true if this node is the first child
		 * - lastChild: Pass true if this node is the last child
		 * 
		 * @see AppHelper::ordering()
		 *
		 * @param array $data the row being ordered
		 * @param array $options see above
		 * @access public
		 *
		 * @return string the html markup for icons to order the rows
		 */
		public function treeOrdering($data = null, $options = array()){
			$options = array_merge(array('firstChild' => false, 'lastChild' => false), $options);
			
			if (!$data) {
				$this->errors[] = 'There is no data to build reorder links';
				return false;
			}

			$out = '';
			
			if(!$options['firstChild']) {
				$out .= $this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'arrow-up'),
						array(
							'alt' => __('Up', true),
							'title' => __('Move up', true),
							'width' => '16px',
							'class' => 'arrow-up'
						)
					),
					array(
						'action' => 'reorder',
						'direction' => 'up',
						$data['id']
					),
					array(
						'escape' => false,
					)
				);
			}

			if(!$options['lastChild']) {
				$out .= $this->Html->link(
					$this->Html->image(
						$this->Image->getRelativePath('actions', 'arrow-down'),
						array(
							'alt' => __('Down', true),
							'title' => __('Move down', true),
							'width' => '16px',
							'class' => 'arrow-down'
						)
					),
					array(
						'action' => 'reorder',
						'direction' => 'down',
						$data['id']
					),
					array(
						'escape' => false,
					)
				);
			}

			return $out;
		}

		/**
		 * @brief return the pagination counter text as set in the format
		 * 
		 * @param object $pagintion the pagination helper object
		 * @access public
		 *
		 * @return string the markup
		 */
		public function paginationCounter($pagintion) {
			if (empty($pagintion)) {
				$this->errors[] = 'You did not pass the pagination object.';
				return false;
			}

			return $pagintion->counter(array('format' => __($this->paginationCounterFormat, true)));
		}

		/**
		 * @deprecated
		 * 
		 * create a wysiwyg editor for the field that is passed in. If wysiwyg
		 * is disabled or not installed it will render a textarea.
		 *
		 * @param string $id the field to create a wysiwyg editor for
		 * @param array $config some settings for the editor
		 * @access public
		 * 
		 * @return string markup for the editor
		 */
		public function wysiwyg($id = null, $config = array('toolbar' => 'Full')) {
			if (!$id) {
				$this->errors[] = 'No field specified for the wysiwyg editor';
				return false;
			}

			if (!Configure::read('Wysiwyg.editor')) {
				$this->errors[] = 'There is no editor configured';
			}

			$editor = 'text';

			$_editor = Configure::read('Wysiwyg.editor');
			if (!empty($_editor)) {
				$editor = $_editor;
			}

			return $this->Wysiwyg->load($editor, $id, $config);
		}

		/**
		 * @deprecated
		 *
		 * show a gravitar
		 *
		 * @todo currently only supports gravitars, see the ChartsHelper to make it
		 * more usable
		 *
		 * @param string $email email address
		 * @param array $options the options for the gravitar
		 * @access public
		 * 
		 * @return string the markup of the gravitar
		 */
		public function gravatar($email = null, $options = array()) {
			if (!$email) {
				$this->errors[] = 'no email specified for the gravatar.';
				return false;
			}

			return $this->Gravatar->image($email, $options);
		}

		/**
		 * @brief create some mass action buttons like add, edit, delete etc.
		 *
		 * @param array $buttons the buttons to create
		 * @access public
		 *
		 * @return string the markup for the buttons
		 */
		public function massActionButtons($buttons = null) {
			if (!$buttons) {
				$this->errors[] = 'No buttons set';
				return false;
			}

			$out = '';
			foreach($buttons as $button) {
				$imagePath = $this->Image->getRelativePath(array('actions'), $button);

				$buttonCaption = '<span>';
				if ($imagePath) {
					$buttonCaption .= $this->Html->image($imagePath) . '<br>';
				}

				$buttonCaption .= __(Inflector::humanize($button), true) . '</span>';

				$out .= $this->Form->button(
					 $buttonCaption,
					array(
						'value' => strtolower(str_replace(array('-', ' '), '_', $button)),
						'name' => 'action',
						'title' => $this->niceTitleText($button),
						'div' => false
						)
					);
			}

			return sprintf(
				'<div class="massActions"><div class="wrapper">%s</div></div>',
				$out
			);
		}

		/**
		 * @brief Generate preview links
		 *
		 * create a preview link to a record, expects there to be a preview($id)
		 * method and will use the thickbox plugin if available, or open in a new
		 * window so you can see exactly how the coneten looks without making it active
		 *
		 * uses AppHelper::adminQuickLink to create the url and you must use array() urls
		 *
		 * @param array $row the row to make a preview of
		 * @param array $url some params you want to add to the url
		 * @param string $model if its not the main model
		 * @access public
		 * 
		 * @return string some html for the preview link
		 */
		public function adminPreview($row = array(), $url = array(), $model = ''){
			if(empty($url)){
				$url = array();
			}
			if(!is_array($url)){
				return false;
			}
			
			return $this->Html->link(
				$this->Html->image(
					$this->Image->getRelativePath('actions', 'new-window'),
					array(
						'title' => __('Preview', true),
						'alt' => __('Preview', true)
					)
				),
				array_merge(
					$this->adminQuickLink($row, $url, $model, true),
					array(
						'action' => 'preview',
						'admin' => false,
						'?' => 'TB_iframe=true&width=1000'
					)
				),
				array(
					'target' => '_blank',
					'class' => 'new-window thickbox',
					'escape' => false,
					'title' => __('Preview of the entry', true)
				)
			);
		}

		/**
		 * @brief Generate nice title text.
		 *
		 * This method is used to generate nice looking information title text
		 * depending on what is displayed to the user.
		 *
		 * @param $switch this is the title that is passed in
		 * @param $notHuman if this is true the prettyName function will be
		 *		called making the text human readable.
		 * @access public
		 *
		 * @return string the text for the title.
		 */
		public function niceTitleText($switch = null){
			$switch = prettyName($switch);

			$controller = __(Inflector::singularize($this->params['controller']), true);

			switch(strtolower($switch)){
				case 'add':
					$heading = sprintf('%s %s', __('Create a New', true), $controller);
					$text = sprintf(
						__('Click here to create a new %s. You do not need to '.
							'tick any checkboxes to create a new %s.', true),
						$controller, $controller
					);
					break;

				case 'edit':
					$heading = sprintf('%s %s', __('Edit the', true), $controller);
					$text = sprintf(
						__('Tick the checkbox next to the %s you want to edit '.
							'then click here.<br/><br/>Currently you may only '.
							'edit one %s at a time.', true),
						$controller, $controller
					);
					break;

				case 'copy':
					$controller = __($this->params['controller'], true);
					$heading = sprintf('%s %s', __('Copy some', true), $controller);
					$text = sprintf(
						__('Tick the checkboxes next to the %s you want to copy '.
							'then click here.<br/><br/>You may copy as many %s '.
							'as you like.', true),
						$controller, $controller
					);
					break;

				case 'toggle':
					$controller = __($this->params['controller'], true);
					$heading = sprintf('%s %s', __('Toggle some', true), $controller);
					$text = sprintf(
						__('Tick the checkboxes next to the %s you want to toggle '.
							'then click here.<br/><br/>Inactive %s will become '.
							'active, and active %s will become inactive', true),
						$controller, $controller, $controller
					);
					break;

				case 'delete':
					$heading = sprintf('%s %s', __('Delete some', true), $this->params['controller']);
					$text = sprintf(
						__('Tick the checkboxes next to the %s you want to delete '.
							'then click here.<br/><br/>If possible the %s will be '.
							'moved to the trash can. If not they will be deleted '.
							'permanently.', true),
						$controller, $controller
					);
					break;

				case 'disabled':
					$heading = sprintf('%s %s', __('Delete some', true), $this->params['controller']);
					$text = sprintf(
						__('This %s currently disabled, to enable it tick the '.
							'check to the left and click toggle.', true),
						$controller
					);
					break;

				case 'active':
					$heading = sprintf('%s %s', __('Delete some', true), $this->params['controller']);
					$text = sprintf(
						__('This %s currently active, to disable it tick the '.
							'check to the left and click toggle.', true),
						$controller
					);
					break;

				case 'save':
					$heading = sprintf('%s %s', __('Save the', true), $this->params['controller']);
					$text = sprintf(
						__('Click here to save your %s. This will save your '.
							'current changes and take you back to the index list.', true),
						$controller
					);
					break;

				case 'cancel':
					$heading = sprintf('%s', __('Discard your changes', true));
					$text = sprintf(
						__('Click here to return to the index page without saving '.
							'the changes you have made to the %s.', true),
						$controller
					);
					break;

				case 'move':
					$heading = sprintf('%s %s', __('Move the ', true), $this->params['controller']);
					$text = sprintf(
						__('Tick the checkboxes next to the %s you want to move '.
							'then click here. You will be prompted with a page, '.
							'asking how you would like to move the %s', true),
						$controller, $controller
					);
					break;

				default:
					$heading = $switch;
					$text = 'todo: Need to add something';
			}

			return sprintf('%s :: %s', $heading, $text);
		}

		/**
		 * @todo implement this method or remove it
		 * 
		 * nothing to see, move along
		 * @access public
		 */
		public function niceAltText($text){
			return $text;
		}

		/**
		 * @brief Generate a date picker with the built in jquery datepicker widget.
		 *
		 * @param array $classes
		 * @param string $model the model the picker is for
		 * @param $time show a time picker (or datetime fields)
		 * @access public
		 * 
		 * @return string the markup for the picker
		 */
		public function datePicker($classes, $model = null, $time = false){
			$model = (!$model) ? Inflector::classify($this->params['controller']) : $model;

			$out = '';
			foreach((array)$classes as $class){
				$out .= sprintf(
					'<div class="datePicker"><label>%s</label><div id="%sDatePicker%s"></div>%s</div>',
					Inflector::humanize($class),
					$model,
					ucfirst(Inflector::classify($class)),
					$this->Form->hidden($model.'.'.$class, array('type' => 'text'))
				);
				
				if($time === true){
					$out .= $this->Form->input($model.'.'.str_replace('date', 'time', $class), array('type' => 'time', 'class' => 'timePicker'));
				}

				else if(is_array($time)){
					foreach($time as $t){
						$out .= $this->Form->input($model.'.'.$t, array('type' => 'time', 'class' => 'timePicker'));
					}
				}
				$out .= "\n";
			}

			return $out;
		}

		/**
		 * @var array
		 */
		public $urlCache = array();

		/**
		 * @var string
		 */
		public $urlKey = '';

		/**
		 * @var array
		 */
		public $urlExtras = array();

		/**
		 * @var array
		 */
		public $urlParamFields = array('controller', 'plugin', 'action', 'prefix');

		public function __construct() {
			parent::__construct();

			if (Configure::read('UrlCache.pageFiles')) {
				$view =& ClassRegistry::getObject('view');
				$path = $view->here;
				if ($this->here == '/') {
					$path = 'home';
				}
				$this->urlKey = '_' . strtolower(Inflector::slug($path));
			}

			$this->urlKey = 'url_map' . $this->urlKey;
			$this->urlCache = Cache::read($this->urlKey, 'core');
		}

		/**
		 * @brief before a page is rendered
		 *
		 * @access public
		 *
		 * @link http://api.cakephp.org/class/helper#method-HelperbeforeRender
		 *
		 * @return void
		 */
		public function beforeRender() {
			$this->urlExtras = array_intersect_key($this->params, array_combine($this->urlParamFields, $this->urlParamFields));
		}

		/**
		 * @brief write the new link cache after the page is done being rendered
		 *
		 * @link http://api.cakephp.org/class/helper#method-HelperafterLayout
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function afterLayout() {
			if (is_a($this, 'HtmlHelper')) {
				Cache::write($this->urlKey, $this->urlCache, 'core');
			}
		}

		/**
		 * @brief cache urls so router does less work
		 *
		 * cache urls when the method is called saves using the router doing all
		 * the additional calculations.
		 *
		 * @link http://api.cakephp.org/class/helper#method-Helperurl
		 *
		 * @param mixed $url the url to generate
		 * @param bool $full full url returned or just relative
		 * @access public
		 *
		 * @return string the generated url
		 */
		public function url($url = null, $full = false) {
			$keyUrl = $url;
			if (is_array($keyUrl)) {
				$keyUrl += $this->urlExtras;
			}

			$key = md5(serialize($keyUrl) . $full);
			$key .= md5_file(CONFIGS . DS . 'routes.php');

			if (!empty($this->urlCache[$key])) {
				return $this->urlCache[$key];
			}

			$url = parent::url($url, $full);
			$this->urlCache[$key] = $url;

			return $url;
		}
	}