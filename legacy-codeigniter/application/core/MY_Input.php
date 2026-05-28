<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class MY_Input extends CI_Input
{
	function _clean_input_keys($str)
	{
		$config = &get_config('config');
		if ( ! preg_match("/^[".$config['permitted_uri_chars']."]+$/i", rawurlencode($str)))
		{
			exit('Disallowed Key Characters.');
		}
	
		// Clean UTF-8 if supported
		if (UTF8_ENABLED === TRUE)
		{
			$str = $this->uni->clean_string($str);
		}
	
		return $str;
	}
	
}