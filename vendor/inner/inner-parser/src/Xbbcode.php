<?php
class Inner_Parser_Xbbcode extends Inner_Parser_Abstract {
	
private $_fromXbbcode	= array('#\[h([1-6])\](.+)\[/h([1-6])\]#',
								'#\[ol\](.+)\[/ol\]#',
								'#\[li\](.+)\[/li\]#',
								'#\[b\](.+)\[/b\]#',
								'#\[i\](.+)\[/i\]#',
								'#\[u\](.+)\[/u\]#',
								'#\[s\](.+)\[/s\]#',
								'#\[a\](.+)\[/a\]#',
								'#\[quote\s?(?:id=([0-9]+)\s?|datetime=([0-9]+)\s?|author=(.+)(?:\s[a-z]+=)?){0,3}\](.+)\[/quote\]#U',
								'#\[img\s?(?:width=([0-9]+)\s?|height=([0-9]+)\s?|class=(.*)\s?){0,3}\](.+)\[/img]#',
		'#^(.+)$#m'
);

private $_toHTML	= array("<h$1>$2</h$3>",
							"<ul>$1</ul>",
							"<li>$1</li>",
							"<b>$1</b>",
							"<i>$1</i>",
							"<u>$1</u>",
							"<s>$1</s>",
							"<a href=\"$1\">$1</a>",
							"<div class=\"quote-message\"><div class=\"quote-header\">$3, le $2</div><div class=\"quote-content\">$4</div></div>",
							"<img src=\"$4\" width=\"$1\" height=\"$2\" class=\"$3\" />",
		"<p>$1</p>"
);




							
private $_titleLevels	= array('#======\\s*(.+)#',
								'#=====\\s*(.+)#',
								'#====\\s*(.+)#',
								'#===\\s*(.+)#',
								'#==\\s*(.+)#',
								'#=\\s*(.+)#');
private $_levelAdded	= array("$1",
								"=$1",
								"==$1",
								"===$1",
								"====$1",
								"=====$1",
								"======$1");
						
public function toHTML($input, $levelAdd = 0) {
	if($levelAdd > 0) {
		if($levelAdd > 6)
			throw new Inner_Parser_Exception('Second argument should should range from 0 to 6');
		else
			$input	= preg_replace(array_slice($this->_titleLevels, $levelAdd), $this->_levelAdded[$levelAdd], $input);
	}
	$input	= "\r\n".htmlentities($input, ENT_COMPAT, 'utf-8')."\r\n";

			
	for($i = 0; $i < count($this->_fromXbbcode); $i++)
		$input	= preg_replace($this->_fromXbbcode[$i], $this->_toHTML[$i], $input);


	return $input;
}


}