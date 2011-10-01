<?php

/* html5 core
	Ronaldo Barbachano, Oct. 2011
	A object oriented implementation of HTML 5, with json support.
*/

$html = new html5_core();

class html5_core{
	public $g_attr = array('accesskey'=>'','class'=>'','contenteditable'=>array('true','false','inherit'),'contextmenu'=>'','dir'=>array('ltr','rtl','auto'),'draggable'=>array('true','false','auto'),'dropzone'=>array('copy','move','link'),'hidden'=>'hidden','id'=>'','lang'=>'','spellcheck'=> array('true','false'),'style'=>'','tabindex'=>'','title'=>'','inner'=>'');
	public $open_tag;
	
	function __construct($json=NULL){
		$this->page = new page();
		if($json != NULL) $this->page = json_decode($json);
		$this->tag('meta','',array('charset'=>'utf-8'));
		$this->tag('link','',array('rel'=>'stylesheet','href'=>'main.css'));
		$this->tag('title','HTML5 Demos and Examples');
		$this->tag('p','Here is something');
		$this->tag('script','document.write("Hello World!")',array('type'=>'text/javascript'),0);
		$this->buildPage();		
	}

	function __store(){
		foreach($this->page as $key=>$value){
			if($value == NULL || $value =='')
				unset($this->page->$key);
		
		}
		// should probably store the object ... or allow a config option to store html or smaller structures.. if you're storing
		// full html values in arrays to be reassembled.. thats not very smart...
		if($this->page->head && $this->page->body) echo json_encode($this->page);
	
	}
	function build_tag($inner,$a=NULL,$tag=NULL){
		if($a != NULL){
			foreach($a as $key=>$value){
				// add other keys here if they do not require quotes
					if($key != 'charset')
						$attr .= "$key='$value' ";
					// some values wont need quotes...
					else
						$attr .= "$key=$value ";	
				}
		}	
		// self close a specific tags
		return "<$tag". ( $attr?" $attr":NULL). (in_array($tag,array('br','hr','link','meta'))?'/>' : ">$inner</$tag>" );
	
	}
	
	function buildPage(){
		echo '<!DOCTYPE html> 
<html>
	<head>';
	
		foreach($this->page2['head'] as $tag_name=>$values){
		if(count($values) == 1 && is_string($values[0]))
				echo "\n\t\t" . $this->build_tag($values[0],'',$tag_name);
			elseif(is_array($values[0]) && is_array($values[0][1]))
				echo "\n\t\t" . $this->build_tag($values[0][0],$values[0][1],$tag_name);
		}

echo "\n\t</head>\n\t<body>\n\t";
		foreach($this->page2['body'] as $tag_name => $values){
			if(count($values) == 1 && is_string($values[0]))
				echo "\n\t\t" . $this->build_tag($values[0],'',$tag_name);
			elseif(is_array($values[0]) && is_array($values[0][1]))
				echo "\n\t\t" . $this->build_tag($values[0][0],$values[0][1],$tag_name);
		}

		echo "\n\t</body>\n</html>";		
	}
	
	function tag($tag,$inner=NULL,$a=NULL,$to_head=NULL){
	// to head is for putting tags in the header (specifically for the script tag
		if($tag){
		// loads the tag attribute list.. could probably try to make this static so we dont have to call a new tag_class_name
			//$this->tag = $tag;
			$tag_class_name = '_'.$tag;
			$this->tag = new $tag_class_name;
			$this->a = $this->tag->a;
			//$this->tag = $tag;
			
		//	if($this->open_tag == 'open'){
		//		$this->page->body->$tag []= array($inner,$a);
		//	}
			
			}
		if($tag == 'meta' || $tag == 'link' || $tag == 'title' || $to_head == 1 ){
			$loc = 'head';
		}else{
			$loc = 'body';
		}
		if($a != NULL ){

			foreach($a as $attr=>$value){
				unset($stop);
				if( array_key_exists($attr,$this->a) && is_array($this->a[$attr]) && array_search($attr,$this->a)){
					$this->page2[$loc][$tag] []= array_shift(array_filter(array($inner,$a)));
						$stop = true;
				}
				if( array_key_exists($attr,$this->a)  && !$stop){
					$this->page2[$loc][$tag] []= array_filter(array($inner,$a));
				}			
			}
		}else{
			$this->page2[$loc][$tag] []= array_shift(array_filter(array($inner,$a)));
		}				
				
}
}
class page{public $head,$body;}
class _a{public $a = array('href' => '','hreflang'=>'','title'=>'','media'=>'','rel'=> array('alternate','author','bookmark','external','help','license','next','nofollow','noreferrer','prefetch','prev','search','sidebar','tag'),'target'=>array('_blank','_parent','_self','_top','framename'),'type'=>'MIME_type');}
class _abbr{}
class _address{}
// Some trickiness concerning quotes...
class _area{public $a = array('alt' => '','coords'=>'','href'=>'','hreflang'=>'','media'=>'','rel'=> array('alternate','author','bookmark','external','help','license','next','nofollow','noreferrer','prefetch','prev','search','sidebar','tag'),'shape'=> array('rect','rectangle','circ','circle','poly','polygon'),'target'=>array('_blank','_parent','_self','_top','framename'),'type'=>'MIME_type');}
class _article{}
class _aside{}
// Any text inside the between <audio> and </audio> will be displayed in browsers that does not support the audio element.
class _audio{public $a = array('autoplay' => 'autoplay','controls'=>'controls','loop'=>'loop','preload'=>array('auto','metadata','none'),'src'=>'');}
// these ones should have a shortened syntax for making b tags quickly?
class _b{}
class _base{}
class _bdo{}
class _blockquote{public $a = array ('cite'=>'');}
class _body{}
// has no end tag.. need to figure this out ?
class _br{}
class _button{
// allow for people to define empty keys for parameters whos values are identical
		public $a = array('autofocus' => 'autofocus','disabled'=>'disabled','form'=>'','formaction'=>'','formenctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),
				'formmethod'=>array('get','post'),'formnovalidate'=>'formnovalidate','formtarget'=>array('_blank','_parent','_self','_top','framename'),'name'=>'','type'=>array('button','reset','submit'),
				'value'=>'');
}

class _canvas{public $a = array('height'=>'','width'=>'');}
class _caption{}
class _cite{}
class _col{public $a= array('span'=>'');}
class _colgroup{public $a= array('span'=>'');}
class _command{public $a= array('checked'=>'checked','disabled'=>'disabled','icon'=>'','radiogroup'=>'','type'=>array('button','reset','submit'));}

// FF and opera ONLY
class _datalist{}
class _dd{}
class _del{public $a=  array ('cite'=>'','datetime'=>'');}
// CHROME Only
class _details{public $a= array('open'=>'open');}
class _dfn{}
class _div{}
class _dl{}
class _dt{}
class _em{}
class _embed{public $a = array('height'=>'','width'=>'','type'=>'MIME_type','src'=>'');}
class _fieldset{public $a= array('disabled'=>'disabled','form'=>'','name'=>'');}
class _figcaption{}
class _figure{}
class _footer{}
class _form{public $a= array('accept-charset'=>'charset_list','action'=>'','autocomplete'=>array('on','off'), 'enctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'), 'method'=>array('get','post'), 'name'=>'', 'novalidate'=>'novalidate', 'target'=> array('_blank','_parent','_self','_top','framename'));}
class _h1{}
class _h2{}
class _h3{}
class _h4{}
class _h5{}
class _h6{}
class _head{}
class _header{}
class _hgroup{}
// self closing
class _hr{}
class _html{public $a = array('manifest'=>'', 'xmlns'=> 'http://www.w3.org/1999/xhtml');}
class _i{}
class _iframe{public $a = array('height'=>'','width'=>'','name'=>'','sandbox'=>array('allow-forms','allow-same-origin','allow-scripts','allow-top-navigation'),'seamless'=>'seamless','src'=>'','srcdoc'=>'');}
class _img{	public $a = array('height'=>'','width'=>'','alt'=>'','ismap'=>'','usemap'=>'','src'=>'');}
// src and alt are required ... make a 'required' flag for these options ?
// probably the most advanced #attr
class _input{public $a = array('accept'=>'MIME_type','autocomplete'=>array('on','off') ,'autofocus' => 'autofocus','checked'=>'checked','disabled'=>'disabled','form'=>'','formaction'=>'','formenctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),'formmethod'=>array('get','post'),'formnovalidate'=>'formnovalidate','formtarget'=>array('_blank','_parent','_self','_top','framename'),'name'=>'',	'type'=>array('button','checkbox','color','date', 'datetime','datetime-local', 'email','file','hidden','image','month', 'number', 'password','radio','range','reset','search','submit','tel','text','time', 'url','week'),'height'=>'','list'=>'','max'=>'','maxlength'=>'','min'=>'','multiple'=>'multiple','pattern'=>'regexp','readonly'=>'readonly','required'=>'required','size'=>'','step'=>'','value'=>'','width'=>'');}
class _ins{public $a = array('cite'=>'','datetime'=>'');}
// not supported in IE and Safari
// autofocus's only value is 'disabled' sounds fishy...
class _keygen{	public $a = array('autofocus'=>'disabled','challenge'=>'challenge','disabled'=>'disabled','form'=>'','keytype'=> array('rsa','other'),'name'=>'');}
class _kbd{}
class _label{public $a = array('for'=>'','form'=>'');}
class _legend{}
// value must be number... used only for <ol> lists
class _li{	public $a = array ('value'=>'');}
// to only appear in 'head' tag.. unsure how to implement ..
class _link{public $a = array ('href'=>'','hreflang'=>'','media'=>'','rel'=> array('alternate','author','help','icon','licence','next','pingback','prefetch','prev','search','sidebar','stylesheet','tag'), 'sizes'=>array('heightxwidth','any'),'type'=>'');}
class _map{public $a = array('name'=>'');}
class _mark{}
// to only appear in 'head' tag.. unsure how to implement ..
class _meta{public $a = array ('charset'=>'','content'=>'', 'http-equiv'=> array('content-type','expires','refresh','set-cookie','others'), 'name'=> array('author','description','keywords','generator','others'));}
// only supported in opera and chrome :/ come back and finish up
class _meter{}
class _nav{}
class _noscript{}
class _object{public $a = array ('data'=>'','form'=>'','height'=>'','name'=>'','type'=>'MIME_Type', 'usemap'=>'', 'width'=>'');}
class _ol{public $a = array ('reversed'=>'reversed','start'=>'','type'=>array('1','A','a','I','i'));}
class _optgroup{public $a = array('label'=>'','disabled'=>'disabled');}
class _option{public $a = array('label'=>'','disabled'=>'disabled','selected'=>'selected','value'=>'');}
// only supported in opera
class _output{public $a = array('for'=>'','form'=>'','name'=>'');}
class _p{public $a = array('name'=>'','value'=>'');	}
class _pre{}
//No Support in IE or Safari
class _progress{public $a= array('max'=>'','value'=>'');}
class _q{public $a= array('cite'=>'');}
// The <rp> tag is used in ruby annotations, to define what to show if a browser does not support the ruby element.
class _rp{}
// The <rt> tag defines an explanation or pronunciation of characters (for East Asian typography).
class _rt{}
// The <ruby> tag specifies a ruby annotation (for East Asian typography).
class _ruby{}
class _s{}
class _samp{}
//	Note: If the "src" attribute is present, the <script> element must be empty.
class _script{public $a= array('async'=>'async','defer'=>'defer','type'=>'','charset'=>'','src'=>'');	}
class _select{public $a = array('autofocus'=>'autofocus','disabled'=>'disabled','form'=>'','multiple'=>'','name'=>'','size'=>'');}
class _section{}
class _small{}
class _source{public $a= array('src'=>'','media'=>'','type'=>'MIME_type');}
class _span{}
class _strong{}
class _style{public $a= array('type'=>'text/css','media'=>'','scoped'=>'scoped');}
class _sub{}
// Only supported in chrome
class _summary{}
class _sup{}
// not sure wether to store as string or int...
class _table{public $a= array('border'=>'1');}
class _tbody{}
class _td{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'');}
class _textarea{public $a= array('autofocus'=>'autofocus','cols','disabled'=>'disabled','dirname'=>'','form'=>'','maxlength'=>'','name'=>'','placeholder'=>'','readonly'=>'readonly','required'=>'required','rows'=>'','wrap'=>array('hard','soft'));}
class _tfoot{}
class _th{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'','scope'=> array('col','colgroup','row','rowgroup'));}
class _thead{}
class _time{public $a= array('datetime'=>'','pubdate'=>'pubdate');}
class _title{}
class _tr{}
class _ul{}
class _var{}
class _video{
	public $a= array('audio'=>'muted','autoplay'=>'autoplay','controls'=>'controls','height'=>'','loop'=>'loop','poster'=>'','src'=>'', 'preload'=> array('auto','metadata','none'),'width'=>'');
}
// Not supported in opera The <wbr> tag defines where in a word it would be ok to add a line-break.
class _wbr{}
