Making rows lockable is quite simple. It makes use of a behavior to do the record locking and a component for unlocking and displaying errors when users attempt to edit a row that is locked that they did not lock. There is also a helper for displaying the status along with a message of who locked the row and when it was locked.

#### Model 

To make rows lockable all that is required is setting a property on the model. The Lockable behavior is automatically attached through the Events plugin when a model is loaded, but only when this property is set and has a value of `true`.

	public $lockable = true;

It is possible to to set the value within the constructor but will need to be done before calling parent as that is when it is automatically added. The behavior could also be loaded at a later stage in the request using the usual CakePHP behavior collection, although that is not recomended as it could be loaded too late and allow a row to be edited that should have been locked.

Finds will automatically contain the locked information as the behavior will automatically attach this during the find. 

#### Controller

There is nothing specific required in the controller level of your code for lockable to function. Locks automatically includes a component through the Events system that will make the required checks to stop users editing records that are already locked. This same component will also remove locks once the locked record has been saved or the action canceled. These actions should be done through the [mass actions](/infinitas\_docs/Libs) to be automated.

#### View

To show details of the locked status there is a helper method that will show if a record has been locked or not, and if so who by and when it was locked. The method takes the entire row of data from a find call. For example in a blog listing in the admin backed you would do something like the following:

	<tr>
		... Some Headings ...
	</tr>
	<?php
		foreach($posts as $post) {
			...
			<td><?php echo $this->Locked->display($post); ?></td>
			...
		}
	?>

By default the lock data will be in a `Lock` key of the row in question. If you are doing some custom locking and using an alias the method takes a second param as the Lock data alias.

	echo $this->Locked->display($post, 'CustomLockModel');
