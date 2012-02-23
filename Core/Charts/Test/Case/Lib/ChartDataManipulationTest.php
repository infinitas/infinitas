<?php
	App::uses('ChartDataManipulation', 'Charts.Lib');

	class ChartDataManipulationTest extends CakeTestCase {
		public $fixtures = array(
			'plugin.configs.config',
			'plugin.themes.theme',
			'plugin.routes.route',
			'plugin.view_counter.view_count',

			'plugin.crons.cron'
		);

		public function startTest(){
			$this->ChartManipulation = new ChartDataManipulation();
			$this->Cron = ClassRegistry::init('Crons.Cron');
		}

		public function endTest() {
			unset($this->ChartManipulation);
			ClassRegistry::flush();
		}

		/**
		 * test getting the min / max date
		 */
		public function testGetDates(){
			$crons = $this->Cron->find('all');
			$result = $this->ChartManipulation->getDates($crons, array());
			$expected = array();
			$this->assertEquals($expected, $result);

			$options = array('alias' => $this->Cron->alias);
			$result = $this->ChartManipulation->getDates($crons, $options);
			$expected = array('start_date' => '2010-12-07 02:29:02', 'end_date' => '2010-12-07 14:21:01');
			$this->assertEquals($expected, $result);

			$options = array('alias' => $this->Cron->alias, 'date_field' => 'created');
			$result = $this->ChartManipulation->getDates($crons, $options);
			$expected = array('start_date' => '2010-12-07 02:29:02', 'end_date' => '2010-12-07 14:21:01');
			$this->assertEquals($expected, $result);

			$options = array('extract' => '/' . $this->Cron->alias . '/created');
			$result = $this->ChartManipulation->getDates($crons, $options);
			$expected = array('start_date' => '2010-12-07 02:29:02', 'end_date' => '2010-12-07 14:21:01');
			$this->assertEquals($expected, $result);
		}

		/**
		 * test getting the stats for the data
		 */
		public function testGetStats(){
			$this->Cron->virtualFields['average_load'] = 'ROUND(AVG(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['max_load']     = 'ROUND(MAX(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['hour']         = 'HOUR(' . $this->Cron->alias . '.start_time)';
			$crons = $this->Cron->find(
				'all',
				array(
					'fields' => array($this->Cron->alias . '.id', 'hour', 'average_load', 'max_load', 'created'),
					'group' => array('hour')
				)
			);

			$options = array('alias' => $this->Cron->alias, 'fields' => array('average_load', 'max_load'), 'blanks' => false, 'stats' => true);
			$format = $this->ChartManipulation->getFormatted($crons, $options);
			$expected = array(
				'average_load' => unserialize('a:5:{s:3:"max";s:5:"0.155";s:3:"min";s:5:"0.000";s:5:"total";d:0.422000000000000152766688188421539962291717529296875;s:7:"average";d:0.03246153846153847222222310620054486207664012908935546875;s:6:"median";s:5:"0.017";}'),
				'stats' => unserialize('a:5:{s:3:"max";s:5:"0.360";s:3:"min";s:5:"0.000";s:5:"total";d:1.9320000000000003836930773104541003704071044921875;s:7:"average";d:0.0743076923076923245847780208350741304457187652587890625;s:6:"median";d:0.040000000000000000832667268468867405317723751068115234375;}'),
				'max_load' => unserialize('a:5:{s:3:"max";s:5:"0.360";s:3:"min";s:5:"0.000";s:5:"total";d:1.510000000000000230926389122032560408115386962890625;s:7:"average";d:0.11615384615384617694733293546960339881479740142822265625;s:6:"median";s:5:"0.080";}')
			);
			$this->assertEquals($expected['average_load'], $format['stats']['average_load']);
			$this->assertEquals($expected['max_load'], $format['stats']['max_load']);
			unset($format['stats']['average_load'], $format['stats']['max_load']);

			$this->assertEquals($expected['stats'], $format['stats']);
		}

		/**
		 * test normalizing the data
		 */
		public function testGetNormalized(){
			$this->Cron->virtualFields['average_load'] = 'ROUND(AVG(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['max_load']     = 'ROUND(MAX(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['hour']         = 'HOUR(' . $this->Cron->alias . '.start_time)';
			$crons = $this->Cron->find(
				'all',
				array(
					'fields' => array($this->Cron->alias . '.id', 'hour', 'average_load', 'max_load', 'created'),
					'group' => array('hour')
				)
			);

			/**
			 * test normal
			 */
			$options = array('alias' => $this->Cron->alias, 'fields' => array('average_load', 'max_load'), 'blanks' => false, 'normalize' => true);
			$format = $this->ChartManipulation->getFormatted($crons, $options);
			$expected = array(
				'average_load' => explode(',', '100,55,12,5,9,21,8,14,10,4,11,22,0'),
				'max_load'     => explode(',', '100,83,25,11,11,58,14,39,22,11,17,28,0')
			);
			$this->assertEquals($expected['average_load'], $format['average_load']);
			$this->assertEquals($expected['max_load'], $format['max_load']);

			/**
			 * test different base
			 */
			$options['normalize'] = 50;
			$format = $this->ChartManipulation->getFormatted($crons, $options);
			$expected = array(
				'average_load' => explode(',', '50,28,6,3,5,11,4,7,5,2,5,11,0'),
				'max_load'     => explode(',', '50,42,13,6,6,29,7,19,11,6,8,14,0')
			);
			$this->assertEquals($expected['average_load'], $format['average_load']);
			$this->assertEquals($expected['max_load'], $format['max_load']);

			/**
			 * test different base with rounding
			 */
			$options['normalize'] = array('base' => 2, 'round' => 3);
			$format = $this->ChartManipulation->getFormatted($crons, $options);
			$expected = array(
				'average_load' => explode(',', '2,1.11,0.245,0.103,0.181,0.426,0.155,0.284,0.206,0.077,0.219,0.439,0'),
				'max_load'     => explode(',', '2,1.667,0.5,0.222,0.222,1.167,0.278,0.778,0.444,0.222,0.333,0.556,0')
			);
			$this->assertEquals($expected['average_load'], $format['average_load']);
			$this->assertEquals($expected['max_load'], $format['max_load']);
		}

		/**
		 * test extracting the data
		 */
		public function testGetData(){
			$this->Cron->virtualFields['average_load'] = 'ROUND(AVG(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['max_load']     = 'ROUND(MAX(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['hour']         = 'HOUR(' . $this->Cron->alias . '.start_time)';
			$crons = $this->Cron->find(
				'all',
				array(
					'fields' => array($this->Cron->alias . '.id', 'hour', 'average_load', 'max_load', 'created'),
					'group' => array('hour')
				)
			);

			/** fails */
			$result = $this->ChartManipulation->getData($crons, array());
			$expected = array();
			$this->assertEquals($expected, $result);

			$result = $this->ChartManipulation->getData($crons, array('alias' => $this->Cron->alias));
			$expected = array();
			$this->assertEquals($expected, $result);

			/**
			 * test multi fields
			 */
			$options = array('alias' => $this->Cron->alias, 'fields' => array('average_load', 'max_load'), 'blanks' => false);
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array(
				'average_load' => explode(',', '0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000'),
				'max_load'     => explode(',', '0.360,0.300,0.090,0.040,0.040,0.210,0.050,0.140,0.080,0.040,0.060,0.100,0.000')
			);
			$this->assertEquals($expected, $result);

			/**
			 * test single field
			 */
			$options['fields'] = array('average_load');
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array(
				'average_load' => explode(',', '0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000')
			);
			$this->assertEquals($expected, $result);

			/**
			 * test string field
			 */
			$options['fields'] = 'average_load';
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array(
				'average_load' => explode(',', '0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000')
			);
			$this->assertEquals($expected, $result);

			/**
			 * test get blanks default
			 */
			$options = array(
				'alias' => $this->Cron->alias, 'fields' => array('average_load', 'max_load'),
				'blanks' => true, 'range' => range(0, 23)
			);
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array(
				'average_load' => explode(',', '0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000,0,0,0,0,0,0,0,0,0,0,0'),
				'max_load'     => explode(',', '0.360,0.300,0.090,0.040,0.040,0.210,0.050,0.140,0.080,0.040,0.060,0.100,0.000,0,0,0,0,0,0,0,0,0,0,0')
			);
			$this->assertEquals($expected, $result);

			/**
			 * test get blanks after
			 */
			$options['insert'] = 'after';
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array(
				'average_load' => explode(',', '0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000,0,0,0,0,0,0,0,0,0,0,0'),
				'max_load'     => explode(',', '0.360,0.300,0.090,0.040,0.040,0.210,0.050,0.140,0.080,0.040,0.060,0.100,0.000,0,0,0,0,0,0,0,0,0,0,0')
			);
			$this->assertEquals($expected, $this->ChartManipulation->getData($crons, $options));

			/**
			 * test get blanks befor
			 */
			$options['insert'] = 'before';
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array(
				'average_load' => explode(',', '0,0,0,0,0,0,0,0,0,0,0,0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000'),
				'max_load'     => explode(',', '0,0,0,0,0,0,0,0,0,0,0,0.360,0.300,0.090,0.040,0.040,0.210,0.050,0.140,0.080,0.040,0.060,0.100,0.000')
			);
			$this->assertEquals($expected, $result);

			/**
			 * test missing range
			 */
			unset($options['range']);
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array('average_load' => array(), 'max_load' => array());
			$this->assertEquals($expected, $result);

			/**
			 * test non array range
			 */
			$options['range'] = 'foo';
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array('average_load' => array(), 'max_load' => array());
			$this->assertEquals($expected, $result);

			/**
			 * test wrong insert
			 */
			$options['range'] = range(0, 23);
			$options['insert'] = 'bar';
			$result = $this->ChartManipulation->getData($crons, $options);
			$expected = array('average_load' => array(), 'max_load' => array());
			$this->assertEquals($expected, $result);
		}

		public function testGetFormatted(){
			$this->Cron->virtualFields['average_load'] = 'ROUND(AVG(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['max_load']     = 'ROUND(MAX(' . $this->Cron->alias . '.load_ave), 3)';
			$this->Cron->virtualFields['hour']         = 'HOUR(' . $this->Cron->alias . '.start_time)';
			$crons = $this->Cron->find(
				'all',
				array(
					'fields' => array($this->Cron->alias . '.id', 'hour', 'average_load', 'max_load', 'created'),
					'group' => array('hour')
				)
			);

			$options = array('alias' => $this->Cron->alias, 'fields' => array('average_load', 'max_load'), 'blanks' => false);
			$format = $this->ChartManipulation->getFormatted($crons, $options);
			$expected = array(
				'start_date' => '2010-12-07 02:29:02',
				'end_date' => '2010-12-07 14:01:01',
				'average_load' => explode(',', '0.155,0.086,0.019,0.008,0.014,0.033,0.012,0.022,0.016,0.006,0.017,0.034,0.000'),
				'max_load'     => explode(',', '0.360,0.300,0.090,0.040,0.040,0.210,0.050,0.140,0.080,0.040,0.060,0.100,0.000')
			);
			$this->assertEquals($expected, $format);

			$date = $this->ChartManipulation->getDates($crons, $options);
			$this->assertEquals($format['start_date'], $date['start_date']);
			$this->assertEquals($format['end_date'], $date['end_date']);
		}
	}

