<?php
/**
* Google Api Base Class
*
* Methods used to interact with Google Api
*
* Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
* @link http://www.imekinox.com
* @package google
* @subpackage google.controllers.GoogleContactsController
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

/**
* Google Contacts Controller for testing Datasource
*
* Configuration in app/config/database.php:
*
* var $google_contacts = array(
*     'datasource'  => 'google_contacts',
*     'accounttype' => 'GOOGLE',
*     'email'       => 'YOUR GOOGLE EMAIL',
*     'passwd'      => 'YOUR PASSWORD',
*     'source'      => 'companyName-applicationName-versionID',
*     'database'    => ''
* );
*
* Find methods:
* - all
* - count
* - first
*
* Default limit is 25 use limit for override.
*
* Available conditions:
* - start-index -> For paging
* - updated-min -> The lower bound on entry update dates.
* - orderby     -> Sorting criterion. The only supported value is lastmodified.
* - showdeleted -> Include deleted contacts in the returned contacts feed.
* - group       -> Value of this parameter specifies group ID
*
* Restult array keys:
*
* Use debug($this->GoogleContacts->_schema); to get structured array
*
*/

class GoogleContactsController extends AppController
{
  var $name = 'GoogleContacts';
  var $uses = array( 'GoogleContacts' , 'Users' );

  function testFindById(){
    $contact = $this->GoogleContacts->find('first');
    $res = $this->GoogleContacts->findById($contact['id']);
    debug($res);
    die();
  }

  function testFindAllLimit5(){
    $contact = $this->GoogleContacts->find('all', array('limit'=>5));
    debug($contact);
    die();
  }
  
  function testFindAll(){
    $contact = $this->GoogleContacts->find('all');
    debug($contact);
    die();
  }

  function testCount(){
    $contact = $this->GoogleContacts->find('count');
    debug($contact);
    die();
  }
  
  function testFindFirst(){
    $contact = $this->GoogleContacts->find('first');
    debug($contact);
    die();
  }
  
  function testUpdate() {
    $contact = $this->GoogleContacts->find('first');
    $contact['Name']['fullName'] = "Contact Changed From Cake";
    $this->GoogleContacts->create($contact);
    $r = $this->GoogleContacts->save();
    debug($r);
    die();
  }

  function index()
  {
    $contact['title'] = "NEW CONTACT";
    $res = $this->GoogleContacts->create('Juanito');
    debug($res);
    $this->GoogleContacts->save($contact);
    //debug($r);
  }
}