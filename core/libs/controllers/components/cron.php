<?php
/**
	* Implements a Pseudo-Cron in php which  uses a syntax file very much like the
	* Unix cron's one. For an overview of the syntax used, *see a reference man
	* page. The syntax this component uses is different from the linux in just one
	* point: it does not have a user *column.
	*
	* It runs any job you have specified in your crontab file just after rendering
	* the output to the user, so he won't notice any slowdown in the page execution.
	*
	* Just declare in any of your most requested controllers
	* var $component = array('Cron');
	* It needs a writable folder in wich you can store the crontab file and the cron log files.
	*
	* @author José Lorenzo Rodríguez
	* @version 0.8
	* @link http://www.unixgeeks.org/security/newbie/unix/cron-1.html
	* @license GPL
	*/

	/**
	* Usage
	* Crontab.txt file example:
	* The last line will call /emails/send every 15 minutes between 8am and 7pm on Monday to Friday.
	*
	* #comments start with '#'
	* #mi  h    d    m    dow      job                       comment
	* 0    5    *    *    Sun      /users/cleanup     # make user cleanu every sunday at 5 am
	* 40   5    2    *    *        /posts/makeIndex   #build an index of your posts
	*/
	// */15 8-19 *    *    Mon-Fri  /emails/send       # Send emails
	class CronComponent extends Object {
		var $Controller;
		var $components = array('RequestHandler');

		/**
		*
		* @var string $crontab
		* The file that contains the job descriptions.For a description of the format, see
		* @link http://www.unixgeeks.org/security/newbie/unix/cron-1.html
		* @link http://www.bitfolge.de/pseudocron
		*/
		var $cronTab;

		/**
		*
		* @var string $writeDir
		* The directory where the script can store information on completed jobs and its log file. Include trailing slash
		*/
		var $writeDir;

		/**
		*
		* @var boolea $useLog
		* Control logging, true=use log file, false=don't use log file
		*/
		var $useLog = true;

		/**
		*
		* @var string $sendLogToEmail
		* Where to send cron results.
		* $sendLogToEmail = "youraddess@mail.domain";
		*/
		var $sendLogToEmail = "";

		/**
		*
		* @var int $maxJobs
		* Maximum number of jobs run during one call of pseudocron.
		* Set to a low value if your jobs take longer than a few seconds and if you scheduled them
		* very close to each other. Set to 0 to run any number of jobs.
		*/
		var $maxJobs = 1;

		/**
		*
		* @var boolean $debug
		* Turn on / off debugging output
		* DO NOT use this on live servers!
		*/
		var $debug = true;

		/**
		*
		* @var string $resultSummary
		* String that contains the results of a job
		*/
		var $resultsSummary;

		/**
		*
		* @var array $jobs
		* jobs to be executed by cron.
		*/
		var $jobs = array();

		/**
		* Controllers initialize function.
		*/
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$writeDir = APP . 'libs' . DS . 'cronjobs' . DS;
			$settings = array_merge(array('cronTab' => $writeDir . 'crontab.txt', 'writeDir' => $writeDir), $settings);
			$this->_set($settings);
		}

		/**
		* Controllers startup function.
		*/
		function startup(&$controller) {
			if (!defined('CRON_INTERVAL')) {
				define('CRON_INTERVAL', 1); //Modify this if you want. this is the time interval betwen each run of this script
				// Dependig on how much traffic you have in your controller's component caller.
			}

			if (!defined('CRON_MINUTE')) {
				define('CRON_MINUTE', 1);
			}

			if (!defined('CRON_HOUR')) {
				define('CRON_HOUR', 2);
			}

			if (!defined('CRON_DOM')) {
				define('CRON_DOM', 3);
			}

			if (!defined('CRON_MONTH')) {
				define('CRON_MONTH', 4);
			}

			if (!defined('CRON_DOW')) {
				define('CRON_DOW', 5);
			}

			if (!defined('CRON_CMD')) {
				define('CRON_CMD', 7);
			}

			if (!defined('CRON_COMMENT')) {
				define('CRON_COMMENT', 8);
			}

			if (!defined('CRON_CRONLINE')) {
				define('CRON_CRONLINE', 20);
			}
		}

		/**
		* Logs a message in cron.log file
		*/
		function _logMessage($msg) {
			if ($msg[strlen($msg) - 1] != "\n") {
				$msg .= "\n";
			}

			if ($this->debug) debug($msg);

			$this->resultsSummary .= $msg;

			if ($this->useLog) {
				$logfile = $this->writeDir . "cron.log";
				$file = fopen($logfile, "a");
				fputs($file, date("r", time()) . "  " . $msg);
				fclose($file);
			}
		}

		function _lTrimZeros($number) {
			while ($number[0] == '0') {
				$number = substr($number, 1);
			}
			return $number;
		}

		function _multisort(&$array, $sortby, $order = 'asc') {
			foreach($array as $val) {
				$sortarray[] = $val[$sortby];
			}
			$c = $array;
			$const = $order == 'asc' ? SORT_ASC : SORT_DESC;
			$s = array_multisort($sortarray, $const, $c, $const);
			$array = $c;
			return $s;
		}

		function _getJobFileName($jobname) {
			$jobfile = $this->writeDir . urlencode($jobname) . ".job";
			return $jobfile;
		}

		function _getLastActualRunTime($jobname) {
			$jobfile = $this->_getJobFileName($jobname);
			if (file_exists($jobfile)) {
				return filemtime($jobfile);
			}
			return 0;
		}

		function _incDate(&$dateArr, $amount, $unit) {
			if ($this->debug) debug(sprintf("Increasing from %02d.%02d. %02d:%02d by %d %6s ", $dateArr['mday'], $dateArr['mon'], $dateArr['hours'], $dateArr['minutes'], $amount, $unit));
			if ($unit == "mday") {
				$dateArr["hours"] = 0;
				$dateArr["minutes"] = 0;
				$dateArr["seconds"] = 0;
				$dateArr["mday"] += $amount;
				$dateArr["wday"] += $amount % 7;
				if ($dateArr["wday"] > 6) {
					$dateArr["wday"] -= 7;
				}

				$months28 = Array(2);
				$months30 = Array(4, 6, 9, 11);
				$months31 = Array(1, 3, 5, 7, 8, 10, 12);

				if (
					(in_array($dateArr["mon"], $months28) && $dateArr["mday"] == 28) ||
						(in_array($dateArr["mon"], $months30) && $dateArr["mday"] == 30) ||
						(in_array($dateArr["mon"], $months31) && $dateArr["mday"] == 31)
						) {
					$dateArr["mon"]++;
					$dateArr["mday"] = 1;
				}
			} elseif ($unit == "hour") {
				if ($dateArr["hours"] == 23) {
					$this->_incDate($dateArr, 1, "mday");
				}else {
					$dateArr["minutes"] = 0;
					$dateArr["seconds"] = 0;
					$dateArr["hours"]++;
				}
			} elseif ($unit == "minute") {
				if ($dateArr["minutes"] == 59) {
					$this->incDate($dateArr, 1, "hour");
				}else {
					$dateArr["seconds"] = 0;
					$dateArr["minutes"]++;
				}
			}
			if ($this->debug) debug(sprintf("to %02d.%02d. %02d:%02d\n", $dateArr['mday'], $dateArr['mon'], $dateArr['hours'], $dateArr['minutes']));
		}

		function _parseElement($element, &$targetArray, $numberOfElements) {
			$this->log($element, 'cron_jobs');
			$subelements = explode(",", $element);

			for ($i = 0;$i < $numberOfElements;$i++) {
				$targetArray[$i] = $subelements[0] == "*";
			}

			for ($i = 0;$i < count($subelements);$i++) {
				if (preg_match("~^(\\*|([0-9]{1,2})(-([0-9]{1,2}))?)(/([0-9]{1,2}))?$~", $subelements[$i], $matches)) {
					if ($matches[1] == '*') {
						$matches[2] = 0; // from
						$matches[4] = $numberOfElements; //to
					}elseif (!isset($matches[4]) || $matches[4] == '') {
						$matches[4] = $matches[2];
					}

					if ($matches[5][0] != '/') {
						$matches[6] = 1; // step
					}

					for ($j = $this->_lTrimZeros($matches[2]);$j <= $this->_lTrimZeros($matches[4]);$j += $this->_lTrimZeros($matches[6])) {
						$targetArray[$j] = true;
					}
				}
			}
		}

		function _getLastScheduledRunTime($job) {
			$extjob = array();
			$this->_parseElement($job[CRON_MINUTE], $extjob[CRON_MINUTE], 60);
			$this->_parseElement($job[CRON_HOUR], $extjob[CRON_HOUR], 24);
			$this->_parseElement($job[CRON_DOM], $extjob[CRON_DOM], 31);
			$this->_parseElement($job[CRON_MONTH], $extjob[CRON_MONTH], 12);
			$this->_parseElement($job[CRON_DOW], $extjob[CRON_DOW], 7);

			$dateArr = getdate($this->_getLastActualRunTime($job[CRON_CMD]));
			$minutesAhead = 0;
			while ($minutesAhead < 525600 AND
				(!$extjob[CRON_MINUTE][$dateArr["minutes"]] OR
					!$extjob[CRON_HOUR][$dateArr["hours"]] OR
					(!$extjob[CRON_DOM][$dateArr["mday"]] OR !$extjob[CRON_DOW][$dateArr["wday"]]) OR
					!$extjob[CRON_MONTH][$dateArr["mon"]])
				) {
				if (!$extjob[CRON_DOM][$dateArr["mday"]] OR !$extjob[CRON_DOW][$dateArr["wday"]]) {
					$this->_incDate($dateArr, 1, "mday");
					$minutesAhead += 1440;
					continue;
				}
				if (!$extjob[CRON_HOUR][$dateArr["hours"]]) {
					$this->_incDate($dateArr, 1, "hour");
					$minutesAhead += 60;
					continue;
				}
				if (!$extjob[CRON_MINUTE][$dateArr["minutes"]]) {
					$this->_incDate($dateArr, 1, "minute");
					$minutesAhead++;
					continue;
				}
			}

			if ($this->debug) debug("Date Array: " . print_r($dateArr));

			return mktime($dateArr["hours"], $dateArr["minutes"], 0, $dateArr["mon"], $dateArr["mday"], $dateArr["year"]);
		}

		function _parseCronFile() {
			// if(!@is_file($this->crontab)) return;
			$file = file($this->cronTab);
			$job = Array();

			for ($i = 0; $i < count($file); $i++) {
				if ($file[$i][0] != '#' && $file[$i] != "") {
					if (preg_match("~^([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-9,/*]+)\\s+([-0-7,/*]+|(-|/|Sun|Mon|Tue|Wed|Thu|Fri|Sat)+)\\s+([^#]*)\\s*(#.*)?$~i", $file[$i], $job)) {
						$jobNumber = count($this->jobs);
						$this->jobs[$jobNumber] = $job;
						if ($this->jobs[$jobNumber][CRON_DOW][0] != '*' && !is_numeric($this->jobs[$jobNumber][CRON_DOW])) {
							$this->jobs[$jobNumber][CRON_DOW] = str_replace(
								Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"),
								Array(0, 1, 2, 3, 4, 5, 6),
								$this->jobs[$jobNumber][CRON_DOW]);
						}
						$this->jobs[$jobNumber][CRON_CMD] = trim($job[CRON_CMD]);
						$this->jobs[$jobNumber][CRON_COMMENT] = trim(substr($job[CRON_COMMENT], 1));
						$this->jobs[$jobNumber][CRON_CRONLINE] = $file[$i];
					}else {
						if ($this->debug) debug('Bad crontab file in line  ' . ($i + 1));
					}

					$this->jobs[$jobNumber]["lastActual"] = $this->_getLastActualRunTime($this->jobs[$jobNumber][CRON_CMD]);
					$this->jobs[$jobNumber]["lastScheduled"] = $this->_getLastScheduledRunTime($this->jobs[$jobNumber]);
				}
			}

			$this->_multisort($this->jobs, "lastScheduled");

			if ($this->debug) var_dump($this->jobs);
		}

		function _markLastRun($jobname) {
			$jobfile = $this->_getJobFileName($jobname);
			touch($jobfile);
		}

		function _runJob($job) {
			$this->resultsSummary = "";
			$lastActual = $job["lastActual"];
			$lastScheduled = $job["lastScheduled"];

			if ($lastScheduled < time()) {
				$this->_logMessage("Running 	" . $job[CRON_CRONLINE]);
				$this->_logMessage("  Last run:       " . date("r", $lastActual));
				$this->_logMessage("  Last scheduled: " . date("r", $lastScheduled));
				if ($this->debug) {
					@$this->Controller->requestAction($job[CRON_CMD]);
					debug('Fue llamada la accion ' . $job[CRON_CMD]);
				}else {
					@$this->Controller->requestAction($job[CRON_CMD]);
				}
				$this->_markLastRun($job[CRON_CMD]);
				$this->_logMessage("Completed	" . $job[CRON_CRONLINE]);
				if ($this->sendLogToEmail != "") {
					mail($this->sendLogToEmail, "[cron] " . $job[CRON_COMMENT], $this->resultsSummary);
				}
				return true;
			}else {
				if ($this->debug) {
					$this->_logMessage("Skipping 	" . $job[CRON_CRONLINE]);
					$this->_logMessage("  Last run:       " . date("r", $lastActual));
					$this->_logMessage("  Last scheduled: " . date("r", $lastScheduled));
					$this->_logMessage("Completed	" . $job[CRON_CRONLINE]);
				}
				return false;
			}
		}

		function __destruct() {
			if (CRON_INTERVAL) {
				$lastrun = $this->_getLastActualRunTime("cron");
				$nextrun = $lastrun + 60 * CRON_INTERVAL;
				if (time() > $nextrun) {
					if (!$this->debug) flush();
					if (! isset($this->requestHandler) OR !$this->requestHandler->isAjax()) {
						$this->_markLastRun("cron");
						$this->_parseCronFile($this->cronTab);
						$jobsRun = 0;
						for ($i = 0;$i < count($this->jobs);$i++) {
							if ($this->maxJobs == 0 || $jobsRun < $this->maxJobs) {
								if ($this->_runJob($this->jobs[$i])) {
									$jobsRun++;
								}
							}
						}
					}
				}
			}
		}
	}