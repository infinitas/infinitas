Snips are blocks of markup that can be rendered from simple text expressions similar to markdown. These small bits of text allow you to insert dynamic bits of code into virtually any part of the application without knowing a thing about PHP.

#### Loading Snips

To load a snip of code you would use `(snip:PluginName)` or `(snip:PluginName#param)`. This requires that there is a snip to load so you should consult the documentation for the specific plug-in as the details may vary slightly.

> Snips are loaded using sqaure brackets `[` and `]`, the examples shown use `(` and `)` so that they are not rendered.

#### Loading Modules

Loading a module from your content is virtually the same as loading snips. A basic example would be `(module:PluginName.module\_name)`.

#### Loading Module Positions

Once again loading an entire module position which will load all the Modules for that position based on the criteria above is quite simple: `(module:Modules.loader#position\_name)`
It is important to note that we are loading something specific here, the `loader` module from the `Modules` plug-in, also note that the param is required (the name of the position to load). This should be a valid position that you can find in the admin back-end

### Snips Example

You are building a Newsletter for your customers and would like to include the details of the latest products in your Shop. Instead of building the Newsletter with hard coded HTML you can use snips. When the snips render a module, the code could then look up products that are new and dynamically insert the markup into the Newsletter as it is sent. This means that you could virtually set up the template for the email once and it will always be upto date.

The example below added to a email would render what ever the `latest\_products` module in the Shop plugin has to offer.

	(module:Shop.latest\_products)

Snips can also be used with `Mustache` markup to make things even more dynamic. When content is rendered, `Mustache templates` are rendered **first** and then snips are parsed. This means that from the example above we can customise the customers email even more.

	(module:Shop.latest\_products#{User.id})

This example would first render the mustache template which would change the snip to the following:

	(module:Shop.latest\_products#--the-users-id--)

Now the `latest\_products` module is actually being pased an id which can be used to look up the users previous purchases and products that have been viewed (using the ViewCounter plugin) to determin the best products to be inclued in the email.