If you are building a plugin that needs to know when Users are logged in or out of the system you can make use of the Events triggered. These will happen when a `login` or `logout` is performed.

There are a few different events related to the login actions:

- `userLogin`: This is called after a user is sucsessfully logged into the application
- `beforeUserLogout`: This is called before a logout happens
- `afterUserLogout`: This is called after a user has been logged out

### Login Example

You are building an application where users are linked to a company. You dont want a user to log in if the company is disabled or does not exist. You can use the `userLogin` event to preform these checks and cancel the login if something is not correct. An example is as follows:

	public function onUserLogin(Event $Event) {
		$company = ClassRegistry::init('CompanyModel')->checkUserCompany($Event->Handler->Auth->user('id'));
		if(!$company) {
			$Event->Handler->Session->destroy();
			$Event->Handler->notice(__d('company', 'Your login has been disabled'), array(
				'redirect' => true,
				'level' => 'warning'
			));
		}

		return $Event->Handler->Session->write('Auth.Company', $company);
	}

When a guest browsing your Shop has added items to the cart, you may need to do some processing should they log in. This event can also be used to do that post login processing.

### Before logout

If you require a user to preform some action before logging out the `beforeUserLogout` event can be used. Users do not always specifically log out though so you should not rely on this event being called. If the session times out this event will not be triggered at all.

### After logout

You can use this method to do any processing after a user has logged out. Exmaples may include cleaning up any tempory data that has been created or changing the redirect.