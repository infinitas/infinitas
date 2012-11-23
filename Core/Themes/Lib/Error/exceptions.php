<?php
/**
 * Themes exceptions
 *
 * @package Infinitas.Themes.Error
 */

/**
 * ThemesException
 *
 * @package Infinitas.Themes.Error
 */
class ThemesException extends InfinitasException {

}

/**
 * NoThemeConfiguredException
 *
 * @package Infinitas.Themes.Error
 */
class NoThemeConfiguredException extends ThemesException {
/**
 * Template string that has attributes sprintf()'ed into it.
 *
 * @var string
 */
	protected $_messageTemplate = 'There does not seem to be a theme configured.';

}

/**
 * NoThemeSelectedException
 *
 * @package Infinitas.Themes.Error
 */
class NoThemeSelectedException extends ThemesException {
/**
 * Template string that has attributes sprintf()'ed into it.
 *
 * @var string
 */
	protected $_messageTemplate = 'A theme type has not been selected.';

}

/**
 * ThemesConfigurationException
 *
 * @package Infinitas.Themes.Error
 */
class ThemesConfigurationException extends ThemesException {

}