<?php
	/* ContentFrontpage Test cases generated on: 2009-12-13 19:12:29 : 1260726929*/
	App::import('Model', 'Cms.Frontpage');

	class FrontpageTestCase extends CakeTestCase
	{
		public $fixtures = array(
			'plugin.cms.content',
			'plugin.cms.frontpage',
	    );

	    public function startTest(){
	        $this->Frontpage = &ClassRegistry::init('Cms.Frontpage');
	    }

		public function testSomething(){
			$this->assert(true, true);
		}

	    public function endTest(){
	        unset( $this->Frontpage );
	        ClassRegistry::flush();
	    }
	}