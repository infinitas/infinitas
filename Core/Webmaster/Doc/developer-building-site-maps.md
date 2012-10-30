To include your plugins data in the sitemap generation you will need to make use of the Events system. When an administrator requests a sitemap to be built the code will trigger an event to all plugins to return any data that should be included in the sitemap.

There are a number of methods that aid in getting the data required for building the sitemap. These include methods for getting the frequency for change which is one of the fields found in a sitemap.

### Basic sitemap

A general sitemap is rendered in xml and contains a number of fields that help search engine spiders crawl and index the site correcly. Instead of having to page through evey link in your site, spiders are able to use the sitemap to find which pages are available and how they should be indexed.

A basic sitemap would look something like the following:

	<?xml version="1.0" encoding="UTF-8"?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	  <url>
	    <loc>http://www.example.com/?id=who</loc>
	    <lastmod>2009-09-22</lastmod>
	    <changefreq>monthly</changefreq>
	    <priority>0.8</priority>
	  </url>
	  <url>
	    <loc>http://www.example.com/?id=what</loc>
	    <lastmod>2009-09-22</lastmod>
	    <changefreq>monthly</changefreq>
	    <priority>0.5</priority>
	  </url>
	  <url>
	    <loc>http://www.example.com/?id=how</loc>
	    <lastmod>2009-09-22</lastmod>
	    <changefreq>monthly</changefreq>
	    <priority>0.5</priority>
	  </url>
	</urlset>

### What is a sitemap

The event triggered when a sitemap is being built is the `siteMapRebuild` event. There are some basic requirements for building a sitemap that should be included in the return data. The basic format of the sitemap data should be a nested array with the following `key` => `value` pairs.

- last\_modified: `timestamp` The date the record was last modified
- change\_frequency: `float` How often the data changes on a scale of `0` to `1`
- priority: `integer` How important the record is
- url: `string` the url where the record can be accessed

Once the Webmaster plugin has gathered the data from all the plugins to be processed, it will generate and write a file to disk. This will be written to `APP . 'webroot' . DS . 'sitemap.xml'` so that it can be accessed by spiders at your website address `http://example.com/sitemap.xml`.

The file is written to disk as the size of these files can become rather large. They can also take a relativly long time to generate depending on the amount of data being processed. Having them written to disk and served as a static XML file makes a lot less work for the server.

### Basic example

The basic principal is to get a list of all the records (or the latest records if there is too many) and return the data so the sitemap can be built. The example below is for the Contacts plugin.

	public function onSiteMapRebuild(Event $Event) {
		$Contact = ClassRegistry::init('Contact.Contact');
		$newest = $Contact->ContactBranch->getNewestRow();
		$frequency = $Contact->getChangeFrequency();
		$branches = $Contact->ContactBranch->find('list', array(
			'fields' => array(
				'ContactBranch.slug',
				'ContactBranch.slug',
			)
		));

		$return = array();
		$return[] = array(
			'url' => Router::url(array(
				'plugin' => 'contact', 
				'controller' => 'branches', 
				'action' => 'index', 
				'admin' => false, 
				'prefix' => false
			), true),
			'last\_modified' => $newest,
			'change\_frequency' => $frequency
		);

		foreach($branches as $branch) {
			$return[] = array(
				'url' => InfinitasRouter::url(array(
					'plugin' => 'contact',
					'controller' => 'branches',
					'action' => 'view',
					'slug' => $branch,
					'admin' => false,
					'prefix' => false
				)),
				'last\_modified' => $newest,
				'change\_frequency' => $frequency
			);
		}

		return $return;
	}

Once the data is returned to the Webmaster plugin, it can process the rows along with any other plugins that have returned data. The file will be saved and the new site map will be available for searchengines to process.
