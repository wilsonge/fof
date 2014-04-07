<?php

/**
 * Joomla will try load the application, too bad there isn't any because we're in CLI.
 * This is our empty version of JRoute
 */
class JRoute
{
	public static function _($url, $xhtml = true, $ssl = null)
	{
		return 'url-routed';
	}
}