<?php
$languageOptions = array('name' => true);
$languages = array(
	'england' => $this->Html->link($this->Contacts->flag('england') . $this->Html->tag('b', '', array('class' => 'caret')), $this->here . '#', array(
		'class' => 'dropdown-toggle',
		'role' => 'button',
		'data-toggle' => 'dropdown',
		'id' => 'language-select',
		'escape' => false
	)),
	'us' => $this->Html->tag('li', $this->Html->link($this->Contacts->flag('us', $languageOptions), '#', array(
		'escape' => false
	))),
	'jp' => $this->Html->tag('li', $this->Html->link($this->Contacts->flag('jp', $languageOptions), '#', array(
		'escape' => false
	)))
);

$currentLanguage = $languages['england'];
unset($languages['england']);

$languages = $this->Html->tag('ul', implode('', $languages), array(
	'class' => 'dropdown-menu',
	'aria-labelledby' => 'language-select'
));
echo $this->Html->tag('ul', implode('', array(
	$this->Html->tag('li', '', array('class' => 'divider-vertical')),
	$this->Html->tag('li', $currentLanguage . $languages, array(
		'class' => 'active'
	)),
	$this->Html->tag('li', '', array('class' => 'divider-vertical')),
)), array('class' => 'nav language-select'));