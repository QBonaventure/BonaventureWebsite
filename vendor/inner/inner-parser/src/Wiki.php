<?php
namespace Inner\Parser;

use Inner\Parser\ParserAbstract;

class Wiki extends ParserAbstract {
	
private $_text = '';
private $_referencesList = array();
private $_notesList = array();
	
private $_fromWiki	= array('#\[(?:\\s*quote=&quot;(.+)&quot;\\s*|\\s*author=&quot;(.+)&quot;\\s*|\\s*url=&quot;(.+)&quot;\\s*){3}\]#U',
							'#\[(?:\\s*quote=&quot;(.+)&quot;\\s*|\\s*author=&quot;(.+)&quot;\\s*){2}\]#U',
							'#\[quote=&quot;(.+)&quot;\]#U',
							
							'#^======\\s*(.+)#m',
							'#^=====\\s*(.+)#m',
							'#^====\\s*(.+)#m',
							'#^===\\s*(.+)#m',
							'#^==\\s*(.+)#m',
							'#^=\\s*(.+)#m',

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

// 		'#\[code.+\].*\[/code\]#U',
// 		'#\[code:php\].*\[/code\]#Us',
							'#\[\\s*(http(?:s?)://(?:.+))\\s*\\|\\s*(.+)\\s*\]#U',
							'#^((?!<(h.|ul|li|ol|blockquote)>)(?!\{codeSnippet\}).{5,})$#m',
							'#\\n|\\r#',
							'#<p>.?</p>#'
);

private $_toHTML	= array("<blockquote>$1<footer><cite><a href=\"$3\">$2</a></cite></footer></blockquote>",
							"<blockquote>$1<footer><cite>$2</cite></footer></blockquote>",
							"<blockquote>$1</blockquote>",
		
							"<h6>$1</h6>",
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

// 		"<code>$2</code>",
							"<a href=\"$1\">$2</a>",
							"<p>$1</p>",
							"",
							"",
							""
);

private $_fromWikiSimpleText	= array('#(?<!:)//(.+)(?<!:)//#U',
										'#\'\'(.+)\'\'#U',
										'#__(.+)__#U',
										'#--(.+)--#U');

private $_toHTMLSimpleText	= array("<b>$1</b>",
									"<i>$1</i>",
									"<u>$1</u>",
									"<s>$1</s>");
						
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
						
public function toHTML($input, $onlySimple = false, $levelAdd = 0) {
// 	if($onlySimple == false) {
// 		$this->_toHTML		= array_merge($this->_toHTML, $this->_toHTMLSimpleText);
// 		$this->_fromWiki	= array_merge($this->_fromWiki, $this->_fromWikiSimpleText);
// 	}

	$input = preg_replace_callback('#\[code(?::(php|js))?\](.*)\[/code\]#Ums', 'self::test', $input);
	

	
	if($levelAdd > 0) {
		if($levelAdd > 6)
			throw new Inner_Parser_Exception('Second argument should should range from 0 to 6');
		else
			$input	= preg_replace(array_slice($this->_titleLevels, $levelAdd), $this->_levelAdded[$levelAdd], $input);
	}
	$input	= "\r\n".htmlentities($input, ENT_COMPAT, 'utf-8')."\r\n";
	
	if($onlySimple == false) {	
		for($i = 0; $i < count($this->_fromWiki); $i++)
			$input	= preg_replace($this->_fromWiki[$i], $this->_toHTML[$i], $input);
	}

	for($i = 0; $i < count($this->_fromWikiSimpleText); $i++)
		$input	= preg_replace($this->_fromWikiSimpleText[$i], $this->_toHTMLSimpleText[$i], $input);

	
	$this->_text	= $input;


	if(preg_match_all('#\[( *(?:cite=&quot;.*&quot;).*)\]#Us', $input, $output, PREG_SET_ORDER)) {
		$order	= 1;
		foreach($output as $out) {
			$this->_processQuotes($out[1], $out[0], $order);
			$order++;
		}
	}
	if(preg_match_all('#\[note=&quot;(.*)&quot;\]#Us', $input, $output, PREG_SET_ORDER)) {
		$order	= 1;
		foreach($output as $out) {
			$this->_processNotes($out[1], $out[0], $order);
			$order++;
		}
	}
	
	if(!empty($this->_referencesList) or !empty($this->_notesList)) {
		$footer	= '<footer>';
		
		if(!empty($this->_referencesList)) {
			$refList	= '<h2>Références</h2><ol id="post-references">';
			foreach($this->_referencesList as $reference) {
				$refList	.= $reference;
			}
			$refList	.= '</ol>';
					
			$footer	.= $refList;
		}
		if(!empty($this->_notesList)) {
			$notesList	= '<h2>Notes</h2><ol id="post-notes">';
			foreach($this->_notesList as $note) {
				$notesList	.= $note;
			}
			$notesList	.= '</ol>';
			$footer	.= $notesList;
		}
		
		
		
		$footer	.= '</footer>';
		
		$this->_text	.= $footer;
	}
	

	$this->_text = preg_replace_callback('#\{codeSnippet\}#', 'self::testthen', $this->_text);
	
	
	return $this->_text;
}

private function _processNotes($note, $wholeString, $order) {
	$noteId	= 'note-'.str_pad($order, 3, '0', STR_PAD_LEFT);

	$noteString	= '<sup><a href="#'.$noteId.'">[note '.$order.']</a></sup>';
	$this->_text	= str_replace($wholeString, $noteString, $this->_text);
	
	$this->_notesList[]	= '<li id='.$noteId.'>'.$note.'</li>';
}

private function _processQuotes($attributesString, $wholeString, $order) {
	preg_match_all('#(cite|year|authors|editors|seen|publisher|publishing-place|journal|issue|volume|title|page|type|url|website|book|chapter)=&quot;(.*)&quot;#U', $attributesString, $att, PREG_SET_ORDER);
	$refId	= 'ref-'.str_pad($order, 3, '0', STR_PAD_LEFT);
	$attributesArray	= array();
	foreach($att as $attribute) {
		$attributesArray[$attribute[1]]	= $attribute[2];
	}
	
	$quoteString	= $attributesArray['cite'];
	if($attributesArray['cite'] != null)
		$quoteString	= '<q>&laquo;&nbsp;'.$quoteString.'&nbsp;&raquo</q>';
	$quoteString	.= '<sup><a href="#'.$refId.'">['.$order.']</a></sup>';

	$this->_text	= str_replace($wholeString, $quoteString, $this->_text);
	$this->_referencesList[]	= Inner_Parser_Wiki_Quotes::toHTML($attributesArray, $refId);
// 	$this->_referencesList[]	= $this->_createReferenceString($attributesArray, $refId);
}

private $codeSnippets = array();

public function test($match) {
	$this->codeSnippets[]	= array('lang' => $match[1],
									'code' => $match[2]);
	return '{codeSnippet}';
}

public function testthen($match) {
// 	$this->codeSnippets[]	= $match[0];
	$codeSnippet	= array_shift($this->codeSnippets);
	if($codeSnippet['lang']	== 'php')
		$return	= highlight_string($codeSnippet['code'], true);
	else
		$return = '<code><pre>'.$codeSnippet['code'].'</pre></code>';
	$return	= preg_replace('#<br\W*?\/>#', '', $return, 1);
	return $return;
}




// private function _createReferenceString($attributes, $refId) {
// 	$ref 	= '<li id="'.$refId.'">';
	
	
	
// 	if(isset($attributes['url']) AND isset($attributes['website'])) {
// 		$ref	.= $attributes['website'];
// 		if(isset($attributes['title']))
// 			$ref	.= ', <i>'.$attributes['title'].'</i>.';
// 		$ref	.= '<br /><a href="'.$attributes['url'].'">Lien</a>';
// 		if(isset($attributes['seen'])) {
// 			$date	= new Inner_View_Helper_FormatDate;
// 			$ref	.= ', vu le <time datetime="'.$attributes['seen'].'">'.$date->formatDate($attributes['seen'], 'dd-MM-yyyy');
// 			if(strpos($attributes['seen'], ':'))
// 				$ref	.= $date->formatDate($attributes['seen'], ' à HH:mm');
// 			$ref	.= '</time>';
// 		}
// 	}
	
	
// 	if(isset($attributes['authors'])) {
// 		$authors	= array();
// 		if(strpos($attributes['authors'], ';')) {
// 			foreach(explode(';', $attributes['authors']) as $author) {
// 				$authors[]	= trim($author);
// 			}
// 		}
// 		else
// 			$authors[]	= $attributes['authors'];
// 		foreach($authors as $author) {
// 			$author	= explode(',', $author);
// 			$ref	.= '<span class="small-caps">'.trim($author[0]).'</span>, '.trim($author[1]).'&nbsp;; ';
// 		}
// 		$ref	= rtrim($ref, '\xC2\xA0; ');
// 		$ref	= trim($ref, '\xC2\xA0');
		
// 		if(isset($attributes['book']) AND isset($attributes['chapter'])) {
// 			$ref	.= '. &laquo;&nbsp;'.$attributes['chapter'].'&nbsp;&raquo;';
// 			$ref	.= ', in <i>'.$attributes['book'].'</i>. ';
// 		}
// 		else {
// 			$ref .= '. <i>'.$attributes['book'].'</i>. ';
// 		}
		
// 		if(isset($attributes['publisher'])) {
// 			$ref	.= $attributes['publisher'];
// 			if(isset($attributes['publishing-place']))
// 				$ref	.= '&nbsp;: '.$attributes['publishing-place'];
// 			$ref	.= ', ';
// 		}
		
// 		if(isset($attributes['year']))
// 			$ref	.= $attributes['year'].', ';
		
// 		if(isset($attributes['page'])) {
// 			if(strpos($attributes['page'], '-'))
// 				$ref	.= ' pp.&nbsp;'.$attributes['page'];
// 			else
// 				$ref	.= ' p.&nbsp;'.$attributes['page'];
// 		}

// 	}
	
	 
// 	$ref	.= '.</li>';
	
// 	return $ref;
// }


}