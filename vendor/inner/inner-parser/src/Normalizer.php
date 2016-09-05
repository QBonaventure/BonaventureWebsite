<?php
class Inner_Parser_Normalizer extends Inner_Parser_Abstract {
	
private $_fromWiki	= array('#( (\?|!|:|;|»))#', 
							'#((«) )#');

private $_toHTML	= array("\xE2\x80\xAF$2",
							"$2\xE2\x80\xAF");
public $d	= '&#8239;';
public function normalize($input) {
	$input	= preg_replace($this->_fromWiki[0], $this->_toHTML[0], $input);
	$input	= preg_replace($this->_fromWiki[1], $this->_toHTML[1], $input);

	
	return $input;
}


}