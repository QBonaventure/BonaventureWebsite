<?php 
namespace Inner\Parser;


class Quotes {
	
static public function toHTML($attributes, $refId) {
	$ref 	= '<li id="'.$refId.'">';

	if(isset($attributes['url']) AND isset($attributes['website'])) {
		if(isset($attributes['authors'])) {
			$ref	.= self::getAuthorsString($attributes['authors']);
			$ref	.= ', &laquo;&nbsp'.$attributes['title'].'&nbsp;&raquo;';
			$ref	.= '. <i>'.$attributes['website'].'</i>.';
		}
		else {
			$ref	.= $attributes['website'];
			if(isset($attributes['title']))
				$ref	.= ', &laquo;&nbsp'.$attributes['title'].'&nbsp;&raquo;';
		}
		
		$ref	.= '<br />En ligne&nbsp;: <a href="'.$attributes['url'].'">Lien</a>.';
		if(isset($attributes['seen'])) {
			$date	= new Inner_View_Helper_FormatDate;
			$ref	.= 'Vu le <time datetime="'.$attributes['seen'].'">'.$date->formatDate($attributes['seen'], 'dd-MM-yyyy');
			if(strpos($attributes['seen'], ':'))
				$ref	.= $date->formatDate($attributes['seen'], ' à HH:mm');
			$ref	.= '</time>';
		}
	}

	if(isset($attributes['authors']) AND isset($attributes['book'])) {
		if(isset($attributes['authors']))
			$ref	.=	self::getAuthorsString($attributes['authors']);

// 		$ref	= rtrim($ref, '\xC2\xA0; ').'LLL';
// 		$ref	= trim($ref, '\xC2\xA0');

		if(isset($attributes['book']) AND isset($attributes['chapter'])) {
			$ref	.= '. &laquo;&nbsp;'.$attributes['chapter'].'&nbsp;&raquo;';
			$ref	.= ', in <i>'.$attributes['book'].'</i>. ';
		}
		else {
			$ref .= '. <i>'.$attributes['book'].'</i>. ';
		}

		if(isset($attributes['publisher'])) {
			$ref	.= $attributes['publisher'];
			if(isset($attributes['publishing-place']))
				$ref	.= '&nbsp;: '.$attributes['publishing-place'];
			$ref	.= ', ';
		}

		if(isset($attributes['year']))
			$ref	.= $attributes['year'].', ';

		if(isset($attributes['page'])) {
			if(strpos($attributes['page'], '-'))
				$ref	.= ' pp.&nbsp;'.$attributes['page'];
			else
				$ref	.= ' p.&nbsp;'.$attributes['page'];
		}

	}


	$ref	.= '.</li>';

	return $ref;
}


public static function getAuthorsString($rawAuthors) {
		$authors	= array();
		if(strpos($rawAuthors, ';')) {
			foreach(explode(';', $rawAuthors) as $author) {
				$authors[]	= trim($author);
			}
		}
		else
			$authors[]	= $rawAuthors;
		$ref	= '';
		foreach($authors as $author) {
			$author	= explode(',', $author);
			if(isset($author[1]))
				$ref	.= ' <span class="small-caps">'.trim($author[0]).'</span>, '.trim($author[1]).' ;';
			else
				$ref	.= ' <span class="small-caps">'.trim($author[0]).'</span> ;'; 
		}
	$ref	= str_replace(' ;', '&nbsp;;', trim($ref, ' ;'));
	return $ref;
}

}
?>