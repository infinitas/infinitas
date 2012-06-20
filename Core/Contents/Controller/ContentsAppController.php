<?php
	/**
	 * @page Contents-Plugin Contents plugin
	 *
	 * @section contents-plugin-overview What is it
	 *
	 * there is currently two parts to the contents plugin. First being Content
	 * and the second being the Layout of said content.
	 *
	 * @section contents-plugin-usage How to use it
	 *
	 * The idea is that you create a simple model with a few extra fields that you
	 * like such as active, ordering etc. To get the contents linked to your model
	 * you need to tell Infinitas, this is done by adding a flad to the model.
	 *
	 * The two parts of the contents plugin are as follows
	 *
	 * @ref contents-plugin-content is for the content
	 *
	 * @ref contents-plugin-layout is for the layout of said content
	 * 
	 * @section contents-plugin-code How it works
	 *
	 * Through the Event system the content models and behaviors will automatically
	 * load and attach onto your model. They will also catch the finds and add
	 * contain statements so that the data is pulled from the database. After a
	 * find is done, the data will be reformatted into your model so you would use
	 * it just like it was directly in your model.
	 *
	 * @bug because cake does not trigger callbacks on related models doing some
	 * things like find('list') to your model will not show the title fields.
	 *
	 * @image html sql_contents_plugin.png "Contents Plugin table structure"
	 *
	 * @section contents-plugin-see-also Also see
	 * @ref EventCore
	 */

	/**
	 * @brief ContentsAppController is the base controller class that all contents controllers
	 * extend from.
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contents
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ContentsAppController extends AppController {
		
	}

	/**
	 * @page contents-plugin-content Contents plugin - Content
	 *
	 * @section contents-plugin-content-overview What is it
	 *
	 * The Contents plugin provides an easy interface to add the basic fields to
	 * a record like tile, body created and modified. There are a number of advantages
	 * to using this for your models including, less code to write and easier to
	 * preform site wide searches as the data is all in one table.
	 *
	 * @section contents-plugin-content-usage How to use it
	 *
	 * The idea is that you create a simple model with a few extra fields that you
	 * like such as active, ordering etc. To get the contents linked to your model
	 * you need to tell Infinitas, this is done by adding a flad to the model.
	 *
	 * @code
	 *	// adding content to your models
	 *	class MyModel extends MyPluginAppModel{
	 *		// ...
	 *		public $contentable = true;
	 *		// ...
	 *	}
	 *
	 *	// the fields will be something like this even though your model does not
	 *	// have title or body fields
	 *
	 *	$myModel['MyModel']['title']; // will contain the title
	 *	$myModel['MyModel']['body']; // will contain the body / description
	 * @endcode
	 *
	 * Now you need the forms to add this data. All you need to do in your add/edit views
	 * is include the fields from your model and then add a call to the contents
	 * element. It should look something like the following:
	 *
	 * @code
	 *	echo $this->Form->create('Post');
	 *		// the next line include things like title, content, keywords, descriptions etc
	 *		echo $this->element('content_form', array('plugin' => 'contents'));
	 *
	 *		// your custom fields
	 *		echo $this->Form->input('active');
	 *		echo $this->Form->input('other_field');
	 *		echo $this->Form->input('something');
	 *	echo $this->Form->end();
	 * @endcode
	 *
	 * If you do not like the format of the element you can create your own or
	 * overload the element in your theme. Have a look at the built in element for
	 * some configuration options and how to overload it.
	 *
	 * @section contents-plugin-content-code How it works
	 *
	 * Through the Event system the content model and behaviors will automatically
	 * load and attach onto your model. They will also catch the finds and add
	 * contain statements so that the data is pulled from the database. After a
	 * find is done, the data will be reformatted into your model so you would use
	 * it just like it was directly in your model.
	 *
	 * @bug because cake does not trigger callbacks on related models doing some
	 * things like find('list') to your model will not show the title fields.
	 *
	 * @section contents-plugin-content-see-also Also see
	 * @ref GlobalLayout
	 */

	/**
	 * @page contents-plugin-layout Contents plugin - Layout
	 *
	 * @section overview What is it
	 *
	 * The GlobalLayout is a way to save a customized view template for a type
	 * of record. They allow you to create a layout in the backend that is parsed
	 * using the mustache templating language. It is efectivly moving the view.ctp
	 * files that you would use to the database, whilst adding a lot more options
	 * with no hacks.
	 *
	 * The advantage of doing this is that you can create different layouts for
	 * different categories, or any other way you see fit. This means that you
	 * do not need to edit the view files for conten or hack various if statments
	 * to render different views.
	 *
	 * @section usage How to use it
	 *
	 * Cee @ref contents-plugin-content for activating the content. After that is
	 * done, all that is needed is to create the view file, and some layouts.
	 *
	 * This is a simple example of how you can set up a view file to be ready for
	 * template rendering. Asuming this was a Post model the view would look like
	 * this. Yes that is all that is needed :)
	 *
	 * @code
	 *	// customized css for the layout
	 *	if(!empty($post['Layout']['css'])) {
	 *		?><style type="text/css"><?php echo $post['Layout']['css']; ?></style><?php
	 *	}
	 *
	 *	// render the content template
	 *	echo $post['Layout']['html'];
	 * @endcode
	 *
	 * Obviously sometimes you want to display the created or modified date, maybe
	 * need to truncate some stuff? You can do this in the view, there is just one
	 * trick to remember. if modifying any data in the view you should call $this->set('name', $name)
	 * for that array. This is so that the interanl _viewVariables will be updated.
	 * It can be done something like below:
	 *
	 * @code
	 *	// make the date nice
	 * 
	 *	$post['Post']['created'] = $this->Time->niceShort($post['Post']['created']);
	 * 
	 *	// make the title shorter (note title is from GlobalContent but in the Post data)
	 *	$post['Post']['title'] = $this->Text->truncate($post['Post']['title'], 50);
	 *
	 *	// make a link var
	 *	$post['Post']['title_link'] = $this->Html->link($post['Post']['title'], array('action' => 'view', $post['Post']['id']));
	 *
	 *	// reset the viewVars IMPORTANT!
	 *	$this->set('post', $post);
	 *
	 *	// customized css for the layout
	 *	if(!empty($post['Layout']['css'])) {
	 *		?><style type="text/css"><?php echo $post['Layout']['css']; ?></style><?php
	 *	}
	 *
	 *	// render the content template
	 *	echo $post['Layout']['html'];
	 * @endcode
	 *
	 * Below are some examples to get you started with layouts, see the Mustache
	 * docs for more information about what is available.
	 *
	 * @code
	 *	<div class="wrapper">
	 *		<div class="introduction {{post.Layout.name}}">
	 *			<h2>{{post.Post.title_link}}<small>{{post.Post.created}}</small></h2>
	 *			<div class="content {{post.Layout.name}}">{{post.Post.body}}</div>
	 *		</div>
	 *	</div>
	 * @endcode
	 *
	 * @section code How it works
	 *
	 * When your model has been attached to the GlobalContent model the GlobalLayout
	 * will automatically be added to the queries in the same way as GlobalContent.
	 * The Mustache template engine is loaded into the View class and parses all the data
	 * looking for templates to use. All the viewVariables are also passed into
	 * the Mustache engine so that the data can be built up.
	 *
	 * The end result is that your views are built up dynamically providing more
	 * flexability, with less hacks.
	 *
	 * @todo move this to mustache docs
	 *
	 * There are some debugging methods available to see what is going on. While
	 * in debug mode add ?mustache=false to the url to disable the rendering and
	 * have Infinitas display the template as it is. You should see the template
	 * displayed as above (not the html though, just the {{some.var}} parts.
	 *
	 *
	 * @section see-also Also see
	 * @ref GlobalContent
	 * @ref Mustache
	 */