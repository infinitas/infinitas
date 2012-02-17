<?php
	/**
	 * @page Contact-Plugin Contact plugin
	 *
	 * @section contact-overview What is it
	 *
	 * The Contact plugin provides a way to list and show all the contacts in the
	 * company per branch. You are also able add and list the different branches or
	 * departments.
	 *
	 * The plugin can be configured to show contact forms that users will be able
	 * to use to contact the inderviduals and/or branch that they need.
	 *
	 * @section contact-usage How to use it
	 *
	 * Set up a Route to the Contact plugin in the format that you wish. Branches
	 * are by default routed to /contact/branches/index and contacts are in
	 * /contact/contacts/index. Its recomened to create routes for each, something
	 * like /contact for branches, /contact/:branch to show the branch and
	 * /contact/:branch/:contact for the contacts.
	 *
	 * After you have created the Routes you can go ahead and start creating the
	 * branches and contacts that you require. If you have a small company with
	 * only one branch you still need to enter the basic information, users will
	 * be automatically redirected to that branch when attempting to view the list
	 * of branches.
	 *
	 * @section contact-code How it works
	 *
	 * Each Branch can have many Contac and the Contact belongs to a particular
	 * Branch. The Contact form integrates with the EmailerComponent to send
	 * emails from the contact forms to the Contact in question.
	 *
	 * There is the VcfHelper which generates vCards for users to download and
	 * save to their favorite contacts application like outlook and gmail etc
	 *
	 * Other helpers include the ContactsHelper. This is used to generate card
	 * like elements in the backend. All the helpers can be used throughout your
	 * application should you need some of its functionality
	 *
	 * @image html sql_contact_plugin.png "Contact Plugin table structure"
	 *
	 * @section contact-see-also Also see
	 * @ref ContactsHelper
	 * @ref VcfHelper
	 * @ref Contact
	 * @ref Branch
	 */

	/**
	 * @brief The main contact's plugin model
	 *
	 * this is extended by the other models in contact plugin
	 *
	 * @todo some sort of telephone index using the Branch and Contact model
	 * could be done with FeedableBehavior
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contact
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::uses('AppController', 'Controller');
	class ContactAppController extends AppController {
		/**
		 * Helpers used within this plugin
		 *
		 * @var array
		 * @access public
		 */
		public $helpers = array(
			'Contact.Vcf',
			'Html',
			'Google.StaticMap'
		);

		public function beforeFilter(){
			parent::beforeFilter();

			$this->RequestHandler->setContent('vcf', 'text/x-vcard');

			return true;
		}
	}