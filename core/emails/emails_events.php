<?php
	final class EmailsEvents extends AppEvents{
		public function onRequireDatabaseConfigs($event){
			return array(
				'emails' => array(
					'datasource' => 'Emails.Imap'
				)
			);
		}

		public function onPluginRollCall(){
			return array(
				'name' => 'Emails',
				'description' => 'Manage your mails',
				'author' => 'Infinitas',
				'icon' => '/emails/img/icon.png',
				'dashboard' => array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'dashboard')
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Dashboard' => array('controller' => 'mail_systems', 'action' => 'dashboard'),
				'Accounts' => array('controller' => 'email_accounts', 'action' => 'index'),
			);

			return $menu;
		}

		public function onSlugUrl($event, $data){
			switch($data['type']){
				case 'inbox':
					return array(
						'plugin' => 'emails',
						'controller' => 'mail_systems',
						'action' => 'index',
						'slug' => $data['data']['EmailAccount']['slug'],
						'account' => $data['data']['EmailAccount']['id']
					);
					break;

				case 'view':
					return array(
						'plugin' => 'emails',
						'controller' => 'mail_systems',
						'action' => 'view',
						'slug' => $data['data']['EmailAccount']['slug'],
						'account' => $data['data']['EmailAccount']['id'],
						'email' => $data['data']['MailSystem']['id'],
						'subject' => $data['data']['MailSystem']['slug']
					);
					break;

				case 'mail':
					return array(
						'plugin' => 'emails',
						'controller' => 'mail_systems',
						'action' => 'admin_get_mail',
						'slug' => $data['data']['EmailAccount']['slug'],
						'account' => $data['data']['EmailAccount']['id'],
						'email' => $data['data']['MailSystem']['id']
					);
					break;

				default:
					pr($data);
					exit;
					break;
			}
		}

		public function onSetupRoutes($event){
			// mail render
			Router::connect(
				'/admin/mail/:slug/:account/:email',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'get_mail', 'admin' => true),
				array('pass' => array('slug', 'account', 'email'))
			);
			// view
			Router::connect(
				'/admin/inbox/:slug/:account/:email/:subject',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'view', 'admin' => true),
				array('pass' => array('slug', 'account', 'email'))
			);
			
			// inbox
			Router::connect(
				'/admin/inbox/:slug/:account',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'index', 'admin' => true),
				array('pass' => array('slug', 'account'))
			);
		}
	}
