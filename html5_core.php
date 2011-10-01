<?php

/* html5 core
	Ronaldo Barbachano, Oct. 2011
	A object oriented implementation of HTML 5, with json support.
*/

class html5_core{
	public $g_attr = array('accesskey'=>'','class'=>'','contenteditable'=>array('true','false','inherit'),'contextmenu'=>'','dir'=>array('ltr','rtl','auto'),'draggable'=>array('true','false','auto'),'dropzone'=>array('copy','move','link'),'hidden'=>'hidden','id'=>'','lang'=>'','spellcheck'=> array('true','false'),'style'=>'','tabindex'=>'','title'=>'','inner'=>'');
	
	function __construct($json=NULL){
		$this->page = new page();
		if($json != NULL) $this->page = json_decode($json);
		$this->page->head []=  $this->tag('meta','',array('charset'=>'utf-8'));
		$this->page->head []= $this->tag('link','',array('rel'=>'stylesheet','href'=>'main.css'));
		$this->page->head []= $this->tag('title','HTML5 Demos and Examples');
		$this->page->body []= $this->tag('p','Here is something');		
	}
	
	function __destruct(){
	// think this might work??
	// just run a foreach and process the loop from the terminal output as example, the only tags you really need to concern yourself with (in the head) are maybe script tags, link
	// and meta ... 
		echo $this->tag('html',"\n\t".
							$this->tag('head',
												"\n\t\t". implode((is_array($this->page->head)? $this->page->head : array($this->page->head)),"\n\t\t") . "\n\t"
										)."\n\t" . $this->tag('body',"\n\t\t".implode( (is_array($this->page->body)? $this->page->body : array($this->page->body)) ,"\n\t") . "\n\t") . "\n"
							);			
		self::__store();				
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

	function __add($a,$value,$a_arr=NULL){
		if(is_array($a_arr)){
			if(array_key_exists($a,$a_arr) || array_key_exists($a,$this->g_attr) ){
				if(is_array($a_arr[$a])){
					$check  = $a_arr->a;
					if(is_array($check) && in_array(trim(strtolower($value)),$check)) $this->$a = $value;
				}else{
				// still should do validation but i'll get around to it prolly...
					$this->$a = $value;
				}
			}
	}else
		$this->$a = $value;
	}
	function build_tag($inner ='',$a=NULL){
		if($this->a != NULL && $a != NULL){
			foreach($this->a as $key=>$value){
				if(is_array($value) && array_search($a[$key],$value))
						$this->__add($key,$a[$key]);
				elseif(array_key_exists($key,$a))
					$this->__add($key,$a[$key]);			
			
			}
		}
		$tag = $this->tag;
		if($inner == '' && $this->inner != '')
			$inner = $this->inner;
		// delete first char and thats your tag...
		foreach($this as $key=>$value){
		// you can do better...
			if(!is_array($value) && $key != 'inner' && $key != 'tag' && $key != 'attr' && $key != 'page' && $key != 'a' && $key != 'store'){
				if($key != 'charset'){
					$result .= "$key='$value' ";
				// some values wont need quotes...
				}else
					$result .= "$key=$value ";	
				// for cleanup for subsequent objects
				// doesnt store its location ...
				$tag = $this->tag;
				$this->store[][$tag] = array("$key"=>"$value");
				unset($this->$key);
			}
		}
		
		if($inner != '' && $inner != NULL)
			$this->store[][$tag] []=$inner;
		$result = trim($result);
		$tag_check = $this->tag;
		unset($this->tag);
		// this needs to go somewhere and be organized etc...
		// a closed tag wont have an inner so we can automatically not show it..
		return "<$tag". ($result? " $result":NULL). (in_array($tag_check,array('br','hr','link','meta'))?'/>' : ">$inner</$tag>" ); 
		
	}
	function tag($tag,$inner=NULL,$a=NULL){
		if(!$this->tag){
			$this->tag = $tag;
			$tag_class_name = '_'.$tag;
			$this->tag = new $tag_class_name;
			$this->a = $this->tag->a;
			$this->tag = $tag;
			}
		else
			return 'Current tag not closed. Please run build_tag() on object';
			// or begin to track inner tags ?

			return $this->build_tag($inner,$a);
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
class _script{public $a= array('async'=>'async','defer'=>'defer','type'=>'MIME_type','charset'=>'','src'=>'');	}
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
