<?php
/**
 * Wizard component by jaredhoyt.
 *
 * Handles multi-step form navigation, data persistence, validation callbacks, and plot-branching navigation.
 *
 * PHP versions 4 and 5
 *
 * Comments and bug reports welcome at jaredhoyt AT gmail DOT com
 *
 * Licensed under The MIT License
 *
 * @writtenby		jaredhoyt
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class WizardComponent extends Object {
/**
 * The Component will redirect to the "expected step" after a step has been successfully
 * completed if autoAdvance is true. If false, the Component will redirect to
 * the next step in the $steps array. (This is helpful for returning a user to
 * the expected step after editing a previous step w/o them having to navigate through
 * each step in between.)
 *
 * @var boolean
 * @access public
 */
	var $autoAdvance = true;
/**
 * Option to automatically reset if the wizard does not follow "normal"
 * operation. (ie. manual url changing, navigation away and returning, etc.)
 * Set this to false if you want the Wizard to return to the "expected step"
 * after invalid navigation.
 *
 * @var boolean
 * @access public
 */
	var $autoReset = false;
/**
 * If no processCallback() exists for the current step, the component will automatically
 * validate the model data against the models included in the controller's uses array.
 *
 * @var boolean
 * @access public
 */
	var $autoValidate = false;
/**
 * List of steps, in order, that are to be included in the wizard.
 *		basic example: $steps = array('contact', 'payment', 'confirm');
 *
 * The $steps array can also contain nested steps arrays of the same format but must be wrapped by a branch group.
 * 		plot-branched example: $steps = array('job_application', array('degree' => array('college', 'degree_type'), 'nodegree' => 'experience'), 'confirm');
 *
 * The 'branchnames' (ie 'degree', 'nodegree') are arbitrary but used as selectors for the branch() and unbranch() methods. Branches
 * can point to either another steps array or a single step. The first branch in a group that hasn't been skipped (see branch())
 * is included by default (if $defaultBranch = true).
 *
 * @var array
 * @access public
 */
	var $steps = array();
/**
 * Controller action that processes your step.
 *
 * @var string
 * @access public
 */
	var $wizardAction = 'wizard';
/**
 * Url to be redirected to after the wizard has been completed.
 * Controller::afterComplete() is called directly before redirection.
 *
 * @var mixed
 * @access public
 */
	var $completeUrl = '/';
/**
 * Url to be redirected to after 'Cancel' submit button has been pressed by user.
 *
 * @var mixed
 * @access public
 */
	var $cancelUrl = '/';
/**
 * If true, the first "non-skipped" branch in a group will be used if a branch has
 * not been included specifically.
 *
 * @var boolean
 * @access public
 */
	var $defaultBranch = true;
/**
 * If true, the user will not be allowed to edit previously completed steps. They will be
 * "locked down" to the current step.
 *
 * @var boolean
 * @access public
 */
	var $lockdown = false;
/**
 * Internal step tracking.
 *
 * @var string
 * @access protected
 */
	var $_currentStep = null;
/**
 * Holds the session key for data storage.
 *
 * @var string
 * @access protected
 */
	var $_sessionKey = null;
/**
 * Other session keys used.
 *
 * @var string
 * @access protected
 */
	var $_configKey = null;
	var $_branchKey = null;
/**
 * Holds the array based url for redirecting.
 *
 * @var array
 * @access protected
 */
	var $_wizardUrl = array();
/**
 * Other components used.
 *
 * @var array
 * @access public
 */
	var $components = array('Session');
/**
 * Initializes WizardComponent for use in the controller
 *
 * @param object $controller A reference to the instantiating controller object
 * @access public
 */
	function initialize(&$controller, $settings = array()) {
		$this->controller =& $controller;

		$this->_sessionKey	= $this->Session->check('Wizard.complete') ? 'Wizard.complete' : 'Wizard.' . $controller->name;
		$this->_configKey 	= 'Wizard.config';
		$this->_branchKey	= 'Wizard.branches.' . $controller->name;

		$this->_set($settings);
	}
/**
 * Component startup method.
 *
 * @param object $controller A reference to the instantiating controller object
 * @access public
 */
	function startup(&$controller) {
		$this->steps = $this->_parseSteps($this->steps);

		$this->config('wizardAction', $this->wizardAction);
		$this->config('steps', $this->steps);

		if (!in_array('Libs.Wizard', $this->controller->helpers) && !array_key_exists('Libs.Wizard', $this->controller->helpers)) {
			$this->controller->helpers[] = 'Libs.Wizard';
		}
	}
/**
 * Main Component method.
 *
 * @param string $step Name of step associated in $this->steps to be processed.
 * @access public
 */
	function process($step) {
		if (isset($this->controller->params['form']['Cancel'])) {
			if (method_exists($this->controller, '_beforeCancel')) {
				$this->controller->_beforeCancel($this->_getExpectedStep());
			}
			$this->reset();
			$this->controller->redirect($this->cancelUrl);
		}

		if (empty($step)) {
			if ($this->Session->check('Wizard.complete')) {
				if (method_exists($this->controller, '_afterComplete')) {
					$this->controller->_afterComplete();
				}
				$this->reset();
				$this->controller->redirect($this->completeUrl);
			}

			$this->autoReset = false;
		} elseif ($step == 'reset') {
			if (!$this->lockdown) {
				$this->reset();
			}
		} else {
			if ($this->_validStep($step)) {
				$this->_setCurrentStep($step);

				if (!empty($this->controller->data) && !isset($this->controller->params['form']['Previous'])) {
					$proceed = false;

					$processCallback = '_' . Inflector::variable('process_' . $this->_currentStep);
					if (method_exists($this->controller, $processCallback)) {
						$proceed = $this->controller->$processCallback();
					} elseif ($this->autoValidate) {
						$proceed = $this->_validateData();
					} else {
						trigger_error(sprintf(__('Process Callback not found. Please create Controller::%s', true), $processCallback), E_USER_WARNING);
					}

					if ($proceed) {
						$this->save();

						if (next($this->steps)) {
							if ($this->autoAdvance) {
								$this->redirect();
							}
							$this->redirect(current($this->steps));
						} else {
							$this->Session->write('Wizard.complete', $this->read());
							$this->reset();

							$this->controller->redirect($this->wizardAction);
						}
					}
				} elseif (isset($this->controller->params['form']['Previous']) && prev($this->steps)) {
					$this->redirect(current($this->steps));
				} elseif ($this->Session->check("$this->_sessionKey.$this->_currentStep")) {
					$this->controller->data = $this->read($this->_currentStep);
				}

				$prepareCallback = '_' . Inflector::variable('prepare_' . $this->_currentStep);
				if (method_exists($this->controller, $prepareCallback)) {
					$this->controller->$prepareCallback();
				}

				$this->config('activeStep', $this->_currentStep);
				return $this->controller->autoRender ? $this->controller->render($this->_currentStep) : true;
			} else {
				trigger_error(sprintf(__('Step validation: %s is not a valid step.', true), $step), E_USER_WARNING);
			}
		}

		if ($step != 'reset' && $this->autoReset) {
			$this->reset();
		}

		$this->redirect();
	}
/**
 * Selects a branch to be used in the steps array. The first branch in a group is included by default.
 *
 * @param string $name Branch name to be included in steps.
 * @param boolean $skip Branch will be skipped instead of included if true.
 * @access public
 */
	function branch($name, $skip = false) {
		$branches = array();

		if ($this->Session->check($this->_branchKey)) {
			$branches = $this->Session->read($this->_branchKey);
		}

		if (isset($branches[$name])) {
			unset($branches[$name]);
		}

		$value = $skip ? 'skip' : 'branch';
		$branches[$name] = $value;

		$this->Session->write($this->_branchKey, $branches);
	}
/**
 * Saves configuration details for use in WizardHelper or returns a config value.
 * This is method usually handled only by the component.
 *
 * @param string $name Name of configuration variable.
 * @param mixed $value Value to be stored.
 * @return mixed
 * @access public
 */
	function config($name, $value = null) {
		if ($value == null) {
			return $this->Session->read("$this->_configKey.$name");
		}
		$this->Session->write("$this->_configKey.$name", $value);
	}
/**
 * Get the data from the Session that has been stored by the WizardComponent.
 *
 * @param mixed $name The name of the session variable (or a path as sent to Set.extract)
 * @return mixed The value of the session variable
 * @access public
 */
	function read($key = null) {
		if ($key == null) {
			return $this->Session->read($this->_sessionKey);
		} else {
			$wizardData = $this->Session->read("$this->_sessionKey.$key");
			if (!empty($wizardData)) {
				return $wizardData;
			} else {
				return null;
			}
		}
	}
/**
 * Handles Wizard redirection. A null url will redirect to the "expected" step.
 *
 * @param string $step Stepname to be redirected to.
 * @param integer $status Optional HTTP status code (eg: 404)
 * @param boolean $exit If true, exit() will be called after the redirect
 * @see Controller::redirect()
 * @access public
 */
	function redirect($step = null, $status = null, $exit = true) {
		if ($step == null) {
			$step = $this->_getExpectedStep();
		}
		$url = array('controller' => strtolower($this->controller->name), 'action' => $this->wizardAction, $step);
		$this->controller->redirect($url, $status, $exit);
	}
/**
 * Resets the wizard by deleting the wizard session.
 *
 * @access public
 */
	function resetWizard() {
		$this->reset();
	}
/**
 * Resets the wizard by deleting the wizard session.
 *
 * @access public
 */
	function reset() {
		$this->Session->delete($this->_branchKey);
		$this->Session->delete($this->_sessionKey);
	}
/**
 * Saves the data from the current step into the Session.
 *
 * Please note: This is normally called automatically by the component after
 * a successful _processCallback, but can be called directly for advanced navigation purposes.
 *
 * @access public
 */
	function save() {
		$this->Session->write("$this->_sessionKey.$this->_currentStep", $this->controller->data);
	}
/**
 * Removes a branch from the steps array.
 *
 * @param string $branch Name of branch to be removed from steps array.
 * @access public
 */
	function unbranch($branch) {
		$this->Session->delete("$this->_branchKey.$branch");
	}
/**
 * Finds the first incomplete step (i.e. step data not saved in Session).
 *
 * @return string $step or false if complete
 * @access protected
 */
	function _getExpectedStep() {
		foreach ($this->steps as $step) {
			if (!$this->Session->check("$this->_sessionKey.$step")) {
				$this->config('expectedStep', $step);
				return $step;
			}
		}
		return false;
	}
/**
 * Saves configuration details for use in WizardHelper.
 *
 * @return mixed
 * @access protected
 */
	function _branchType($branch) {
		if ($this->Session->check("$this->_branchKey.$branch")) {
			return $this->Session->read("$this->_branchKey.$branch");
		}
		return false;
	}
/**
 * Parses the steps array by stripping off nested arrays not included in the branches
 * and returns a simple array with the correct steps.
 *
 * @param array $steps Array to be parsed for nested arrays and returned as simple array.
 * @return array
 * @access protected
 */
	function _parseSteps($steps) {
		$parsed = array();

		foreach ($steps as $key => $name) {
			if (is_array($name)) {
				foreach ($name as $branchName => $step) {
					$branchType = $this->_branchType($branchName);

					if ($branchType) {
						if ($branchType !== 'skip') {
							$branch = $branchName;
						}
					} elseif (empty($branch) && $this->defaultBranch) {
						$branch = $branchName;
					}
				}

				if (!empty($branch)) {
					if (is_array($name[$branch])) {
						$parsed = array_merge($parsed, $this->_parseSteps($name[$branch]));
					} else {
						$parsed[] = $name[$branch];
					}
				}
			} else {
				$parsed[] = $name;
			}
		}
		return $parsed;
	}
/**
 * Moves internal array pointer of $this->steps to $step and sets $this->_currentStep.
 *
 * @param $step Step to point to.
 * @access protected
 */
	function _setCurrentStep($step) {
		$this->_currentStep = reset($this->steps);

		while(current($this->steps) != $step) {
			$this->_currentStep = next($this->steps);
		}
	}
/**
 * Validates controller data with the correct model if the model is included in
 * the controller's uses array. This only occurs if $autoValidate = true and there
 * is no processCallback in the controller for the current step.
 *
 * @return boolean
 * @access protected
 */
	function _validateData() {
		$controller =& $this->controller;

		foreach ($controller->data as $model => $data) {
			if (in_array($model, $controller->uses)) {
				$controller->{$model}->set($data);

				if (!$controller->{$model}->validates()) {
					return false;
				}
			}
		}
		return true;
	}
/**
 * Validates the $step in two ways:
 *   1. Validates that the step exists in $this->steps array.
 *   2. Validates that the step is either before or exactly the expected step.
 *
 * @param $step Step to validate.
 * @return mixed
 * @access protected
 */
	function _validStep($step) {
		if (in_array($step, $this->steps)) {
			if ($this->lockdown) {
				return (array_search($step, $this->steps) == array_search($this->_getExpectedStep(), $this->steps));
			}
			return (array_search($step, $this->steps) <= array_search($this->_getExpectedStep(), $this->steps));
		}
		return false;
	}
}