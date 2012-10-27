Infinitas uses a simple yet powerful [templating language](http://en.wikipedia.org/wiki/Web_template_system) called [mustache](http://mustache.github.com/), which allows designers with limited knowledge of PHP to work with dynamic data from the database in Infinitas. There is nothing currently special available in terms of the mustache templating over and above the standard functionality provided by mustache. All template layouts and views will automatically be parsed for the markup and mustache will replace with the correct data.

Mustache was chosen for Infinitas due to the fact that it is fairly simple to do basic coding such as displaying variables within html, and even looping through record sets. Another great advantage to mustache is the fact that there are many libraries for all sorts of languages already implementing mustache, one of which is JavaScript.

An example of the mustache templating language can be seen below:

	{#SomeData}
	  <h1>{name}</h1>
	  <p>{description}</p>
	{/SomeData}

> This example uses single `{` and `}`, as if done with two would actually render the mustache template

For much more in-depth information on the mustache templating you can view the [docs](http://mustache.github.com/mustache.5.html) or play with the [demo](http://mustache.github.com/#demo). For more information on what variables are available in each section of the site, view or template please see the API and specific documentation for those sections.