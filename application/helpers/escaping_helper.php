<?php
if (!function_exists('__')):
	/**
	 * Simple Character Escaping
	 *
	 * @param string $string 
	 * @return string
	 */
	function __($string) {
		$return = htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
		return $return;
	}
endif;