<?php
	App::import('lib', 'libs.test/app_helper_test.php');
	App::import('helper', 'Libs.InfiniTime');

	class TestInfiniTimeHelper extends CakeTestCase {

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
		public $tests = false;

		/**
		 * @brief Contains the backup of the system timezone
		 *
		 * @var string
		 */
		private $__timeZoneBackup = null;

		public function startCase() {
			parent::startCase();
			App::import('Lib', 'CakeSession');
		}

		public function startTest($method) {
			parent::startTest($method);

			$this->__timeZoneBackup = date_default_timezone_get();
			date_default_timezone_set('UTC');
		}

		public function endTest($method) {
			parent::endTest($method);

			date_default_timezone_set($this->__timeZoneBackup);
		}

		/**
		 * @brief Tests fromString
		 *
		 * @test Enter description here
		 */
		public function testFromString() {
			CakeSession::write('Auth.User.time_zone', 'Europe/London');

			$result = $this->InfiniTime->fromString('');
			$this->assertFalse($result);

			$result = $this->InfiniTime->fromString(0);
			$this->assertFalse($result);

			$result = $this->InfiniTime->fromString('2007-09-10 18:00:00');
			$expected = strtotime('2007-09-10 18:00:00') + 3600;
			$this->assertEqual($result, $expected);

			//Test overwriting the timezone offset
			$result = $this->InfiniTime->fromString('2007-09-10 18:00:00', 5);
			$expected = strtotime('2007-09-10 18:00:00') + (3600 * 5);
			$this->assertEqual($result, $expected);
		}

		/**
		 * @brief Tests nice strings
		 *
		 * @test Enter description here
		 */
		public function testNiceStrings() {
			/**
			 * Test with non UTC server timezone
			 */
			date_default_timezone_set('Europe/London');

			$dateString = '2011-08-17 16:39:00';

			CakeSession::write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Wed, Aug 17th 2011, 16:39', $this->InfiniTime->nice($dateString));
			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Wed, Aug 17th 2011, 17:39', $this->InfiniTime->nice($dateString));

			/**
			 * Test with UTC server timezone
			 */
			date_default_timezone_set('UTC');

			//Test timezones in DST
			$dateString = '2011-08-17 16:00:00';

			CakeSession::write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Wed, Aug 17th 2011, 17:00', $this->InfiniTime->nice($dateString));
			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Wed, Aug 17th 2011, 18:00', $this->InfiniTime->nice($dateString));
			CakeSession::write('Auth.User.time_zone', 'America/Mexico_City');
			$this->assertEqual('Wed, Aug 17th 2011, 11:00', $this->InfiniTime->nice($dateString));

			//Test timezones currently not in DST
			$dateString = '2011-01-10 12:00:00';

			CakeSession::write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Mon, Jan 10th 2011, 12:00', $this->InfiniTime->nice($dateString));
			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Mon, Jan 10th 2011, 13:00', $this->InfiniTime->nice($dateString));
			CakeSession::write('Auth.User.time_zone', 'America/Mexico_City');
			$this->assertEqual('Mon, Jan 10th 2011, 06:00', $this->InfiniTime->nice($dateString));
			//Test a non hour offset
			CakeSession::write('Auth.User.time_zone', 'America/Caracas');
			$this->assertEqual('Mon, Jan 10th 2011, 07:30', $this->InfiniTime->nice($dateString));

			CakeSession::write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Jul 17th 2004, 17:00', $this->InfiniTime->niceShort('2004-07-17 16:00:00'));
		}

		public function testRelativeTimeFunctions() {
			CakeSession::write('Auth.User.time_zone', 'America/New_York');

			$this->assertFalse($this->InfiniTime->isToday('01:00:00'));
			$this->assertTrue($this->InfiniTime->isToday('10:00:00'));

			$monday = date('Y-m-d', strtotime('-' . (date('N')-1) . 'DAY'));
			$this->assertFalse($this->InfiniTime->isThisWeek($monday . ' 01:00:00'));
			$this->assertTrue($this->InfiniTime->isThisWeek($monday . ' 10:00:00'));

			$firstDayOfMonth = date('Y-m-d', mktime(date('H'), date('i') , date('s'), date('n'), 1, date('Y')));
			$this->assertFalse($this->InfiniTime->isThisMonth($firstDayOfMonth . ' 01:00:00'));
			$this->assertTrue($this->InfiniTime->isThisMonth($firstDayOfMonth . ' 10:00:00'));

			$firstDayOfYear = date('Y-m-d', mktime(date('H'), date('i') , date('s'), 1, 1, date('Y')));
			$this->assertFalse($this->InfiniTime->isThisYear($firstDayOfYear . ' 01:00:00'));
			$this->assertTrue($this->InfiniTime->isThisYear($firstDayOfYear . ' 10:00:00'));

			$this->assertTrue($this->InfiniTime->wasYesterday('01:00:00'));
			$this->assertFalse($this->InfiniTime->wasYesterday('10:00:00'));

			$this->assertFalse($this->InfiniTime->isTomorrow(date('Y-m-d', strtotime('+1 DAY')) . ' 01:00:00'));
			$this->assertTrue($this->InfiniTime->isTomorrow(date('Y-m-d', strtotime('+1 DAY')) . ' 10:00:00'));
		}

		public function testOutputFormats() {
			CakeSession::write('Auth.User.time_zone', 'America/New_York');

			$timestamp = $this->InfiniTime->toUnix('2011-08-18 10:42:00');
			$this->assertEqual('2011-08-18 06:42:00', date('Y-m-d H:i:s', $timestamp));

			$this->assertEqual('2011-08-18T06:42:00Z', $this->InfiniTime->toAtom('2011-08-18 10:42:00'));

			$this->assertEqual('Thu, 18 Aug 2011 06:42:00 -0400', $this->InfiniTime->toRSS('2011-08-18 10:42:00'));

			CakeSession::write('Auth.User.time_zone', 'Europe/London');
			$this->assertEqual('Thu, 18 Aug 2011 11:42:00 +0100', $this->InfiniTime->toRSS('2011-08-18 10:42:00'));
			$this->assertEqual('Thu, 20 Jan 2011 18:00:15 +0000', $this->InfiniTime->toRSS('2011-01-20 18:00:15'));

			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');
			$this->assertEqual('Thu, 18 Aug 2011 12:42:00 +0200', $this->InfiniTime->toRSS('2011-08-18 10:42:00'));
		}

		/**
		 * @brief Tests timeAgoInWords
		 *
		 * @test Enter description here
		 */
		public function testTimeAgoInWord() {
			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');

			$result = $this->InfiniTime->timeAgoInWords(strtotime('-2 hours'));
			$this->AssertEqual('2 hours ago', $result);

			$result = $this->InfiniTime->timeAgoInWords('2010-05-10 10:00:00', array('format' => 'Y-m-d H:i:s'));
			$this->assertEqual('on 2010-05-10 12:00:00', $result);

			$result = $this->InfiniTime->timeAgoInWords(strtotime('1995-01-10 10:00:00'), array('format' => 'Y-m-d H:i:s'));
			$this->assertEqual('on 1995-01-10 11:00:00', $result);

			//Test overwriting the offset
			$result = $this->InfiniTime->timeAgoInWords(strtotime('1995-01-10 10:00:00'), array('format' => 'Y-m-d H:i:s', 'userOffset' => 5));
			$this->assertEqual('on 1995-01-10 15:00:00', $result);
		}


		public function testVarious() {
			CakeSession::write('Auth.User.time_zone', 'Europe/London');
			$result = $this->InfiniTime->dayAsSql('2009-10-30', 'Item.created');
			$this->assertEqual("(Item.created >= '2009-10-30 00:00:00') AND (Item.created <= '2009-10-30 23:59:59')", $result);

			$result = $this->InfiniTime->format('Y-m-d H:i:s', '2010-10-24 14:15:10');
			$this->assertEqual('2010-10-24 15:15:10', $result);

			$this->assertTrue($this->InfiniTime->wasWithinLast('5 hours', strtotime('-2 hours')));

			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');
			setlocale(LC_TIME, 'nl_NL');
			$result = $this->InfiniTime->i18nFormat('2010-11-15 15:14:00', '%A, %e %B %Y %H:%M');
			$this->assertEqual('maandag, 15 november 2010 16:14', $result);
			setlocale(LC_TIME, NULL);
		}
		/**
		 * @brief Tests relativeTime
		 *
		 * @test Enter description here
		 */
		public function testRelativeTime() {
			CakeSession::write('Auth.User.time_zone', 'Europe/Brussels');
			$result = $this->InfiniTime->relativeTime('1998-05-20 10:00:00', array('format' => 'Y-m-d H:i:s'));
			$this->assertEqual('on 1998-05-20 12:00:00', $result);
		}
	}