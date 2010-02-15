<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    class WysiwygHelper extends AppHelper
    {
        var $helpers = array(
            'Form',
            'Javascript'
        );

	    function ckEditor($id = null, $config = array()){
	    	$default = array(
			    'baseFloatZIndex' => '',
			    'baseHref' => '',
			    'blockedKeystrokes' => '',
			    'bodyClass' => '',
			    'bodyId' => '',
			    'browserContextMenuOnCtrl' => '',
			    'colorButton_backStyle' => '',
			    'colorButton_colors' => '',
			    'colorButton_enableMore' => '',
			    'colorButton_foreStyle' => '',
			    'contentsCss' => '',
			    'contentsLangDirection' => '',
			    'corePlugins' => '',
			    'customConfig' => '',
			    'defaultLanguage' => '',
			    'dialog_backgroundCoverColor' => '',
			    'dialog_backgroundCoverOpacity' => '',
			    'dialog_magnetDistance' => '',
			    'disableNativeSpellChecker' => '',
			    'disableNativeTableHandles' => '',
			    'disableObjectResizing' => '',
			    'docType' => '',
			    'editingBlock' => '',
			    'emailProtection' => '',
			    'enterMode' => '',
			    'entities' => '',
			    'entities_additional' => '',
			    'entities_greek' => '',
			    'entities_latin' => '',
			    'entities_processNumerical' => '',
			    'extraPlugins' => '',
			    'filebrowserBrowseUrl' => '',
			    'filebrowserFlashBrowseUrl' => '',
			    'filebrowserFlashUploadUrl' => '',
			    'filebrowserImageBrowseLinkUrl' => '',
			    'filebrowserImageBrowseUrl' => '',
			    'filebrowserImageUploadUrl' => '',
			    'filebrowserUploadUrl' => '',
			    'find_highlight' => '',
			    'font_defaultLabel' => '',
			    'font_names' => '',
			    'font_style' => '',
			    'fontSize_defaultLabel' => '',
			    'fontSize_sizes' => '',
			    'fontSize_style' => '',
			    'forcePasteAsPlainText' => '',
			    'forceSimpleAmpersand' => '',
			    'format_address' => '',
			    'format_div' => '',
			    'format_h1' => '',
			    'format_h2' => '',
			    'format_h3' => '',
			    'format_h4' => '',
			    'format_h5' => '',
			    'format_h6' => '',
			    'format_p' => '',
			    'format_pre' => '',
			    'format_tags' => '',
			    'fullPage' => '',
			    'height' => '',
			    'htmlEncodeOutput' => '',
			    'ignoreEmptyParagraph' => '',
			    'image_removeLinkByEmptyURL' => '',
			    'keystrokes' => '',
			    'language' => '',
			    'menu_groups' => '',
			    'menu_subMenuDelay' => '',
			    'newpage_html' => '',
			    'pasteFromWordCleanupFile' => '',
			    'pasteFromWordNumberedHeadingToList' => '',
			    'pasteFromWordPromptCleanup' => '',
			    'pasteFromWordRemoveFontStyles' => '',
			    'pasteFromWordRemoveStyles' => '',
			    'plugins' => '',
			    'protectedSource' => '',
			    'removeFormatAttributes' => '',
			    'removeFormatTags' => '',
			    'removePlugins' => '',
			    'resize_enabled' => '',
			    'resize_maxHeight' => '',
			    'resize_maxWidth' => '',
			    'resize_minHeight' => '',
			    'resize_minWidth' => '',
			    'shiftEnterMode' => '',
			    'skin' => '',
			    'smiley_descriptions' => '',
			    'smiley_images' => '',
			    'smiley_path' => '',
			    'startupFocus' => '',
			    'startupMode' => '',
			    'startupOutlineBlocks' => '',
			    'stylesCombo_stylesSet' => '',
			    'tabIndex' => '',
			    'tabSpaces' => '',
			    'templates' => '',
			    'templates_files' => '',
			    'templates_replaceContent' => '',
			    'theme' => '',
			    'toolbar' => 'Full',
			    'toolbar_Basic' => '',
			    'toolbar_Full' => '',
			    'toolbarCanCollapse' => '',
			    'toolbarLocation' => '',
			    'toolbarStartupExpanded' => '',
			    'undoStackSize' => '',
			    'width' => ''
			);

	    	$config = array_merge($default,(array)$config);
			$did = '';
			foreach (explode('.', $id) as $v) {
				$did .= ucfirst($v);
			}


			$code = 'CKEDITOR.replace( \''.$did.'\', { toolbar : \''.$config['toolbar'].'\' } );';
			return $this->input($id).$this->Javascript->codeBlock($code);
	    }

        function text( $id = null )
        {
            return $this->input( $id );
        }

        function input( $id )
        {
            return $this->Form->input( $id, array( 'style' => 'width:100%; height:500px;' ) );
        }
    }
?>