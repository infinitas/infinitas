If you are working on importing large amounts of data or generally doing large saves the `BigDataBehavior` behavior may be able to speed up the saves by quite a bit. By default methods such as `saveAll` in CakePHP save a single row at a time which creates a lot of overhead with all the connections to the database.

The `BigDataBehavior` can speed this up by creating `multi insert` statements which reduces the number of connections to the database, and also by turing off `indexes` before running the save query.

The `rawSave` method will automatically figure out the fields that can be saved and order the data for each record so that the correct data is saved to the correct field.

#### Example

We have a table called `examples` with an `Example` model. It has the following fields:

- id (uuid)
- name
- body
- created

	$data = array(
		array(
			'invalid' => 'foo',
			'name' => 'A',
			'body' => 'a'
		),
		array(
			'body' => 'b',
			'name' => 'B'
		),
		array(
			'name' => 'C',
			'body' => 'c'
		),
	);
	$this->Example->rawSave($data);

This will generate `SQL` similar to the following:

	INSERT INTO `examples` (`id`, `name`, `body`, `created`) VALUES 
	('550e8400-e29b-41d4-a711-446655440000', 'A', 'a', '2012-01-01 00:00:00'), 
	('550e8400-e29b-41d4-a712-446655440000', 'B', 'b', '2012-01-01 00:00:00'), 
	('550e8400-e29b-41d4-a713-446655440000', 'C', 'c', '2012-01-01 00:00:00');

The UUID `id` has been detected and a UUID generated for each record, the fields have been correctly ordered and the magic `created` and / or `modified` fields are correctly generated.

#### Validation

You can also validate data before saving. This is done before any save is attempted so is not suitable for validating things such as `isUnique`. That would require doing the validation for each record after saving each row. It does however work for things like `notEmpty`, min / max length and so on. This makes use of the validation in the model doing the save.

	$this->Example->rawSave($data, array(
		'validate' => true
	));

#### Indexing

Database engines will generally rebuild indexes after data is added or modified. This can take a large amount of time especially when there is a large amount of data. The method can detect the type of engine that is being used such as `InnoDB` or `MyISAM` and disable the indexing before running the save. The indexes will be enabled again once the save has completed.

To auto detect the indexing you can use the following

	$this->Example->rawSave($data, array(
		'disableIndex' => true
	));

Or you can do it manually

	$this->Example->rawSave($data, array(
		'disableIndex' => array(
			'unique\_checks',
			'foreign\_key\_checks',
		)
	));

#### Transactions

When using an engine that supports transactions they can be used to improve the speed of inserts too. This can be done with the following option:
	
	$this->Example->rawSave($data, array(
		'transaction' => true
	));

#### Data chunking

Most database engines will have a limit on the amount of data that can be sent to them. You could not for example save 500,000 rows of data with large text fields as you would go over the limit for the save. For this reason you can chunk the data into a number of smaller saves which will still generate the `multi inserts` but fall below the maximum size.

To save 1,000 rows at a time you could do the following.

	$this->Example->rawSave($data, array(
		'chunk' => 1000
	));

> In this example with 500,000 rows, saving 1,000 at a time will require 500 save opperations on the database. This is far less than the 500,000 you would encounter with using CakePHP `saveAll`.