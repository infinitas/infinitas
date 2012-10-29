If you would like to add basic search functionality to a section of your plugin it can be done with a few lines of code. You can use the filter plugin to quickly look up records by specific combinations of fields in the main model and anything that is joined to that model in the usual pagination.

The filter component works by doing a [PRG](http://en.wikipedia.org/wiki/Post/Redirect/Get):

1. Post the search conditions which the Filter plugin catches. Data is converted to a GET URL and a redirect is done.
2. When a GET is determined to be filtered the Filter plugin will prepare all the data for the controller before the action is called.
3. All the params are available in the Filter component, ready to be passed to the paginator.

### Controller code

You would start out with your normal controller and view files. The controller method would look something like the following which would fetch the data and make it available to the view.

	class ExampleController extends ExampleAppController {
		// other methods

		public function admin_index() {
			$data = $this->Paginator->paginate();
			$this->set(compact('data'));
		}

		// more methods
	}

Say the Example model has an `active` field and a `title` that you would want to make searchable. The following code would be added to the controller:

`$this->Filter->filter` holds the details of any filtering that has been submitted. `$filterOptions['fields']` is what is used by the frontend to build the search form.

	public function admin_index() {
		$data = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'active' => (array)Configure::read('CORE.active_options')
		);

		$this->set(compact('data', 'filterOptions'));
	}

Two things have been added. First is the conditions that are to be added to the pagination call. This is what will automatically be filtered when something is searched for. The second is preparing some data for the view so that the search form can be built.

The `$filterOptions['fields']` variable takes an array of fields that are to be searched. For simple text fields you can use a integer keyed array. If not using the `Model.field` syntax found in the form helper the current model is assumed.

	$filterOptions['fields'] = array(
		'field\_1',
		'field\_2',
		'Model.another\_field'
	);

For something more advanced the array becomes `'field' => array()` with the array being values. This allows building filters on related data. For example, to filter the user list by a group you can do the following:

	$filterOptions['fields'] = array(
	   'group\_id' => $this->User->Group->find('list')
	);

### View code

The final change to make is adding the search form to the view. This can either be done using the usual CakePHP method of `$this->Form->input(...)` or making use of the Filter plugins built in helper methods.

The simple option is to add the passed `$filterOptions` to the `adminIndexHead()` method. This will build the usual page header found in the Infinitas backend with all the required markup to match the syles of the other pages.

	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);

Alternativly you can manually call the Filter plugin helper methods. The two methods usually required are `$this->Filter->form()` and `$this->Filter->clear()`. The first method `form()` generates the form markup that is able to submit the details being searched for. The second method `clear()` builds links that are able to remove any filters that have been set.

	echo $this->Filter->form('Post', $filterOptions);
	echo $this->Filter->clear($filterOptions);

If you would like to customise the filtering even more you can have a look at the existing helper methods to see what is being created and do something similar using normal CakePHP FormHelper methods.

### Letter Filter

Another helper method available in the Filter plugin is the `alphabetFilter()` method. This will build the markup displaying an alphabet list that the administrator can use to find records by the `displayFields` first character.

[![](http://assets.infinitas-cms.org/docs/Core/Filter/filter-frontend.png "Example alphabet filter")](http://assets.infinitas-cms.org/docs/Core/Filter/filter-frontend.png)

To build a list for the current model you can call the method with no parameters.

	$this->Filter->alphabetFilter();

Alternativly if you need to display the list for a model that is not the current default you can pass the specified model.

	$this->Filter->alphabetFilter('ExamplePlugin.ExampleModel');

