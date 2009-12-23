<?php
    /**
     * Geshi Helper
     *
     * Implements geshi syntax highlighting for cakephp
     * Originally based off of http://www.gignus.com/code/code.phps
     *
     * @author Mark story
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * Copyright 2008 Mark Story
     * 823 millwood rd. #3
     * toronto, ontario
     * M4G 1W3, Canada
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     */
    App::import('Vendor', 'Geshi', array( 'file' => 'geshi'.DS.'geshi.php' ) );

    class GeshiHelper extends AppHelper {
        /**
         * The Container Elements that could contain highlightable code
         *
         * @var array
         **/
        public $validContainers = array('pre');

        /**
         * Replace containers with divs to increase validation
         *
         * @var string
         */
        public $containerMap = array('pre' => array('div class="code"', 'div'));

        /**
         * The languages you want to highlight.
         *
         * @var array
         **/
        public $validLanguages = array('css', 'html', 'php', 'javascript', 'python', 'sql');

        /**
         * Default language to use if no valid language is found.  leave null to require a language attribute
         * to be set on each container.
         *
         * @var mixed  false for no default language, String for the default language
         **/
        public $defaultLanguage = false;

        /**
         * The Attribute use for finding the code Language.
         *
         * Common choices are lang and class
         *
         * @var string
         **/
        public $langAttribute = 'lang';

        /**
         * GeSHi Instance
         *
         * @var object
         **/
        protected $_geshi = null;

        /**
         * Show the Button that can be used with JS to switch to plain text.
         *
         * @var bool
         */
        public $showPlainTextButton = true;

        function highlight( $htmlString ) {
            $tags = implode('|', $this->validContainers);
            //yummy regex
            $pattern = '#(<('. $tags .')[^>]'.$this->langAttribute.'=["\']+([^\'".]*)["\']+>)(.*?)(</\2\s*>|$)#s';
            /*
               matches[0] = whole string
               matches[1] = open tag including lang attribute
               matches[2] = tag name
               matches[3] = value of lang attribute
               matches[4] = text to be highlighted
               matches[5] = end tag
            */
            $html = preg_replace_callback($pattern, array($this, '_processCodeBlock'), $htmlString);
            return $this->output( $html );
        }

        /**
         * Preg Replace Callback
         * Uses matches made earlier runs geshi returns processed code blocks.
         *
         * @return string Completed replacement string
         **/
        protected function _processCodeBlock($matches) {
            list($block, $openTag, $tagName, $lang, $code, $closeTag) = $matches;
            unset($matches);
            //check language
            $lang = $this->validLang($lang);
            $code = html_entity_decode($code, ENT_QUOTES); //decode text in code block as GeSHi will re-encode it.

            if (isset($this->containerMap[$tagName])) {
                $patt = '/' . preg_quote($tagName) . '/';
                $openTag = preg_replace($patt, $this->containerMap[$tagName][0], $openTag);
                $closeTag = preg_replace($patt, $this->containerMap[$tagName][1], $closeTag);
            }

            if ($this->showPlainTextButton) {
                $button = '<a href="#null" class="geshi-plain-text">Show Plain Text</a>';
                $openTag = $button . $openTag;
            }

            if ((bool)$lang) {
                //get instance or use stored instance
                if ($this->_geshi == null) {
                    $geshi = new GeSHI(trim($code), $lang);
                    $this->__configureInstance($geshi);
                    $this->_geshi = $geshi;
                } else {
                    $this->_geshi->set_source(trim($code));
                    $this->_geshi->set_language($lang);
                }
                $highlighted = $this->_geshi->parse_code();
                return $openTag . $highlighted . $closeTag;
            }
            return $openTag . $code . $closeTag;
        }
        /**
         * Check if the current language is a valid language.
         *
         * @return mixed.
         **/
        protected function validLang( $lang )  {
            if (in_array($lang, $this->validLanguages)) {
                return $lang;
            }
            if ($this->defaultLanguage) {
                return $this->defaultLanguage;
            }
            return false;
        }

        /**
         * Configure a geshi Instance the way we want it.
         * app/config/geshi.php
         *
         * @return void
         **/
        private function __configureInstance($geshi) {
            if (file_exists( CONFIGS . 'geshi.php')) {
                include CONFIGS .'geshi.php';
            }
        }
    } // END class geshiHelper extends AppHelper
?>