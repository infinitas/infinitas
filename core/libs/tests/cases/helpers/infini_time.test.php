<?php
	App::import('lib', 'libs.test/app_helper_test.php');
	App::import('helper', 'Libs.InfiniTime');
	
	class TestInfiniTimeHelper extends AppHelperTestCase {

		/**
		 * @brief Configuration for the test case
		 *
		 * Loading fixtures:
		 * 
		 * List all the needed fixtures in the do part of the fixture array.
		 * In replace you can overwrite fixtures of other plugins by your own.
		 *
		 * 'fixtures' => array(
		 *		'do' => array(
		 *			'SomePlugin.SomeModel
		 *		),
		 *		'replace' => array(
		 *			'Core.User' => 'SomePlugin.User
		 *		)
		 * )
		 * @var array 
		 */
		public $setup = array(
			'helper' => 'Libs.InfiniTime'
		);
		
		/**
		 * @brief Contains a list of test methods to run
		 *
		 * If it is set to false all the methods will run. Otherwise pass in an array
		 * with a list of tests to run.
		 *
		 * @var mixed 
		 */
		public $tests = array('testNice');

		public function startCase() {
			parent::startCase();
	
			App::import('Lib', 'CakeSession');
			$this->Session = new CakeSession();
		}
		
		/**
		 * @brief Tests fromString
		 *
		 * @test Enter description here
		 */
		public function testFromString() {

		}

		/**
		 * @brief Tests nice
		 *
		 * @test Enter description here
		 */
		public function testNice() {
			/**
			 * Test with non UTC server timezone
			 */
			date_default_timezone_set('Europe/London');
			
			//Test timezones in DST
			$dateString = '2011-08-17 16:39:00';
			$this->Session->write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Wed, Aug 17th 2011, 16:39', $this->InfiniTime->nice($dateString));
			$this->Session->write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Wed, Aug 17th 2011, 17:39', $this->InfiniTime->nice($dateString));

			/**
			 * Test with UTC server timezone
			 */
			date_default_timezone_set('UTC');
			
			$dateString = '2011-08-17 16:00:00';
			$this->Session->write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Wed, Aug 17th 2011, 17:00', $this->InfiniTime->nice($dateString));
			$this->Session->write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Wed, Aug 17th 2011, 18:00', $this->InfiniTime->nice($dateString));
			
			//Test timezones currently not in DST
			$dateString = '2011-01-10 12:00:00';
			$this->Session->write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Mon, Jan 10th 2011, 12:00', $this->InfiniTime->nice($dateString));
			$this->Session->write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Mon, Jan 10th 2011, 13:00', $this->InfiniTime->nice($dateString));
		}

		/**
		 * @brief Tests niceShort
		 *
		 * @test Enter description here
		 */
		public function testNiceShort() {
			
		}

		/**
		 * @brief Tests dayAsSql
		 *
		 * @test Enter description here
		 */
		public function testDayAsSql() {
			
		}

		/**
		 * @brief Tests isToday
		 *
		 * @test Enter description here
		 */
		public function testIsToday() {
			
		}

		/**
		 * @brief Tests isThisWeek
		 *
		 * @test Enter description here
		 */
		public function testIsThisWeek() {
			
		}

		/**
		 * @brief Tests isThisMonth
		 *
		 * @test Enter description here
		 */
		public function testIsThisMonth() {
			
		}

		/**
		 * @brief Tests isThisYear
		 *
		 * @test Enter description here
		 */
		public function testIsThisYear() {
			
		}

		/**
		 * @brief Tests wasYesterday
		 *
		 * @test Enter description here
		 */
		public function testWasYesterday() {
			
		}

		/**
		 * @brief Tests isTomorrow
		 *
		 * @test Enter description here
		 */
		public function testIsTomorrow() {
			
		}

		/**
		 * @brief Tests toUnix
		 *
		 * @test Enter description here
		 */
		public function testToUnix() {
			
		}

		/**
		 * @brief Tests toAtom
		 *
		 * @test Enter description here
		 */
		public function testToAtom() {
			
		}

		/**
		 * @brief Tests toRSS
		 *
		 * @test Enter description here
		 */
		public function testToRSS() {
			
		}

		/**
		 * @brief Tests timeAgoInWords
		 *
		 * @test Enter description here
		 */
		public function testTimeAgoInWord() {
			
		}

		/**
		 * @brief Tests relativeTime
		 *
		 * @test Enter description here
		 */
		public function testRelativeTime() {
			
		}

		/**
		 * @brief Tests wasWithinLast
		 *
		 * @test Enter description here
		 */
		public function testWasWithinLast() {
			
		}

		/**
		 * @brief Tests format
		 *
		 * @test Enter description here
		 */
		public function testFormat() {
			
		}

		/**
		 * @brief Tests i18nFormat
		 *
		 * @test Enter description here
		 */
		public function testI18nFormat() {
			
		}
	}