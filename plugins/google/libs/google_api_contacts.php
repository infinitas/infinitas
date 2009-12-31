<?php
/**
 * Google Api Contacts Class
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
 * @subpackage google.vendors.GoogleApiContacts
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import( 'Lib', 'GoogleApiBase' );

/**
 * GoogleApiContacts
 *
 * Datasource for Google Contacts Custom Methods
 */
class GoogleApiContacts extends GoogleApiBase
{
  public function __construct( $config )
  {
    // CP is the key for google contacts api, this is passed to the auth to tell we are using contacts
    $config['service'] = 'cp';
    parent::__construct( $config );
  }

  /**
  * Get Google Contacts Schema
  *
  * @return array $schema
  * @access public
  */
  public function getSchema()
  {
    $schema = array(
                'google_contacts' => array(
                                     'gd:etag' => array(
                                                  'type' => 'string',
                                                  'null' => true
                                                ),
                                     'id' => array(
                                             'type' => 'string',
                                             'null' => true,
                                             'length' => 255
                                           ),
                                     'updated' => array(
                                                  'type' => 'string',
                                                  'null' => true
                                                ),
                                     'edited' => array(
                                                 'type' => 'string',
                                                 'null' => true
                                               ),
                                     'category' => array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                     'title' => array(
                                                'type' => 'string',
                                                'null' => false
                                              ),
                                     'name' => array(
                                               'type' => 'string',
                                               'null' => true
                                             ),
                                     'nickname' => array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                     'birthday' => array(
                                                   'type' => 'string',
                                                   'null' => true
                                                 ),
                                     'organization' => array(
                                                       'title' => array(
                                                                  'type' => 'string',
                                                                  'null' => true
                                                                ),
                                                       'company' => array(
                                                                    'type' => 'string',
                                                                    'null' => true
                                                                  )
                                                     ),
                                     'email' => array(
                                                'primary' => array(
                                                             'type' => 'boolean',
                                                             'null' => true
                                                           ),
                                                'address' => array(
                                                             'type' => 'string',
                                                             'null' => true
                                                           ),
                                                'category' => array(
                                                              'type' => 'string',
                                                              'null' => true
                                                            )
                                              ),
                                     'im' => array(
                                             'address' => array(
                                                          'type' => 'string',
                                                          'null' => true
                                                        ),
                                             'category' => array(
                                                           'type' => 'string',
                                                           'null' => true
                                                         )
                                           ),
                                     'phones' => array(
                                                 'phone' => array(
                                                            'type' => 'integer',
                                                            'null' => true
                                                          ),
                                                 'category' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             )
                                               ),
                                     'address' => array(
                                                  'address' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             ),
                                                  'category' => array(
                                                                'type' => 'string',
                                                                'null' => true
                                                              )
                                                ),
                                     'events' => array(
                                                 'date' => array(
                                                           'type' => 'string',
                                                           'null' => true
                                                         ),
                                                 'category' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             )
                                               ),
                                     'relations' => array(
                                                    'name' => array(
                                                              'type' => 'string',
                                                              'null' => true
                                                            ),
                                                    'category' => array(
                                                                  'type' => 'string',
                                                                  'null' => true
                                                                )
                                                  ),
                                     'custom' => array(
                                                 'key' => array(
                                                          'type' => 'string',
                                                          'null' => true
                                                        ),
                                                 'value' => array(
                                                            'type' => 'string',
                                                            'null' => true
                                                          )
                                               ),
                                     'websites' => array(
                                                   'address' => array(
                                                                'type' => 'string',
                                                                'null' => true
                                                              ),
                                                   'category' => array(
                                                                 'type' => 'string',
                                                                 'null' => true
                                                               )
                                                 ),
                                     'groups' => array(
                                                 'group_id' => array(
                                                               'type' => 'string',
                                                               'null' => true
                                                             ),
                                                 'deleted' => array(
                                                              'type' => 'boolean',
                                                              'null' => true
                                                            )
                                               )
                                   )
              );
    return $schema;
  }

  /**
  * Convert Google Contacts schema-based Object into Atom text
  *
  * @return string $atom
  * @access public
  */
  public function toAtom($object) {
    $atom = "<atom:entry xmlns:atom='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005' xmlns:gContact='http://schemas.google.com/contact/2008'>";
    if (isset($object['id'])) {
      $atom .= "<id>".$object['id']."</id>";
    }
    if (isset($object['updated'])) {
      $atom .= "<updated>".$object['updated']."</updated>";
    }
    if (isset($object['edited'])) {
      $atom .= "<app:edited xmlns:app='http://www.w3.org/2007/app'>".$object['edited']."</app:edited>";
    }
    if (isset($object['Category'])) {
      $atom .= "<category scheme='".$object['Category']['scheme']."' term='".$object['Category']['term']."'/>";
    }
    if (isset($object['title'])) {
      $atom .= "<title>".$object['title']."</title>";
    }
    if (isset($object['content'])) {
      $atom .= "<atom:content type='text'>".$object['content']."</atom:content>";
    }
    if (isset($object['Link'])) {
      foreach ($object['Link'] as $Link) {
        $atom .= "<link rel='".$Link['rel']."' type='".$Link['type']."' href='".$Link['href']."'/>";
      }
    }
    if (isset($object['Name'])) {
      $atom .= "<gd:name>";
      $atom .= "<gd:fullName>".$object['Name']['fullName']."</gd:fullName>";
      $atom .= "</gd:name>";
    }
    if (isset($object['nickname'])) {
      $atom .= "<gContact:nickname>".$object['nickname']."</gContact:nickname>";
    }
    if (isset($object['Birthday'])) {
      $atom .= "<gContact:birthday when='".$object['Birthday']['when']."'/>";
    }
    if (isset($object['Organization'])) {
      $atom .= "<gd:organization rel='".$object['Organization']['rel']."'>";
      $atom .= "<gd:orgName>".$object['Organization']['orgName']."</gd:orgName>";
      $atom .= "<gd:orgTitle>".$object['Organization']['orgTitle']."</gd:orgTitle>";
      $atom .= "</gd:organization>";
    }
    if (isset($object['Email'])) {
      foreach ($object['Email'] as $Email) {
        $primary = isset($Email['primary'])?"true":"false";
        $atom .= "<gd:email rel='".$Email['rel']."' address='".$Email['address']."' primary='".$primary."'/>";
      }
    }
    if (isset($object['Im'])) {
      foreach ($object['Im'] as $Im) {
        $atom .= "<gd:im address='".$Im['address']."' protocol='".$Im['protocol']."' rel='".$Im['rel']."'/>";
      }
    }
    if (isset($object['PhoneNumber'])) {
      foreach ($object['PhoneNumber'] as $PhoneNumber) {
        $atom .= "<gd:phoneNumber rel='".$PhoneNumber['rel']."'>".$PhoneNumber['value']."</gd:phoneNumber>";
      }
    }
    if (isset($object['StructuredPostalAddress'])) {
      foreach ($object['StructuredPostalAddress'] as $StructuredPostalAddress) {
        $atom .= "<gd:structuredPostalAddress rel='".$StructuredPostalAddress['rel']."'>";
        $atom .= "<gd:formattedAddress>".$StructuredPostalAddress['formattedAddress']."</gd:formattedAddress>";
        $atom .= "</gd:structuredPostalAddress>";
      }
    }
    if (isset($object['Event'])) {
      foreach ($object['Event'] as $Event) {
        $atom .= "<gContact:event rel='".$Event['rel']."'>";
        $atom .= "<gd:when startTime='".$Event['When']['startTime']."'/>";
        $atom .= "</gContact:event>";
      }
    }
    if (isset($object['Relation'])) {
      foreach ($object['Relation'] as $Relation) {
        $atom .= "<gContact:relation rel='".$Relation['rel']."'>".$Relation['value']."</gContact:relation>";
      }
    }
    if (isset($object['UserDefinedField'])) {
      foreach ($object['UserDefinedField'] as $UserDefinedField) {
        $atom .= "<gContact:userDefinedField key='".$UserDefinedField['key']."' value='".$UserDefinedField['value']."'/>";
      }
    }
    if (isset($object['Website'])) {
      foreach ($object['Website'] as $Website) {
        $atom .= "<gContact:website href='".$Website['href']."' rel='".$Website['rel']."'/>";
      }
    }
    if (isset($object['GroupMembershipInfo'])) {
      foreach ($object['GroupMembershipInfo'] as $GroupMembershipInfo) {
        $atom .= "<gContact:groupMembershipInfo deleted='".$GroupMembershipInfo['deleted']."' href='".$GroupMembershipInfo['href']."'/>";
      }
    }
    $atom .= "</atom:entry>";
    return $atom;
  }
}
