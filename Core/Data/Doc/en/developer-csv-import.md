A lot of sites require some sort of CSV import functionality. Possibly you are building a Shop that needs thousands of products imported or a list of Newsletter subscribers. The CSV libs provide an interface that makes it easy to do this.

There are two low level classes for working with CSV data. 

#### CSV File Object

This is a PHP SPL class extension that will load the selected CSV file and read it line by line. This method of reading CSV files is extreamly memory efficient no matter what size the CSV file is. Pointers are used in the file and only the line being read is ever put into memory.

The options for reading a file are as follows:

- mode: how the file is [opened](http://www.php.net/manual/en/function.fopen.php) (see `mode`)
- include\_path: boolean Whether to search in the include\_path for filename.
- delimiter: the csv field delimiter default `,`
- enclosure: the csv field enclosure default `"`
- escape: the csv data escape char default `\\`
- heading: boolean, true if first row is headings, false if not (default `true`)

Setting heading to `true` would make the first row the keys for subsequent rows. For example:


##### No Headings

	foo,2012-01-01
	bar,2012-01-02

	array(
		array(
			0 => 'foo',
			1 => '2012-01-01'
		),
		array(
			0 => 'bar',
			1 => '2012-01-02'
		)
	)

##### Headings

	name,created
	foo,2012-01-01
	bar,2012-01-02

	array(
		array(
			'name' => 'foo',
			'created' => '2012-01-01'
		),
		array(
			'name' => 'bar',
			'created' => '2012-01-02'
		)
	)

#### Reading a CSV file

The most basic use of the `CsvFileObject` is as follows. Initialise the class and loop through all the rows.

	App::uses('CsvFileObject', 'Data.Lib/Csv');
	$Csv = new CsvFileObject('/some/path/file.csv');

	$Csv->read();

The `CsvFileObject` class is able to convert data into a format that is familiar to CakePHP. For example if you built your CSV file using headings that are in the format of `ModelName.field\_name` the array structure would resemble what is returned by `find()` calls.

	Example1.name,Example2.name,Example1.created
	foo,baz,2012-01-01
	bar,baz,2012-01-02

	array(
		array(
			'Example1' => array(
				'name' => 'foo',
				'created' => '2012-01-01'
			)
			'Example2' => array(
				'name' => 'baz'
			)
		),
		....
	)

You can also specify the default model in the constructor so that the headings do not require the `ModelName.field\_name` format.

	name,Example2.name,created
	foo,baz,2012-01-01
	bar,baz,2012-01-02

	$Csv = new CsvFileObject('/some/path/file.csv', array(
		'model' => 'FooBar'
	));

	array(
		array(
			'FooBar' => array(
				'name' => 'foo',
				'created' => '2012-01-01'
			)
			'Example2' => array(
				'name' => 'baz'
			)
		),
		....
	)

#### Csv Iterator

The `CsvFileObject` is a really low level class that provides all the formatting of data. It can however be clunky when trying to read the data from the file. For this reason there is the `CsvIterator` which makes it much easier to work with the data.

The `CsvIterator` implements PHP [SPL Iterator](http://php.net/manual/en/spl.iterators.php) and works just like any other iterator. A basic example of using the `CsvIterator` to save data directly to the database.

	$Csv = new CsvIterator(new CsvFileObject('/some/file.csv'));

	for($Csv->rewind(); $Csv->valid(); $Csv->next()) {
		ClassRegistry::init('Example')->saveAll($Csv->current());
	}

> The `CsvIterator` will be linked with the [mass save](/infintias\_docs/Data/developer-mass-save) in future versions so that it will be possible to build importers that can save csv data to the database much quicker.