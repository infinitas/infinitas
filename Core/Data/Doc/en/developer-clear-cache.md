The [ClearCache](https://github.com/ceeram/clear_cache) plugin has been adapted to work in Infinitas. It is part of the core and loaded by defaut so is ready to be used throughout the app.

Added to the default functionality clearing the cache will also remove combined asset files that are created by the Assets plugin when a site is not in debug mode.

#### Console usage

Clear all cache

	cake data.clear\_cache

Clear specific cache files

	cake data.clear\_cache files
	cake data.clear\_cache files .
	cake data.clear\_cache files views
	cake data.clear\_cache files models persistent

Clear specific cache engines

	cake data.clear\_cache engines
	cake data.clear\_cache engines default
	cake data.clear\_cache engines \_cake\_core\_
	cake data.clear\_cache engines default custom

> The shell commands will output a list of what has been done along with any errors that may have been encountered during the cache clearing.

#### From the app

Initialise the ClearCache lib

	App::import('Lib', 'Data.ClearCache');
	$ClearCache = new ClearCache();

Clear all cache

	$output = $ClearCache->run();

Clear specific cache files

	$output = $ClearCache->files();
	$output = $ClearCache->files('.');
	$output = $ClearCache->files('views');
	$output = $ClearCache->files('models', 'persistent');

Clear specific cache engines

	$output = $ClearCache->engines();
	$output = $ClearCache->engines('\_cake\_core\_');
	$output = $ClearCache->engines('default', 'custom');

#### Return data

`$ClearCache->run()` returns an associative array of result

	array(
		'files' => array(
			'deleted' => array(...),
			'error' => array(...)
		),
		'engines' => array(
			'default' => true,
			'\_cake\_core\_' => false
		)
	)

`$ClearCache->files()` returns an associative array of deleted/undeleted files

	array(
		'deleted' => array(...),
		'error' => array(...)
	)

`$ClearCache->engines()` returns an associative array of result

	array(
		'default' => true,
		'\_cake\_core\_' => false
	)
