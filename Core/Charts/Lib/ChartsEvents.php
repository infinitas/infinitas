<?php
	/**
	 * @page Charts-Plugin Charts plugin
	 *
	 * @section charts-overview What is it
	 *
	 * The Charts plugin is an abstraction of data that uses the Adapter
	 * design pattern to draw the charts. This make it really simple to extend
	 * and change. There are some core classes included for some types of charts
	 * that you can use as is, or as a base to create your own.
	 *
	 * @section categories-usage How to use it
	 *
	 * To get started creating some charts you will need to include the Charts
	 * helper in your code, this can be done by adding either of the following
	 *
	 * @code
	 *  // could also be within another helper
	 *	class MyController extends MyPluginAppController{
	 *		public $helpers = array(
	 *			// ...
	 *			'Charts.Charts' => array(
	 *				'MyPlugin.Example'
	 *			)
	 *			// ...
	 *		)
	 *	}
	 *
	 *	// or from within a controller method
	 *
	 *	$this->helpers['Charts.Charts'][] = 'MyPlugin.Example';
	 * @endcode
	 *
	 * Both examples will look for and include a helper in the folder
	 * plugins/my_plugin/views/helpers/example_chart_engine.php with and the class
	 * should lool like this:
	 *
	 * @code
	 *	class ExampleChartEngineHelper extends ChartsBaseEngineHelper{
	 *		// ...
	 *	}
	 * @endcode
	 *
	 * After having done this you will be able to use the charts helper to start
	 * building charts for what ever data you have. You also have the option of
	 * making a certain chart globally available by including it with the Event
	 * system.
	 *
	 * A basic example of creating a html chart will look something like below.
	 * Note that this is almost the format that the charts engine produces, basically
	 * it will shuffle things around to be more simple to manipulate and allows
	 * for a few other options like passing size as '900,150' (a string) instead
	 * of the array format. This is true for all options.
	 * 
	 * @code
	 *	echo $this->Charts->draw(		
	 *		'bar',
	 *		array(
	 *			'data' => array(10, 15, 20, 5, 10, 40),
	 *			'axes' => array(
	 *				'x' => array('a', 'b', 'c', 'd', 'e', 'f'),
	 *				'y' => ''
	 *			),
	 *			'size' => array(900, 150)
	 *			'tooltip' => true
	 *		)
	 *	);
	 * @endcode
	 *
	 * @section categories-code How it works
	 *
	 * When the charts helper is initialized it take the engine that you passed
	 * and adds it to the helpers array. Pretty basic stuff, its just adding another
	 * helper.
	 *
	 * When you call ChartsHelper::draw() with the params it will take all the
	 * data passed and make sure its mostly valid and ready for the chart engine
	 * to process. This makes it really simple to either switch the chart engine
	 * at a later stage and develop new engines that can be used with ease.
	 *
	 * The Charts helper will also take care of caching so times when the data
	 * has been cached there will be no calls to the actual engine drawing the
	 * charts.
	 *
	 * @section categories-see-also Also see
	 * @ref EventCore
	 * @ref GoogleStaticChartHelper
	 * @ref HtmlChartEngineHelper
	 */

	/**
	 * @brief Charts plugin events
	 *
	 * The events for the charts plugin allows the plugin to integrate with the
	 * core code.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Charts
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ChartsEvents extends AppEvents {
		public function onRequireLibs() {
			App::uses('ChartDataManipulation', 'Charts.Lib');
		}

		public function onRequireHelpersToLoad($event = null) {
			return array(
				'Charts.Charts' => array(
					Configure::read('Charts.default_engine')
				)
			);
		}
	}