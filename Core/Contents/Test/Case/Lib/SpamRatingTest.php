<?php
App::uses('SpamRating', 'Contents.Lib');

class SpamRatingTest extends CakeTestCase {

/**
 * @brief set up at the start
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('Comments.InfinitasComment');
		$this->Lib = new SpamRating(array(
			// 'email' => 'email', NOTE: this is assumed so should work when commented out.
			'content' => 'comment',
			'url_text' => array('bad-word'),
			'blacklist' => array('very-bad', 'also-bad'),
			'model' => $this->Model,
		));
	}

/**
 * @brief break down at the end
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Lib, $this->Model);
	}

	public function testConfig() {
		$this->Lib->config(array(
			// 'email' => 'email', NOTE: this is assumed so should work when commented out.
			'content' => 'comment',
		));
	}

/**
 * test rate mx record
 * 
 * @dataProvider rateMxRecordDataProvider
 */
	public function testRateMxRecord($data, $expected) {
		$result = $this->Lib->rateMxRecord(array(
			'email' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate mx record data provider
 *
 * @return array
 */
	public function rateMxRecordDataProvider() {
		return array(
			'good' => array(
				'foo@gmail.com',
				1
			),
			'made-up' => array(
				'foo@zzzzzxxxxxxxxxxxxxxyyyyyyyyyyyy.com',
				-10
			)
		);
	}

/**
 * test rate links
 * 
 * @dataProvider rateLinksDataProvider
 */
	public function testRateLinks($data, $expected) {
		$result = $this->Lib->rateLinks(array(
			'comment' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate mx record data provider
 *
 * @return array
 */
	public function rateLinksDataProvider() {
		return array(
			'no links' => array(
				'foo bar has no links \o/',
				3
			),
			'1 short link' => array(
				'foo bar <a href="http://foo.com">test</a> baz buz',
				2
			),
			'1 long link' => array(
				'foo bar <a href="http://foo.com/something/long?here=good">test</a> baz buz',
				1
			),
			'2 link' => array(
				'foo bar <a href="http://foo.com">test</a> baz buz foo bar <a href="http://google.com">test</a> baz buz',
				1
			),
			'3 link' => array(
				'foo bar <a href="http://google.com">test</a> baz buz foo bar <a href="http://foo.com">test</a> baz buz foo bar <a href="http://foo.com">test</a> baz buz',
				-3
			),
			'3 long links' => array(
				'foo bar <a href="http://google.com/something/that/is/spammy">test</a> baz buz foo bar <a href="http://foo.com?foo=bar&baz=fuzz">test</a> baz buz foo bar <a href="http://foo.com/bar/fiz/baz?spam">test</a> baz buz',
				-6
			),
			'1 link blacklist word' => array(
				'foo bar <a href="http://bad-word.com/foobar">test</a> baz buz',
				1
			),
			'2 link blacklist word' => array(
				'foo bar <a href="http://word.com/bad-word">test</a> baz buz foo bar <a href="http://bad-word.com/foobar">test</a> baz buz',
				-1
			),
			'1 link blacklist keyword' => array(
				'foo bar <a href="http://very-bad.com/foobar">test</a> baz buz',
				1
			),
			'2 link blacklist keyword' => array(
				'foo bar <a href="http://word.com/very-bad">test</a> baz buz foo bar <a href="http://bad-word.com/also-bad">test</a> baz buz',
				-2
			),
		);
	}

/**
 * test rate length
 * 
 * @dataProvider rateLengthDataProvider
 */
	public function testRateLength($data, $expected) {
		$result = $this->Lib->rateLength(array(
			'comment' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate length data provider
 *
 * @return array
 */
	public function rateLengthDataProvider() {
		return array(
			'just link' => array(
				'http://foobar.com',
				-5
			),
			'short' => array(
				'this is too short',
				-1
			),
			'exact' => array(
				'this is exactly size',
				2
			),
			'long' => array(
				'Reset cookies and prompt the user to sign in (includes desktop and mobile devices)',
				2
			),
			'utf8 too short' => array(
				'ɯopuɐɹ ǝɯos sı sıɥʇ',
				-1
			),
			'lenght from links only' => array(
				'foo bar <a href="http://word.com/very-bad">test</a> <a href="http://bad-word.com/also-bad">test</a> baz buz <a href="http://bad-word.com/also-bad">test</a>',
				0
			),
			'1 link, long text' => array(
				'foo bar <a href="http://word.com/very-bad">test</a> some long text with a single link',
				1
			)
		);
	}

/**
 * test rate starting word
 * 
 * @dataProvider rateStartingWordDataProvider
 */
	public function testRateStartingWord($data, $expected) {
		$result = $this->Lib->rateStartingWord(array(
			'comment' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate starting word data provider
 * 
 * @return array
 */
	public function rateStartingWordDataProvider() {
		return array(
			'no match' => array(
				'foo bar baz',
				0
			),
			'match' => array(
				'very-bad started here',
				-10
			),
			'trim match' => array(
				' very-bad started here',
				-10
			)
		);
	}

/**
 * test rate body
 * 
 * @dataProvider rateBodyDataProvider
 */
	public function testRateBody($data, $expected) {
		$result = $this->Lib->rateBody(array(
			'comment' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate body data provider
 * 
 * @return array
 */
	public function rateBodyDataProvider() {
		return array(
			'normal text' => array(
				'Foo bar baz ',
				-12
			),
			'normal text wrong case' => array(
				'foo bar bAz',
				-13
			),
			'repeating text' => array(
				'Foo bar baaaaaaz',
				-1
			),
			'repeating text' => array(
				'STOP SHOUTING AT ME',
				-14
			),
			'mixed with uc words' => array(
				'mixed case with TOOMUCH uppercase',
				-11
			),
			'mixed with uc words multi' => array(
				'mixed case with TOOMUCH UPPERCASE',
				-12
			),
			'repeating text a lot' => array(
				'Fooooo baaaar baaaaaaz',
				-15
			),
			'rubbish' => array(
				'Ppppp foo hrtfvbhd bar bbbrwwwskp baz lkrf',
				-12
			),
			'no case' => array(
				'should use capitals sometimes',
				-12
			),
			'too little ascii' => array(
				'ç¾ä»£çš„ãªãƒšã‚·ãƒŸã‚ºãƒ ã«ã¿ã¡ã¦ã„ã‚‹ã®ã ã‚°ãƒ©ã‚¦ã‚¶ãƒ¼ã',
				-18
			)
		);
	}

/**
 * test rate keywords
 * 
 * @dataProvider rateKeywordsDataProvider
 */
	public function testRateKeywords($data, $expected) {
		$result = $this->Lib->rateKeywords(array(
			'comment' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate keywords data provider
 * 
 * @return array
 */
	public function rateKeywordsDataProvider() {
		return array(
			'none' => array(
				'this is normal text',
				0
			),
			'one' => array(
				'this has the very-bad word',
				-1
			),
			'two' => array(
				'this has the very-bad word and also-bad word',
				-2
			),
			'no-space' => array(
				'this has the very-badalso-bad word',
				-2
			)
		);
	}

/**
 * test rate email
 * 
 * @dataProvider rateEmailDataProvider
 *
 * @return void
 */
	public function testRateEmail($data, $expected) {
		$this->Lib->config(array(
			'column_email' => 'email'
		));
		$result = $this->Lib->rateEmail(array(
			'email' => $data
		));
		$this->assertEquals($expected, $result);
	}

/**
 * rate email data provider
 * 
 * @return array
 */
	public function rateEmailDataProvider() {
		return array(
			'number' => array(
				'123@site.com',
				-15
			),
			'number' => array(
				'******@site.com',
				-15
			),
			'not-number' => array(
				'foo-123@site.com',
				0
			)
		);
	}

/**
 * test rate
 * 
 * @dataProvider rateDataProvider
 *
 * @return void
 */
	public function testRate($data, $expected) {
		$this->Lib = new SpamRating(array(
			'column_email' => 'email',
			'content' => 'comment',
			'blacklist' => array()
		));

		$result = $this->Lib->rate($data['data'], $data['config']);
		$this->assertEquals($expected, $result);
	}

/**
 * rate data provider
 * 
 * @return array
 */
	public function rateDataProvider() {
		return array(
			'short' => array(
				array(
					'data' => array(
						'email' => 'foo@gmail.com',
						'comment' => 'This is a comment'
					),
					'config' => array()
				),
				-8
			),
			'normal' => array(
				array(
					'data' => array(
						'email' => 'foo@gmail.com',
						'comment' => 'This is a normal comment with one link http://site.com that is quite good'
					),
					'config' => array()
				),
				1
			),
			'spam' => array(
				array(
					'data' => array(
						'email' => 'chi_pickett@gmail.com',
						'comment' => 'Your applications programme. Votes add to favorites deliverance \nadd to my human being mins, audience ' .
						'chaturbating very \nhot overgorge girls mental object wrestle two girls wrestle oil hand-to-hand struggle \nmud ' .
						'hand-to-hand struggle mixed rassling really live cam porno golf course juvenile person sex screw picture on \nbed ' .
						'beautiful denizen girls. Latinas go Asiatic wood warbler place rid \nsex gossip mariasynn tuppeny sex gossip temptingness ' .
						'to you if you wish what \nsexy screwing rub it against his leg she can do the anal sex \nor to a greater extent artful ' .
						'adolescent coin lady friend fights two insane immature girls bare girls solon babes voluptuous equipage free live nude ' . 
						'cams lines make \nup one&#039;s mind mould large indefinite amount harder job. And it is my ethical as an fauna sex cams ' .
						'interior av paragon picture show downloads jog unrestricted sex webcams , release acknowledgement move issue lesbian ' .
						'websites gay videos free webcam chatting couple cam smut cams sex camera chat gay gratis free live porn chat',
						'ip' => '46.246.63.69'
					),
					'config' => array()
				),
				-6
			),
			'more-spam' => array(
				array(
					'data' => array(
						'email' => 'alicia.blaine@gmail.com',
						'comment' => 'Mood linkbacks linkback url well-nigh linkbacks marker employ digg this locomote is updated registration is \n' .
							'squinting sorry, you staleness refrain now. Sex erotic stories construe sexy short stories crane cam put across.\nno-count, ' .
							'could not interact to so numerous external recording seizure inclination \nblind, roxio and parcel transmission secure one. ' .
							'The \nrecording converse with asian adult female \nhttp://www.love2dating.com wood warbler nonpublic visit see salience rammesuf ' .
							'personal \nchew the fat inhabit subcategories albania European country European nation Belgique Brasil canada geographic region \nform ' . 
							'of government Scandinavian nation African country state mendicant \ncommonwealth E island free gay videos lav no ratings cams \n' .
							'damage per atomlike textile extras. sound sex girls endure cam \nlarge integer turned on sex with her all \nperiod of time, go free ' .
							'sex dating gay porn movies \ngay web cams lol cam gopro cam lesbian chatrooms \nfree chat website camlive',
						'ip' => '93.182.154.27'
					),
					'config' => array()
				),
				-5
			),
			'extra-fail' => array(
				array(
					'data' => array(
						'email' => 'alicia.blaine@gmail.com',
						'comment' => 'sex mood linkbacks linkback url well-nigh linkbacks marker employ digg this locomote is updated registration is \n' .
							'squinting sorry, you staleness refrain now. Sex erotic stories construe sexy short stories crane cam put across.\nno-count, ' .
							'could not interact to so numerous external recording seizure inclination \nblind, roxio and parcel transmission secure one. ' .
							'The \nrecording converse with asian adult female \nhttp://www.love2dating.com wood warbler nonpublic visit see salience rammesuf ' .
							'personal \nchew the fat inhabit subcategories albania European country European nation Belgique Brasil canada geographic region \nform ' . 
							'of government Scandinavian nation African country state mendicant \ncommonwealth E island free gay videos lav no ratings cams \n' .
							'damage per atomlike textile extras. sound sex girls endure cam \nlarge integer turned on sex with her all \nperiod of time, go free ' .
							'sex dating gay porn movies \ngay web cams lol cam gopro cam lesbian chatrooms \nfree chat website camlive',
						'ip' => '93.182.154.27'
					),
					'config' => array()
				),
				-15
			),
			'extra-super-fail' => array(
				array(
					'data' => array(
						'email' => '123456@gmail.com',
						'comment' => 'sex mood linkbacks linkback url well-nigh linkbacks marker employ digg this locomote is updated registration is \n' .
							'squinting sorry, you staleness refrain now. Sex erotic stories construe sexy short stories crane cam put across.\nno-count, ' .
							'could not interact to so numerous external recording seizure inclination \nblind, roxio and parcel transmission secure one. ' .
							'The \nrecording converse with asian adult female \nhttp://www.love2dating.com wood warbler nonpublic visit see salience rammesuf ' .
							'personal \nchew the fat inhabit subcategories albania European country European nation Belgique Brasil canada geographic region \nform ' . 
							'of government Scandinavian nation African country state mendicant \ncommonwealth E island free gay videos lav no ratings cams \n' .
							'damage per atomlike textile extras. sound sex girls endure cam \nlarge integer turned on sex with her all \nperiod of time, go free ' .
							'sex dating gay porn movies \ngay web cams lol cam gopro cam lesbian chatrooms \nfree chat website camlive',
						'ip' => '93.182.154.27'
					),
					'config' => array()
				),
				-100
			)
		);	
	}

}