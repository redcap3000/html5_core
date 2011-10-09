<?php

/* html5 core
	Ronaldo Barbachano, Oct. 2011
	A object oriented implementation of HTML 5, with json support. Always valid HTML ... 
	If you are familiar with json structure, then this will feel right at home...
	Define html pages/markup via objects and nested arrays, use the page object
	to help build pages... use json_page() after make has been invoked...
	also use load_json_page() to echo out a json encoded string.
	
*/


class page{public $head,$body;
	function __construct($head=NULL,$body=NULL,$title=NULL,$b_at=NULL,$h_at=NULL){
	// these are totally optional. If you provide a title tag in your head you dont need it...
	// head attributes are rare... body attributes also kinda rare.. but provide assoc arrays
		if($title) $this->title = $title;
		if($$b_at) $this->b_at = $b_at;
		if($h_at) $this->h_at = $h_at;
		$args = func_get_args();
		if(is_string($args[0]) && $args[1] == true && !is_array($args[1])){		
			return $this->load_json_page($args[0]);
		}
		$this->head = $head;
		$this->body = $body;
	}
	
	function make_page(){
	// pass in what you want to use for the html tag attributes as array in the first function,
	// and a page title for the second (if one is not provided inside $this->head)
		if($this->title != null)
			$this->head []=  new _title($this->title);
		echo "<!doctype html>";
		// echos the head directly
		$result = new _html(array( new _head($this->head,$this->h_at) ,  new _body($this->body)) ,$this->b_at);
		echo $result->make();
	}
	
	function json_page(){
	// to do make json smaller by removing a lot of the key names for 't' and 'at' .. and 
	// turning into simple numerically indexed arrays
	// could create a dynamic json registry object as we run make and unset the old values 	
		return json_encode($this);
	}
	
	public function load_json_page($json,$from_file=false){
		if($from_file != false)
			// attempt to load the json as a file path
			$json = file_get_contents($json);		
		
		$json = json_decode($json);
		
		if(is_object($json)){
		// what about html head and title attributes ??
			$this->head = $json->head;
			$this->body = $json->body;
		
			$this->make_page(($this->h_at? $this->ht_at : NULL), ($this->b_at ? $this->b_at :NULL) ,($this->title? $this->title : NULL) );
			// unset this_>he_at and the like to make it a more normal object incase it needs to be rewritten?
		}else{
		 echo "\nInvalid json, or json file path\n";
		}
	}
}

class tag{
	function __construct($inner='',$attr=NULL,$tag=NULL){
	// inner refers to the data between the tags, parent child refers to another object ...
	// make option to return built tag on construction ?
	// at is for attribute, and in is for inner, was careful to pick 2 letter keys
	// for space, also these are not tags so it should be less confusing than 'i' and 'a'
	// , or '0' and '3'
		$arg_count = func_num_args();
	
		if( ($this->o && $arg_count > 1 && is_string($attr))  || ($arg_count >= 3 && is_string($attr . $tag) ) ){
			$this->t =  ltrim(get_called_class(),'_');
			$this->do_arg(func_get_args());
		}else{
		// for normal syntax calls, also builds objects from json and properly selects
		// tag class
			if(is_array($attr)) $this->at = $attr;
			if($inner != '')$this->in = $inner;
			if($tag == NULL && !$this->t)
				$this->t =  ltrim(get_called_class(),'_');
			elseif($tag != NULL && !$this->t)
			// for loading from a json object
				$this->t = $tag;
		}
	}
	
	function do_arg($args){
		$arg_count = count($args);
		// this is if the developer wants to put in other parameters that are less common
		// and not needed for most tags, accepts assoc. array
		
		if($arg_count > count($this->o) + 1){
			return "\nToo many arguments ($arg_count) for $tag\n";
		}
		
		if($arg_count > 0){
		// don't forget to validate class names etc..
			foreach($this->o as $loc=>$tag_name){
				if($args[$loc] && !is_array($args[$loc]) && !is_object($args[$loc])){
					if($tag_name == 'inner' && $args[$loc] != ''){
						$this->in = $args[$loc];
						}
					elseif( $this->validate_param($tag_name,$value) )
						$this->at [$tag_name]= $args[$loc];
				}elseif(is_object($args[$loc]) && $tag_name == 'inner'){
					// process the object like any other tag ? store to inner ?
					$this->in = $args[$loc];
				}
				
				elseif(is_array($args[$arg_count-1])){
					foreach($args[$arg_count-1] as $key=>$value){
						$att_name = $this->o[$key];
						if($this->validate_param($key,$value))
							$this->at [$key] = $value;
					}
				}
			}		
		}
		return true;
	}
	
	function validate_param($param,$value=NULL,$array=NULL){
	// checks $this->a, or any other array passed to see if values exist
	// also checks the hthe html5 globals if not found in $array or $this->a
		if($array ==NULL) $array = $this->a;
		if(is_array($array) && array_key_exists($param,$array)){
			if(is_array($array[$param]) && $value != NULL)
				return (in_array($value,$array[$param])? true:false);
			else return true;		
		}elseif(array_key_exists($param,html5_globals::$a)){
			return $this->validate_param($param,$value,html5_globals::$a);
		}else return false;
	}
	
	function std_to_tag($obj){
		$new_class = '_'.$obj->t;
		if(is_object($obj->in))
		// convert std class into its class tag
			$obj->in = $this->std_to_tag($obj->in);
		if(is_object($obj->at)){
		// convert object into assoc. array
			foreach($obj->at as $x=>$y)
				$arr[$x]=$y;
			$obj->at = $arr;
			unset($arr);
		}
		// attributes are returned as an object instead of array :(
		return new $new_class($obj->in,$obj->at);
	}
	
	function make($inner=NULL,$a=NULL,$tag=null){
		if($tag == NULL) $tag = $this->t;
		if(is_array($this->in))
			foreach($this->in as $obj){
			// json decoded objects dont retain their classnames and become std object makking this statement not possible...
			// solution only store the values that are contained in whatever is passed into a new _a or new _whatever statement
				if(is_a($obj, 'stdClass')) $obj = $this->std_to_tag($obj);
				$inner .= $obj->make();
			}
			
		else{
			if(is_a($obj, 'stdClass')) $obj = $this->std_to_tag($obj);
			if($inner == NULL && $this->in)	$inner = $this->in;	
			$inner =(is_object($this->in)? $this->in->make($this->in->inner,$this->in->at,$this->in->t) : ($inner!=NULL? $inner :  $this->in));
		}

		if($a == NULL){
			if($this->at) $a = $this->at;
			$this->a = ( $this->a? array_merge( $this->a,html5_globals::$a) : html5_globals::$a);
			// storing the a attributes in every tag is ineeficent ...
			}		
		
		if(is_array($a))
			foreach($a as $key=>$value){
				if((is_array($this->a[$key]) && array_key_exists($key, $this->a) && array_search($value, $this->a[$key]) )  ){
				// add other keys here if they do not require quotes
					$attr .= "$key='$value' ";
				// validate values to see if it exists within the list						
				}elseif(array_key_exists($key,$this->a) && !is_array($this->a[$key])){
					$attr .= ($key != 'charset'?"$key='$value' ":"$key=$value ");
				// some values wont need quotes...						
				}
				$attr = trim($attr);
			// self close specific tags
			// use parent child and math to determine when where to write these tags?
		}
		// how do we keep track of tabs... yikes.. if tag name is not html or head or body we get a bunch ?
		// unsetting because of memory use.. probably attempt to unset tag name too and get by referring to classname
		unset($this->a);
		unset($this->o);
		return "\n".   $delim ."<".$tag. ( $attr?" $attr":NULL). (in_array($tag,array('br','hr','link','meta'))?'/>' : ">$delim$inner$delim</$tag>" );
	}
	}

class html5_globals{public static $a = array('accesskey'=>'','class'=>'','contenteditable'=>array('true','false','inherit'),'contextmenu'=>'','dir'=>array('ltr','rtl','auto'),'draggable'=>array('true','false','auto'),'dropzone'=>array('copy','move','link'),'hidden'=>'hidden','id'=>'','lang'=>'','spellcheck'=> array('true','false'),'style'=>'','tabindex'=>'','title'=>'','inner'=>'');}
class _a extends tag{
	public $a = array('href' => '','hreflang'=>'','title'=>'','media'=>'',
					  'rel'=> array('alternate','author','bookmark','external','help','license','next','nofollow','noreferrer','prefetch','prev','search','sidebar','tag'),
					  'target'=>array('_blank','_parent','_self','_top','framename'),'type'=>'MIME_type');
	// ideally $a could be used to keep track of order, but may create more problems
	// and unneeded complexity...				  
	public $o = array('href','inner','title','target');
	
	}
class _abbr extends tag{}
class _address extends tag{}
// Some trickiness concerning quotes...
class _area extends tag{public $a = array('alt' => '','coords'=>'','href'=>'','hreflang'=>'','media'=>'','rel'=> array('alternate','author','bookmark','external','help','license','next','nofollow','noreferrer','prefetch','prev','search','sidebar','tag'),'shape'=> array('rect','rectangle','circ','circle','poly','polygon'),'target'=>array('_blank','_parent','_self','_top','framename'),'type'=>'MIME_type');}
class _article extends tag{}
class _aside extends tag{}
// Any text inside the between <audio> and </audio> will be displayed in browsers that does not support the audio element.
class _audio extends tag{public $a = array('autoplay' => 'autoplay','controls'=>'controls','loop'=>'loop','preload'=>array('auto','metadata','none'),'src'=>'');}
// these ones should have a shortened syntax for making b tags quickly?
class _b extends tag{}
class _base extends tag{}
class _bdo extends tag{}
class _blockquote extends tag{public $a = array ('cite'=>'');}
class _body extends tag{}
// has no end tag.. need to figure this out ?
class _br extends tag{}
class _button extends tag{
// allow for people to define empty keys for parameters whos values are identical
		public $a = array('autofocus' => 'autofocus','disabled'=>'disabled','form'=>'','formaction'=>'','formenctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),
				'formmethod'=>array('get','post'),'formnovalidate'=>'formnovalidate','formtarget'=>array('_blank','_parent','_self','_top','framename'),'name'=>'','type'=>array('button','reset','submit'),
				'value'=>'');
}

class _canvas extends tag{public $a = array('height'=>'','width'=>'');}
class _caption extends tag{}
class _cite extends tag{}
class _col extends tag{public $a= array('span'=>'');}
class _colgroup extends tag{public $a= array('span'=>'');}
class _command extends tag{public $a= array('checked'=>'checked','disabled'=>'disabled','icon'=>'','radiogroup'=>'','type'=>array('button','reset','submit'));}

// FF and opera ONLY
class _datalist extends tag{}
class _dd extends tag{}
class _del extends tag{public $a=  array ('cite'=>'','datetime'=>'');}
// CHROME Only
class _details extends tag{public $a= array('open'=>'open');}
class _dfn extends tag{}
class _div extends tag{
public $o = array('inner','id','class');
}
class _dl extends tag{}
class _dt extends tag{}
class _em extends tag{}
class _embed extends tag{public $a = array('height'=>'','width'=>'','type'=>'MIME_type','src'=>'');}
class _fieldset extends tag{public $a= array('disabled'=>'disabled','form'=>'','name'=>'');}
class _figcaption extends tag{}
class _figure extends tag{}
class _footer extends tag{}
class _form extends tag{public $a= array('accept-charset'=>'charset_list','action'=>'','autocomplete'=>array('on','off'), 'enctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'), 'method'=>array('get','post'), 'name'=>'', 'novalidate'=>'novalidate', 'target'=> array('_blank','_parent','_self','_top','framename'));}
class _h1 extends tag{}
class _h2 extends tag{}
class _h3 extends tag{}
class _h4 extends tag{}
class _h5 extends tag{}
class _h6 extends tag{}
class _head extends tag{}
class _header extends tag{}
class _hgroup extends tag{}
// self closing
class _hr extends tag{}

class _html extends tag{public $a = array('manifest'=>'', 'xmlns'=> 'http://www.w3.org/1999/xhtml');}
class _i extends tag{}
class _iframe extends tag{public $a = array('height'=>'','width'=>'','name'=>'','sandbox'=>array('allow-forms','allow-same-origin','allow-scripts','allow-top-navigation'),'seamless'=>'seamless','src'=>'','srcdoc'=>'');}
class _img extends tag{	public $a = array('height'=>'','width'=>'','alt'=>'','ismap'=>'','usemap'=>'','src'=>'');}
// src and alt are required ... make a 'required' flag for these options ?
// probably the most advanced #attr
class _input extends tag{public $a = array('accept'=>'MIME_type','autocomplete'=>array('on','off') ,'autofocus' => 'autofocus','checked'=>'checked','disabled'=>'disabled','form'=>'','formaction'=>'','formenctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),'formmethod'=>array('get','post'),'formnovalidate'=>'formnovalidate','formtarget'=>array('_blank','_parent','_self','_top','framename'),'name'=>'',	'type'=>array('button','checkbox','color','date', 'datetime','datetime-local', 'email','file','hidden','image','month', 'number', 'password','radio','range','reset','search','submit','tel','text','time', 'url','week'),'height'=>'','list'=>'','max'=>'','maxlength'=>'','min'=>'','multiple'=>'multiple','pattern'=>'regexp','readonly'=>'readonly','required'=>'required','size'=>'','step'=>'','value'=>'','width'=>'');}
class _ins extends tag{public $a = array('cite'=>'','datetime'=>'');}
// not supported in IE and Safari
// autofocus's only value is 'disabled' sounds fishy...
class _keygen extends tag{	public $a = array('autofocus'=>'disabled','challenge'=>'challenge','disabled'=>'disabled','form'=>'','keytype'=> array('rsa','other'),'name'=>'');}
class _kbd extends tag{}
class _label extends tag{public $a = array('for'=>'','form'=>'');}
class _legend extends tag{}
// value must be number... used only for <ol> lists
class _li extends tag{	public $a = array ('value'=>'');}
// to only appear in 'head' tag.. unsure how to implement ..
class _link extends tag{public $a = array ('href'=>'','hreflang'=>'','media'=>'','rel'=> array('alternate','author','help','icon','licence','next','pingback','prefetch','prev','search','sidebar','stylesheet','tag'), 'sizes'=>array('heightxwidth','any'),'type'=>'');}
class _map extends tag{public $a = array('name'=>'');}
class _mark extends tag{}
// to only appear in 'head' tag.. unsure how to implement ..
class _meta extends tag{public $a = array ('charset'=>'','content'=>'', 'http-equiv'=> array('content-type','expires','refresh','set-cookie','others'), 'name'=> array('author','description','keywords','generator','others'));}
// only supported in opera and chrome :/ come back and finish up
class _meter extends tag{}
class _nav extends tag{}
class _noscript extends tag{}
class _object extends tag{public $a = array ('data'=>'','form'=>'','height'=>'','name'=>'','type'=>'MIME_Type', 'usemap'=>'', 'width'=>'');}
class _ol extends tag{public $a = array ('reversed'=>'reversed','start'=>'','type'=>array('1','A','a','I','i'));}
class _optgroup extends tag{public $a = array('label'=>'','disabled'=>'disabled');}
class _option extends tag{public $a = array('label'=>'','disabled'=>'disabled','selected'=>'selected','value'=>'');}
// only supported in opera
class _output extends tag{public $a = array('for'=>'','form'=>'','name'=>'');}
class _p extends tag{public $a = array('name'=>'','value'=>'');	}
class _pre extends tag{}
//No Support in IE or Safari
class _progress extends tag{public $a= array('max'=>'','value'=>'');}
class _q extends tag{public $a= array('cite'=>'');}
// The <rp> tag is used in ruby annotations, to define what to show if a browser does not support the ruby element.
class _rp extends tag{}
// The <rt> tag defines an explanation or pronunciation of characters (for East Asian typography).
class _rt extends tag{}
// The <ruby> tag specifies a ruby annotation (for East Asian typography).
class _ruby extends tag{}
class _s extends tag{}
class _samp extends tag{}
//	Note: If the "src" attribute is present, the <script> element must be empty.
class _script extends tag{public $a= array('async'=>'async','defer'=>'defer','type'=>'','charset'=>'','src'=>'');	}
class _select extends tag{public $a = array('autofocus'=>'autofocus','disabled'=>'disabled','form'=>'','multiple'=>'','name'=>'','size'=>'');}
class _section extends tag{}
class _small extends tag{}
class _source extends tag{public $a= array('src'=>'','media'=>'','type'=>'MIME_type');}
class _span extends tag{}
class _strong extends tag{}
class _style extends tag{public $a= array('type'=>'text/css','media'=>'','scoped'=>'scoped');}
class _sub extends tag{}
// Only supported in chrome
class _summary extends tag{}
class _sup extends tag{}
// not sure wether to store as string or int...
class _table extends tag{public $a= array('border'=>'1');}
class _tbody extends tag{}
class _td extends tag{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'');}
class _textarea extends tag{public $a= array('autofocus'=>'autofocus','cols','disabled'=>'disabled','dirname'=>'','form'=>'','maxlength'=>'','name'=>'','placeholder'=>'','readonly'=>'readonly','required'=>'required','rows'=>'','wrap'=>array('hard','soft'));}
class _tfoot extends tag{}
class _th extends tag{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'','scope'=> array('col','colgroup','row','rowgroup'));}
class _thead extends tag{}
class _time extends tag{public $a= array('datetime'=>'','pubdate'=>'pubdate');}
class _title extends tag{}
class _tr extends tag{}
class _ul extends tag{}
class _var extends tag{}
class _video extends tag{
	public $a= array('audio'=>'muted','autoplay'=>'autoplay','controls'=>'controls','height'=>'','loop'=>'loop','poster'=>'','src'=>'', 'preload'=> array('auto','metadata','none'),'width'=>'');
}
// Not supported in opera The <wbr> tag defines where in a word it would be ok to add a line-break.
class _wbr extends tag{}