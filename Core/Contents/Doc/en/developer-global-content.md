The `GlobalContent` model provides a base for building models with _content_ such as blogs, cms, producs and more. It makes it easy to do a number of things such as search, tag and SEO. The main advantages is that it has all the usual details such as `title`, `description`, `created`, `image`, `group_id`, `author_id` and elements that make building forms much easier.

Some examples of the `GlobalContent` model in action include the Blog and Cms plugins. Both of these make use of the model as they share a large amount of functionality such as `title`, `body`, `author` and more.

### Background

The Contents plugin has a behavior that is able to attach it self to models where this functionality is required. Once attached it will automatically create all the required relations and alter finds to include other details such as the `author` or `group` the content belongs to.

Besides setting a flag in a model and the optional use of the elemnts to make building forms easier, there are no other changes required. This makes it realy easy to build content related models.

As the `GlobalContent` model creates links to many different things it becomes easy to do a number of things, for example finding any content by a specific user or all the content that is for a specific group of users regardless of what type of content it is for.

### Examples of use

#### Blog

##### Contents plugin
- title
- slug (for SEO urls)
- body
- author\_id / alias
- editor\_id / alias
- group\_id (create posts for specific groups)
- image (main post image)
- category
- tags

##### Blog customisation

- Order content by date
- active (enable / disable posts)
- views (track view count)
- rating
- parent\_id (multi page posts)
- ordering (used for multi page posts)

> The main thing added here to make `blog` like content is how data is ordered. There is also the optional `multi page posts` which is done by adding the `parent\_id` field.

#### Cms

##### Contents plugin

- title
- slug (for SEO urls)
- body
- author\_id / alias
- editor\_id / alias
- group\_id (create posts for specific groups)
- image (main post image)
- category
- tags

##### Cms customisation

- start / end date
- active
- rating

> The main customisation for cms content is being able to configure pages to become active or disabled after a certain date.

Status and ratings are also going to be moved into the Contents plugin so that there is even less required to build other content models. Most fields are optional but do make development of content related stuff much easier.

### Usage

To get started using the `GlobalContent` model you will need to set a flag in your model so that when the Events are triggered the behavior is automatically attached which adds the required relations and functionality to your model.

	public $contentable = true;

You can also include the behavior using the normal `$actsAs` provided by CakePHP to attach this functionality.

Once the `GlobalContent` has been configured you would need to adjust your forms to include the new fields. This can be done using the `FormHelper` for more fine grained control of the form. Alternativly you can use the provided elements that provides easy access to most of the functionality.

#### Content

The content element provides the fields for general data such as `title` and `description`. You can disable some fields that are not required such as the `image` or `intro`.

For all the available fields you can use

	echo $this->element('Contents.content\_form');

Or exclude some from the form:

	echo $this->element('Contents.content\_form', array(
		'image' => false,
		'intro' => false
	));

The model that the `GlobalContent` relates to is normally taken from the default model that is currently loaded. For example if done in the Blog plugin through the `BlogPostsController` the model would be `Blog.BlogPost` as that is what the controller would load by default. If you wanted to specify something else you would pass it to the element:

	echo $this->element('Contents.content\_form', array(
		'model' => 'Example.Example'
	));

#### Meta

The `meta_form` element provides the fields to save basic metat data about the post. This includes things such as the `meta description`, `meta keywords` and `canonical` url which are all for SEO purposes.

	echo $this->element('Contents.meta\_form');

#### Author

The `author\_form` will help with storing who the creator and editor of the content was. This can be used for doing lookups by author or accountability. 

	echo $this->element('Contents.author\_form');

### Form example

You have a blog post model and want to make use of this functionality. Your form might look something like the following.

	echo $this->Form->create('BlogPost');
		echo $this->Form->input('id'); // BlogPost.id field
		echo $this->element('Contents.content\_form'); // all the content fields
		echo $this->Form->input('active'); // BlogPost.active field
	echo $this->Form->end();

Making use of the built in Infinitas `admin\_add()` and `admin\_edit()` controller methods, the content would all be saved correctly. If you are overriding thouse methods be sure to call `saveAll()` so that all the data can be saved.