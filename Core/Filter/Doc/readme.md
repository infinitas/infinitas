The Filter plugin gives developers an easy way to provide basic search capabilities in their code. Generally a search by `status` and or `name` would be sufficient for most backend applications, which is what this plugin does with very limited code.

The filter plugin is limited to **simple search** such as partial / full text, status and relations across the main data table and relations. The plugin also provides a number of ways to build search forms for users.

For more information on searching for data see the [user guide](/infinitas\_docs/Filter/search-for-data) or see the [developer docs](/infinitas\_docs/Filter/developer-building-search-forms).

## Background

> This information comes from the original readme that the code was adapted from.

This plugin is a fork of [Jose Gonzalez's component](http://github.com/josegonzalez/cakephp-filter-component), which is something of a fork of [James Fairhurst's](http://www.jamesfairhurst.co.uk/posts/view/cakephp_filter_component/) Filter Component, which is in turn a fork by [Maciej Grajcarek](http://blog.uplevel.pl/index.php/2008/06/cakephp-12-filter-component/) which is ITSELF a fork from [Nik Chankov's](http://nik.chankov.net/2008/03/01/filtering-component-for-your-tables/).

That's a lot of forks...

This also contains a view helper made by [mcurry](http://github.com/mcurry/cakephp-filter-component).

This also uses a behavior adapted from work by [Brenton](http://bakery.cakephp.org/articles/view/habtm-searching) to allow for HasAndBelongsToMany and HasMany relationships.