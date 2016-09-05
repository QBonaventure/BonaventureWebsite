<?php
class Inner_Parser_Test extends Inner_Parser_Abstract {
	
private $_fromWiki	= array('#======\\s*(.+)#',
							'#=====\\s*(.+)#',
							'#====\\s*(.+)#',
							'#===\\s*(.+)#',
							'#==\\s*(.+)#',
							'#=\\s*(.+)#',

							'#\*\*(.+)#',
							'#\*(.+)#',
							'#</ul>(.?)<ul>#s',
							'#</li>(.?)(.?)<ul>#s',
							'#</ul>(.?)<ul>#s',

							'#\#\#(.+)#',
							'#\#(.+)#',
							'#</ol>(.?)<ol>#s',
							'#</li>(.?)(.?)<ol>#s',
							'#</ol>(.?)<ol>#s',
														
							'#\[\\s*(http://(?:.+))\\s*\\|\\s*(.+)\\s*\]#',
							
							'#//(.+)//#U',
							'#\'\'(.+)\'\'#U',
							'#__(.+)__#U',
							'#--(.+)--#U',
'#^((?!<h.>).+)$#m',
		'#<p>.</p>#',
		'#\\n|\\r#'
);

private $_toHTML	= array("<h6>$1</h6>",
							"<h5>$1</h5>",
							"<h4>$1</h4>",
							"<h3>$1</h3>",
							"<h2>$1</h2>",
							"<h1>$1</h1>",

							"<ul><ul><li>$1</li></ul></ul>",
							"<ul><li>$1</li></ul>",
							"",
							"<ul>",
							"",

							"<ol><ol><li>$1</li></ol></ol>",
							"<ol><li>$1</li></ol>",
							"",
							"<ol>",
							"\r\n",
							
							"<a href=\"$1\">$2</a>",
							
							"<b>$1</b>",
							"<i>$1</i>",
							"<u>$1</u>",
							"<s>$1</s>",
"<p>$1</p>",
"",
		"",
		"%"
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
	for($i = 0; $i < count($this->_fromWiki); $i++)
		$input	= preg_replace($this->_fromWiki[$i], $this->_toHTML[$i], $input);
	
	return $input;
}


}