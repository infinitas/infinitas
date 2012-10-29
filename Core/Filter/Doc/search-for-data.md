Most plugins that contain a significant amount of data will implement some kind of filtering or search in the admin backend. Examples of the default filtering can be seen throughout the core plugins such as Users and the official plugins such as Blog, Cms and Shop plugins.

The Filter plugin is normally made available on the index actions, that is the pages where lists of data can be found for the particular section of the site.

The fields that are filterable will normally cover most usage cases, but if a more complex search is required this my be built into a custom plugin in which case you would need to read its documentation for more information.

When available the `letter list` will allow you to click on a letter which will display all data that has its main field begining with the chosen letter. You may also select a number (the `#` symbol) or any other character (clicking `?` will display anything that is not beginning with `A-Z` or `0-9`). There is also a link to the right hand side, `All`, which will clear the filter and display all results once again.

### What to look for

An example of the usual Filter options can be seen below. When available you could have a `search` icon that will drop down the search form and / or the `letter list` that provides access to filter data by the first letter of a field. 

[![](http://assets.infinitas-cms.org/docs/Core/Filter/filter-frontend.png "Normal filtering")](http://assets.infinitas-cms.org/docs/Core/Filter/filter-frontend.png)

When clicking on the `search` icon a form will open up that allows you to search with the specified options. Normally you will have a choice between input boxes and drop downs where appropriate.

[![](http://assets.infinitas-cms.org/docs/Core/Filter/search-form-frontend.png "Search form")](http://assets.infinitas-cms.org/docs/Core/Filter/search-form-frontend.png)