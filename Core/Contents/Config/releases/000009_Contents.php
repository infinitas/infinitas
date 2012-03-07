<?php
	/**
	 * Infinitas Releas
	 *
	 * Auto generated database update
	 */
	 
	class R4f562fb9a98c4d6fb6f83ad96318cd70 extends CakeRelease {

	/**
	* Migration description
	*
	* @var string
	* @access public
	*/
		public $description = 'Migration for Contents version 0.9';

	/**
	* Plugin name
	*
	* @var string
	* @access public
	*/
		public $plugin = 'Contents';

	/**
	* Actions to be performed
	*
	* @var array $migration
	* @access public
	*/
		public $migration = array(
			'up' => array(
				'create_table' => array(
					'global_categories' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
						'hide' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
						'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3, 'key' => 'index'),
						'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
						'parent_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
						'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
						'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'tree_list_all' => array('column' => array('lft', 'id', 'rght'), 'unique' => 1),
							'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0),
							'idx_access' => array('column' => 'group_id', 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'global_contents' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'introduction' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'body' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'image' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'dir' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'full_text_search' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'keyword_density' => array('type' => 'float', 'null' => false, 'default' => '0.000', 'length' => '5,3'),
						'global_category_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'meta_keywords' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'meta_description' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'group_id' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'layout_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'author_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'author_alias' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'editor_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'editor_alias' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'canonical_url' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'canonical_redirect' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'content' => array('column' => array('model', 'foreign_key'), 'unique' => 1),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'global_layouts' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'auto_load' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'css' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'html' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'php' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'content_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'global_tagged' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'foreign_key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'tag_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'language' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'UNIQUE_TAGGING' => array('column' => array('model', 'foreign_key', 'tag_id', 'language'), 'unique' => 1),
							'INDEX_TAGGED' => array('column' => 'model', 'unique' => 0),
							'INDEX_LANGUAGE' => array('column' => 'language', 'unique' => 0),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
					'global_tags' => array(
						'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'identifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'keyname' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
						'weight' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
						'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
						'indexes' => array(
							'PRIMARY' => array('column' => 'id', 'unique' => 1),
							'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1),
						),
						'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
					),
				),
			),
			'down' => array(
				'drop_table' => array(
					'global_categories', 'global_contents', 'global_layouts', 'global_tagged', 'global_tags'
				),
			),
		);
		
		public $fixtures = array(
			'contents' => array(
				'GlobalCategory' => array(
					array(
						'id' => '00000000-3394-4e47-0010-000000000000',
						'active' => 1,
						'hide' => 0,
						'group_id' => 1,
						'item_count' => 0,
						'parent_id' => null,
						'lft' => 1,
						'rght' => 6,
						'views' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0010-000000000001',
						'active' => 1,
						'hide' => 0,
						'group_id' => 1,
						'item_count' => 0,
						'parent_id' => '00000000-3394-4e47-0010-000000000000',
						'lft' => 2,
						'rght' => 3,
						'views' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0010-000000000002',
						'active' => 1,
						'hide' => 0,
						'group_id' => 1,
						'item_count' => 0,
						'parent_id' => '00000000-3394-4e47-0010-000000000000',
						'lft' => 4,
						'rght' => 5,
						'views' => 0,
					)
				),
				'GlobalContents' => array(
					array(
						'id' => '00000000-3394-4e47-0011-000000000000',
						'model' => 'Contents.GlobalCategory',
						'foreign_key' => '00000000-3394-4e47-0010-000000000000',
						'title' => 'Help',
						'slug' => 'help',
						'introduction' => 'Help using infinitas',
						'body' => 'Help using infinitas',
						'image' => null,
						'dir' => null,
						'full_text_search' => 'help using infinitas',
						'keyword_density' => 0,
						'global_category_id' => null,
						'meta_keywords' => 'infinitas',
						'meta_description' => 'infinitas',
						'group_id' => 1,
						'layout_id' => '00000000-3394-4e47-0012-000000000001',
						'author_id' => null,
						'author_alias' => 'Infinitas',
						'editor_id' => null,
						'editor_alias' => 'Infinitas',
						'canonical_url' => null,
						'canonical_redirect' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0011-000000000001',
						'model' => 'Contents.GlobalCategory',
						'foreign_key' => '00000000-3394-4e47-0010-000000000001',
						'title' => 'Getting Started',
						'slug' => 'getting-started',
						'introduction' => 'Getting started with infinitas',
						'body' => 'Getting started with infinitas',
						'image' => null,
						'dir' => null,
						'full_text_search' => 'getting started with infinitas',
						'keyword_density' => 0,
						'global_category_id' => null,
						'meta_keywords' => 'infinitas',
						'meta_description' => 'infinitas',
						'group_id' => 1,
						'layout_id' => '00000000-3394-4e47-0012-000000000001',
						'author_id' => null,
						'author_alias' => 'Infinitas',
						'editor_id' => null,
						'editor_alias' => 'Infinitas',
						'canonical_url' => null,
						'canonical_redirect' => 1,
					),
					array(
						'id' => '00000000-3394-4e47-0011-000000000002',
						'model' => 'Contents.GlobalCategory',
						'foreign_key' => '00000000-3394-4e47-0010-000000000002',
						'title' => 'Configuration',
						'slug' => 'configuration',
						'introduction' => 'Configuring infinitas',
						'body' => 'Configuring infinitas',
						'image' => null,
						'dir' => null,
						'full_text_search' => 'configuring infinitas',
						'keyword_density' => 0,
						'global_category_id' => null,
						'meta_keywords' => 'infinitas',
						'meta_description' => 'infinitas',
						'group_id' => 1,
						'layout_id' => '00000000-3394-4e47-0012-000000000001',
						'author_id' => null,
						'author_alias' => 'Infinitas',
						'editor_id' => null,
						'editor_alias' => 'Infinitas',
						'canonical_url' => null,
						'canonical_redirect' => 1,
					)
				),
				'GlobalLayout' => array(
					array(
						'id' => '00000000-3394-4e47-0012-000000000000',
						'name' => 'Category Index',
						'model' => 'Contents.GlobalCateogry',
						'auto_load' => 'index',
						'css' => '',
						'html' => "<div id=\"main-content-full\">\r\n	<h1>Articles</h1>\r\n	<div class=\"main-ruler\"></div>\r\n	<p>Below is a list of the categories that content has been filed under.  Click a category to see content within it.</p>\r\n	<!--<ul class=\"button-list\">\r\n		<li><a href=\"#\" id=\"button-current\">All</a></li>\r\n		<li><a href=\"#\">Websites</a></li>\r\n		<li><a href=\"#\">Branding</a></li>\r\n		<li><a href=\"#\">Illustration</a></li>\r\n		<li><a href=\"#\">Photography</a></li>\r\n	</ul>-->\r\n	<div class=\"clear\"></div>\r\n	<div class=\"section narrow\">\r\n		{{#categories}}\r\n			{{%UNESCAPED}}{{%DOT-NOTATION}}\r\n			<div class=\"three-col\">\r\n				<h3><a href=\"{{GlobalCategory.url}}\">{{GlobalCategory.title}}</a></h3>\r\n				<a href=\"{{GlobalCategory.url}}\" title=\"{{GlobalCategory.title}}\">\r\n					<img class=\"img-box-large feature-img\" alt=\"{{GlobalCategory.title}}\" src=\"{{GlobalCategory.content_image_path_medium}}\" width=\"276px\" height=\"116px\">\r\n				</a>\r\n				<p class=\"no-pad\">{{GlobalCategory.body}}</p>\r\n				<a href=\"{{GlobalCategory.url}}\" class=\"arrow-link\">View Category</a>\r\n			</div>\r\n			<div class=\"ruler\"></div>\r\n		{{/categories}}\r\n	</div>\r\n	<div class=\"clear\"></div>\r\n	{{paginationNavigation}}\r\n</div>",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000001',
						'name' => 'Category View',
						'model' => 'Contents.GlobalCateogry',
						'auto_load' => null,
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}} \r\n<div id=\"post-list\">\r\n	<h1>{{category.GlobalCategory.title}}</h1>\r\n	<div class=\"post-meta\">\r\n		<ul>\r\n			<li class=\"meta-date ie6fix\">{{category.GlobalCategory.created}}</li>\r\n			<li class=\"meta-author ie6fix\">{{category.GlobalCategory.author}}</li>\r\n			<li class=\"meta-items ie6fix\">{{category.GlobalCategory.item_count}}</li>\r\n		</ul>\r\n		<div class=\"clear\"></div>				\r\n	</div>\r\n	{{category.GlobalCategory.body}}\r\n	<div class=\"main-ruler\"></div>\r\n</div>",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000002',
						'name' => 'Blog Index',
						'model' => 'Blog.BlogPost',
						'auto_load' => 'index',
						'css' => '',
						'html' => "{{%UNESCAPED}}\r\n{{#posts}}\r\n	{{%DOT-NOTATION}}\r\n	<div class=\"post-list\">\r\n		<h1>{{BlogPost.title_link}}</h1>\r\n		<div class=\"post-meta\">\r\n			<ul>\r\n				<li class=\"meta-date ie6fix\">{{BlogPost.created}}</li>\r\n				<li class=\"meta-author ie6fix\">{{BlogPost.author_link}}</li>\r\n				<li class=\"meta-tag ie6fix\">{{BlogPost.module_tags_list}}</li>\r\n				<li class=\"meta-comments ie6fix\"><a href=\"{{BlogPost.url}}#comments-top\">{{BlogPost.module_comment_count}}</a></li>\r\n			</ul>\r\n			<div class=\"clear\"></div>\r\n		</div>\r\n		<img class=\"img-left\" alt=\"\" src=\"/bitcore/img/post-featured-140x140.jpg\">\r\n		<p>{{BlogPost.body}}</p>\r\n		<a class=\"arrow-link\" href=\"{{BlogPost.url}}\">{{infinitasJsData.config.Website.read_more}}</a>\r\n		<div class=\"clear\"></div>\r\n		<div class=\"main-ruler\"></div>\r\n	</div>\r\n{{/posts}}\r\n\r\n{{^posts}}\r\n<div>\r\n	No content found\r\n</div>\r\n{{/posts}}\r\n\r\n{{paginationNavigation}}",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000003',
						'name' => 'Blog View',
						'model' => 'Blog.BlogPost',
						'auto_load' => null,
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}} \r\n<div id=\"post\">[snip:Blog.BlogPost#seo-foo-bar]\r\n	<h1>{{post.BlogPost.title}}</h1>\r\n	<div class=\"post-meta\">\r\n		<ul>\r\n			<li class=\"meta-date ie6fix\">{{post.BlogPost.created}}</li>\r\n			<li class=\"meta-author ie6fix\">{{post.BlogPost.author_link}}</li>\r\n			<li class=\"meta-tag ie6fix\">{{post.BlogPost.module_tags_list}}</li>\r\n			<li class=\"meta-comments ie6fix\">{{post.BlogPost.module_comment_count}}</li>\r\n		</ul>\r\n		<div class=\"clear\"></div>				\r\n	</div>\r\n	{{post.BlogPost.body}}\r\n	<div class=\"main-ruler\"></div>\r\n	{{post.BlogPost.author_bio}}\r\n	<div class=\"afterEvent\">{{post.Post.events_after}}</div>\r\n	{{post.BlogPost.module_comments}}\r\n</div>",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000004',
						'name' => 'Cms Index',
						'model' => 'Cms.CmsContent',
						'auto_load' => 'index',
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}}\r\n<div id=\"post-list\">\r\n	<h1>{content.CmsContent.title}</h1>\r\n	<div class=\"post-meta\">\r\n		<ul>\r\n			<li class=\"meta-date ie6fix\">{content.CmsContent.created}</li>\r\n			<li class=\"meta-author ie6fix\">{content.CmsContent.author}</li>\r\n			<li class=\"meta-tag ie6fix\">{content.CmsContent.tags}</li>\r\n			<li class=\"meta-comments ie6fix\">{content.CmsContent.comment_count}</li>\r\n		</ul>\r\n		<div class=\"clear\"></div>				\r\n	</div>\r\n	{content.CmsContent.body}\r\n	<div class=\"main-ruler\"></div>\r\n</div>",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000005',
						'name' => 'Cms View',
						'model' => 'Cms.CmsContent',
						'auto_load' => null,
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}}\r\n<div id=\"post\">\r\n	<h1>{{content.CmsContent.title}}</h1>\r\n	<div class=\"post-meta\">\r\n		<ul>\r\n			<li class=\"meta-date ie6fix\">{{content.CmsContent.created}}</li>\r\n			<li class=\"meta-author ie6fix\">{{content.CmsContent.author}}</li>\r\n			<li class=\"meta-tag ie6fix\">{{content.CmsContent.tags}}</li>\r\n			<li class=\"meta-comments ie6fix\">{{content.CmsContent.comment_count}}</li>\r\n		</ul>\r\n		<div class=\"clear\"></div>				\r\n	</div>\r\n	{{content.CmsContent.body}}\r\n	<div class=\"main-ruler\"></div>\r\n</div>",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000006',
						'name' => 'Gallery Slider',
						'model' => 'Gallery.Image',
						'auto_load' => null,
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}}\r\n<div id=\"stage-wrap\">\r\n	<div id=\"stage\">\r\n		<div class=\"anythingSlider\">\r\n			<div class=\"wrapper\">\r\n				<ul>\r\n					{{#galleryImages}}\r\n						<li>\r\n							{{#Image.even}}\r\n								<div class=\"slide-feature round-all fr\">{{Image.full_image}}</div>\r\n								<div class=\"slide-content fl\">\r\n									<h2>{{Image.title}}</h2>\r\n									{{Image.body}}\r\n								</div>\r\n							{{/Image.even}}\r\n							{{^Image.even}}\r\n								<div class=\"slide-feature round-all fl\">{{Image.full_image}}</div>\r\n								<div class=\"slide-content fr\">\r\n									<h2>{{Image.title}}</h2>\r\n									{{Image.body}}\r\n								</div>\r\n							{{/Image.even}}\r\n						</li>\r\n					{{/galleryImages}}\r\n				</ul>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000007',
						'name' => 'Portfolio Index',
						'model' => 'Portfolios.Portfolio',
						'auto_load' => 'index',
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}}\r\n",
						'php' => '',
						'content_count' => 0,
					),
					array(
						'id' => '00000000-3394-4e47-0012-000000000008',
						'name' => 'Portfolio View',
						'model' => 'Portfolios.Portfolio',
						'auto_load' => null,
						'css' => '',
						'html' => "{{%UNESCAPED}}{{%DOT-NOTATION}}\r\n",
						'php' => '',
						'content_count' => 0,
					)
				)
			),
		);

	
	/**
	* Before migration callback
	*
	* @param string $direction, up or down direction of migration process
	* @return boolean Should process continue
	* @access public
	*/
		public function before($direction) {
			return true;
		}

	/**
	* After migration callback
	*
	* @param string $direction, up or down direction of migration process
	* @return boolean Should process continue
	* @access public
	*/
		public function after($direction) {
			return true;
		}
	}