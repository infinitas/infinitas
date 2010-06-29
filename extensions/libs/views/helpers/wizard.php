<?php
/**
 * Wizard helper by jaredhoyt.
 *
 * Creates links, outputs step numbers for views, and creates dynamic progress menu as the wizard is completed.
 *
 * PHP versions 4 and 5
 *
 * Comments and bug reports welcome at jaredhoyt AT gmail DOT com
 *
 * Licensed under The MIT License
 *
 * @writtenby		jaredhoyt
 * @lastmodified	Date: March 11, 2009
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class WizardHelper extends AppHelper {
	var $helpers = array('Session','Html');
	var $output = null;

/**
 *
 *
 * @param
 * @return
 */
	function config($key = null) {
		if ($key == null) {
			return $this->Session->read('Wizard.config');
		} else {
			$wizardData = $this->Session->read('Wizard.config.'.$key);
			if (!empty($wizardData)) {
				return $wizardData;
			} else {
				return null;
			}
		}
	}
/**
 *
 *
 * @param
 * @return
 */
	function link($title, $step = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		if ($step == null) {
			$step = $title;
		}
		$wizardAction = $this->config('wizardAction');

		return $this->Html->link($title, $wizardAction.$step, $htmlAttributes, $confirmMessage, $escapeTitle);
	}
/**
 *
 *
 * @param
 * @return
 */
	function stepNumber($step = null, $shiftIndex = 1) {
		if ($step == null) {
			$step = $this->config('activeStep');
		}

		$steps = $this->config('steps');

		if (in_array($step, $steps)) {
			return array_search($step, $steps) + $shiftIndex;
		} else {
			return false;
		}
	}

	function activeStep() {
		return $this->config('activeStep');
	}
/**
 *
 *
 * @param
 * @return
 */
	function progressMenu($titles = array(), $attributes = array(), $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		$wizardConfig = $this->config();
		extract($wizardConfig);

		$attributes = array_merge(array('wrap' => 'div'), $attributes);
		extract($attributes);

		$incomplete = null;

		foreach ($steps as $title => $step) {
			$title = empty($titles[$step]) ? $step : $titles[$step];

			if (!$incomplete) {
				if ($step == $expectedStep) {
					$incomplete = true;
					$class = 'expected';
				} else {
					$class = 'complete';
				}
				if ($step == $activeStep) {
					$class .= ' active';
				}
				$this->output .= "<$wrap class='$class'>" . $this->Html->link($title, array('action' => $wizardAction, $step), $htmlAttributes, $confirmMessage, $escapeTitle) . "</$wrap>";
			} else {
				$this->output .= "<$wrap class='incomplete'>" . $title . "</$wrap>";
			}
		}

		return $this->output;
	}
}