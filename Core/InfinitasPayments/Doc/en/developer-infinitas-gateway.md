The InfinitasGateway class provides an abstract way of dealing with payment gateway APIs. All the included and addon payment gateway classes get data in a generic format and are then able to manipulate it according to what is required.

### Simple example

	$Gateway = new InfinitasGateway('PayPal', 'express', array(
	   'username' => '--username--',
	   'password' => '--password--',
	   'api' => '--api-key--'
	));

	$result = $Gateway
	   ->item(array('name' => 'item 1', 'selling' => 10.5, 'quantity' => 3))
	   ->item(array('name' => 'item 5', 'selling' => 3.25, 'quantity' => 10))
	   ->shipping(2.4)
	   ->insurance(.9)
	   ->handling(1.5)
	   ->process();

> It is best to wrap the payment gateway calls in a `try / catch` block as they throw exceptions if there are any errors.

`$result` will now contain the details of the transaction. There may be slight implementation differences in each gateway API so it is best to check with each type.

Once all the details have been added to the order you can call the `process()` method which sends the details to the selected payment processor. In the example this was set in the construct but can also be done through a method call.

	$Gateway = new InfinitasGateway();
	$result = $Gateway
	   ->item(array('name' => 'item 5', 'selling' => 3.25, 'quantity' => 10))
	   ->shipping(2.4)
	   ->provider('PayPal', 'express', array(... connection details ...))
	   ->process();

You can change the provider at any point before calling process.

### Batch processing

Here is an example of processing `three` orders at once. If the gateway allows bulk processing all the orders will be sent at once, if not they will be processed transparently as one transaction.

This type of processing is not used for things like cart checkout, but rather monthly subscriptions or other **off-line** processing.

	$result = $Gateway
	   ->user('id' => 123, 'username' => 'bob', ...)
	   ->item(array('name' => 'item 1', 'selling' => 10.5, 'quantity' => 3))
	   ->item(array('name' => 'item 5', 'selling' => 3.25, 'quantity' => 10))
	   ->shipping(2.4)
	   ->insurance(.9)
	   ->handling(1.5)
	->complete()
	   ->user('id' => 234, 'username' => 'sam', ...)
	   ->item(array('name' => 'item 2', 'selling' => 5.75, 'quantity' => 10))
	   ->shipping(5)
	->complete()
	   ->user('id' => 345, 'username' => 'john', ...)
	   ->item(array('name' => 'item 2', 'selling' => 5.75, 'quantity' => 2))
	   ->shipping(5)
	   ->insurance(2)
	->process();

`complete()` marks the end of one order and the start of the next, it will also compute the totals for items, the entire order and calculate tax based on what is passed in. Calling `process()` will also call `complete()` for the current order so it is not required.

