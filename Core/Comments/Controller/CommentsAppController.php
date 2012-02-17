<?php
	/**
	 * @page Comments-Plugin Comments plugin
	 *
	 * @section charts-overview What is it
	 *
	 * The Comments plugin allows you to add comments to any row of data for your
	 * entire application by simply adding some fields to your database and including
	 * the elements where needed.
	 *
	 * The comments can be anything, not only comments in the sence of blogs. For
	 * example they could also be used for notes on shop orders, communication in
	 * a bugtracker etc.
	 *
	 * The fields used in the comments are flexible, having only the comment field
	 * and the users email address as required fields for the form. Adding extra
	 * fields to the form will be saved in a EAV table handled by the ExpandableBehavior
	 *
	 * @section categories-usage How to use it
	 *
	 * To get comments running in your plugin you will need to do a few things, first
	 * of which is adding a field to the table you wish to allow users to comment on
	 * The field should be called 'comment_count' and should be of type INT() MEDIUMINT()
	 * or any other integer type field you would require.
	 *
	 * After that has been done you will need to include the element for the comment
	 * form and latest comments in your views where you would like to see it. Example
	 * usage is below.
	 *
	 * The comments plugin uses a nifty rating system that checks a number of
	 * configurable details of the comment and gives it a score. If the score is
	 * too low it is regarded as spam. If it is below the threshold it will not
	 * even be saved. A number of factors are included in the score such as the lenght
	 * of the comment, the number of previous comments that were accepted and number
	 * of links that are in the comment body.
	 *
	 * @code
	 *	// simple when using the correct conventions
	 *  // infinitas will figure out the title, and where to save the comment
	 *	echo $this->element('modules/comment', array('plugin' => 'comments'));
	 *
	 *	// more advanced for wierd setups
	 *  // when you dont use conventions you will need to tell infinitas what is what
	 *	$this->element(
	 *		'modules/comment',
	 *		array(
	 *			'plugin' => 'comments',
	 *			'content' => $model,
	 *			'modelName' => 'Model'
	 *		)
	 *	);
	 * @endcode
	 *
	 * @todo currently passing foreign_id which is not needed, the comment element
	 * should figure this out as the model is being passed. it can be obtained from
	 * the data using ->primaryKey
	 *
	 * That is all there is to it. Should you wish to do something really different
	 * you can overlaod the comment module in a theme. you can also change the
	 * plugin that is passed in the second param to anything you like should you
	 * overload it, just remember where and create the file in the corresponding
	 * directory. The other option is to just define your own comment element and
	 * call it in your view. Take note of the core one as there are some conditions
	 * that need to be met like setting the action of the form and the data that
	 * should be passed in.
	 *
	 *
	 * @section categories-code How it works
	 *
	 * Currently the comments are saved by a method in AppController::comment() so
	 * that you can just set the action to 'comment' and it will work as all controllers
	 * extend AppController they inherit the method.
	 *
	 * After the comment is submitted it under goes a rating and a score is determined
	 * for. It can then based on the configuration be accepted and marked as active
	 * automatically if it has passed, or if its edge case will be marked as pending
	 * for admin to later activte it. Comments with very low scores can be set to
	 * not save.
	 *
	 * @image html sql_comments_plugin.png "Comments Plugin table structure"
	 *
	 * @todo record ip addresses of people repeatedly entering comments and block
	 * them for a set time with the ip blocking stuff simmilar to the brute force
	 * attacks. can help reduce sever load by blackholing spam bots.
	 *
	 * @todo limit comments to x per hour or something (setting)
	 *
	 * @section categories-see-also Also see
	 * @ref EventCore
	 * @ref FilterHelper
	 * @ref GravatarHelper
	 * @ref ExpandableBehavior
	 * @ref ExpandableBehavior
	 */

	/**
	 * @brief CommentsAppController is the base class that all comments controllers extend
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::uses('AppController', 'Controller');
	class CommentsAppController extends AppController{
		/**
		 * some helpers to load for this plugin
		 *
		 * @var array
		 * @access public
		 */
		public $helpers = array(
			'Libs.Gravatar'
		);
	}