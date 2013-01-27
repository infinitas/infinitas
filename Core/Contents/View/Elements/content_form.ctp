<?php
/**
 * Element for building forms used in conjunction with the contents plugin
 *
 * This can be used for quickly building your content for, or it can be done manually to customise
 * the output
 *
 * Options:
 *	- model: the model the data belongs to (default is to use the one auto loaded by the controller)
 *	- image: Image field added unless `image` is passed as false
 *	- intro: set to true for an introduction input
 *
 * @package Infinitas.Contents.View
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
if (empty($model)) {
	$model = implode('.', $this->request->models[current(array_keys($this->request->models))]);
} else if (!strstr($model, '.')) {
	$model = ClassRegistry::init($model)->plugin . '.' . ClassRegistry::init($model)->alias;
}
list($p, $m) = pluginSplit($model);

echo $this->Form->input('GlobalContent.id');
echo $this->Form->hidden('GlobalContent.model', array(
	'value' => $model
));

$class = 'span6';
if (!isset($image) || $image !== false) {
	$class = 'span4';
	$image = $this->element('Filemanager.file_upload', array(
		'fieldName' => 'GlobalContent.image',
		'inputOptions' => array(
			'label' => __d('contents', 'Content Image')
		)
	));
	$thumb = null;
	if (!empty($this->request->data[$m])) {
		$thumb = $this->Html->link(
			$this->Html->image($this->request->data[$m]['content_image_path_thumb']),
			$this->request->data[$m]['content_image_path_full'],
			array(
				'escape' => false,
				'class' => 'thickbox'
			)
		);
	}
	$image = $this->Html->tag('div', $image, array(
		'class' => 'span3'
	));
	$image .= $this->Html->tag('div', $thumb, array(
		'class' => 'span1'
	));
}
$fields = $this->Html->tag('div', implode('', array(
	$this->Form->input('GlobalContent.title', array(
		'div' => $class
	)),
	$this->Form->input('GlobalContent.slug', array(
		'label' => __d('contents', 'Alias'),
		'div' => $class
	)),
	$image
)), array('class' => 'row-fluid'));

$tags = Hash::extract($contentTags, '{n}.GlobalTag.name');
$currentTags = (array)Hash::extract($this->request->data, 'GlobalTagged.{n}.GlobalTag.name');
$tagAvailabel = array();
foreach ($tags as &$tag) {
	if (in_array($tag, $currentTags)) {
		continue;
	}
	$tagAvailabel[] = $this->Design->label($tag);
}

$fields .= $this->Html->tag('div', implode('', array(
	$this->Form->input('GlobalContent.layout_id', array(
		'options' => $contentLayouts,
		'empty' => Configure::read('Website.empty_select'),
		'class' => 'smaller',
		'div' => 'span6'
	)),
	$this->Form->input('GlobalContent.group_id', array(
		'options' => $contentGroups,
		'label' => __d('contents', 'Min Group'),
		'empty' => __d('contents', 'Public'),
		'div' => 'span6'
	))
)), array('class' => 'row-fluid')) .
$this->Html->tag('div', implode('', array(
	$this->Html->tag('div', implode('', array(
		$this->Form->input('GlobalContent.tags', array(
			'value' => implode(',', $currentTags),
			'data-tags' => htmlspecialchars(implode(',', $tags)),
			'label' => __d('contents', 'Tags')
		)),
		$this->element('Contents.category_list')
	)), array('class' => 'span5')),
	$this->Html->tag('div', $this->Form->label(__d('contents', 'Existing tags')) . implode('', $tagAvailabel), array(
		'class' => 'tags span7'
	))
)), array('class' => 'row-fluid'));

if (!isset($intro) || $intro !== false) {
	$fields .= $this->Infinitas->wysiwyg('GlobalContent.introduction');
}
$fields .= $this->Infinitas->wysiwyg('GlobalContent.body');

$template = '%s';
if (!empty($metaFieldSet) && $metaFieldSet === true) {
	$template = sprintf(
		'<fieldset><h1>%s</h1>%%s</fieldset>',
		__d('contents', 'Content')
	);
}

echo sprintf($template, $fields);

if (!empty($relatedContent)) {
	foreach ($relatedContent as &$related) {
		$related['url']['admin'] = false;
		foreach ($related['tags'] as &$tag) {
			$tag = $this->Html->link($tag, '#', array(
				'class' => 'tag'
			));
		}
		$related = $this->Html->tag('li', $related['text'] . implode('', $related['tags']), array(
			'data-pk' => $related['pk'],
			'data-text' => $related['text'],
			'data-url' => InfinitasRouter::url($related['url'], false)
		));
	}
	echo $this->Html->tag('div', $this->Html->tag('ul', implode('', $relatedContent), array(
		'class' => 'related-content records',
	)), array('class' => 'hide'));
}

if (!empty($tags)) {
	foreach ($tags as &$tag) {
		$url = current($this->Event->trigger($p . '.slugUrl', array(
			'type' => 'tag',
			'data' => array(
				'tag' => strtolower(str_replace(' ', '', $tag))
			)
		)));
		$url[$p]['admin'] = false;
		$tag = $this->Html->tag('li', $tag, array(
			'data-pk' => 'tag-' . $tag,
			'data-text' => $tag,
			'data-url' => InfinitasRouter::url($url[$p], false)
		));
	}
	echo $this->Html->tag('div', $this->Html->tag('ul', implode('', $tags), array(
		'class' => 'related-content tags',
	)), array('class' => 'hide'));
}
