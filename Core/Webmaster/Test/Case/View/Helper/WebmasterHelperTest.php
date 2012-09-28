<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('WebmasterHelper', 'Webmaster.View/Helper');

/**
 * WebmasterHelper Test Case
 *
 */
class WebmasterHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Webmaster = new WebmasterHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Webmaster);

		parent::tearDown();
	}

/**
 * testSeoMetaTags method
 *
 * @return void
 */
	public function testSeoMetaTags() {
	}

/**
 * testMetaRobotTag method
 *
 * @return void
 */
	public function testMetaRobotTag() {
	}

/**
 * testMetaDescription method
 *
 * @return void
 */
	public function testMetaDescription() {
	}

/**
 * testMetaKeywords method
 *
 * @return void
 */
	public function testMetaKeywords() {
	}

/**
 * testMetaCanonicalUrl method
 *
 * @return void
 */
	public function testMetaCanonicalUrl() {
	}

/**
 * testMetaAuthor method
 *
 * @return void
 */
	public function testMetaAuthor() {
	}

/**
 * testMetaGenerator method
 *
 * @return void
 */
	public function testMetaGenerator() {
	}

/**
 * testMetaIcon method
 *
 * @return void
 */
	public function testMetaIcon() {
	}

/**
 * testMetaCharset method
 *
 * @return void
 */
	public function testMetaCharset() {
	}

/**
 * testMetaGoogleVerification method
 *
 * @return void
 */
	public function testMetaGoogleVerification() {
	}

/**
 * testMetaTitle method
 *
 * @return void
 */
	public function testMetaTitle() {
	}

/**
 * testMetaRss method
 *
 * @return void
 */
	public function testMetaRss() {
	}

}
