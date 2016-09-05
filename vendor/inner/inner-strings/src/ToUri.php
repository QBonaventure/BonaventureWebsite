<?php

namespace Inner\Strings;

class ToUri {

	public function toUri($string) {
		$string		= str_replace('Â ', ' ', strtolower($string));
		
		$search		= array(':',   ',',  ';',  'Â«',   'Â»',  'â€™', '?', '!', 'â€¦', '.', '"',);
		$replace	= array('-',   '-',  '-',  '-',   '-',  '-');
		$string		= str_replace($search, $replace, $string);
	
		$search		= array(' ', '\'');
		$replace	= array('-', '-');
		$string		= str_replace($search, $replace, $string);
		
		while(strpos($string, '--') != false) {
			$string	= str_replace('--', '-', $string);
		}
		
		$string	= trim($string, '-');
		
		return urldecode($string);
	}

}