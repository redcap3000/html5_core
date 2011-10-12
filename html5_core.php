<?php

/* html5 core
	Ronaldo Barbachano, Oct. 2011
	A object oriented implementation of HTML 5, with json support. Always valid HTML ... 
	If you are familiar with json structure, then this will feel right at home...
	Define html pages/markup via objects and nested arrays, use the page object
	to help build pages... 
	
	Other less common /less supported tags included in html5_tags.php and adds
	around 300k of memory...	
*/
class page{public $head,$body;
	function stats(){
		return  "<em>Memory use: " . round(memory_get_usage() / 1024) . 'k'. "</em> <p><em>Load time : "
	. sprintf("%.4f", (((float) array_sum(explode(' ',microtime())))-$this->start_time)) . " seconds</em></p><p><em>Overhead memory : ".$this->oh_memory." k</em></p>";
	}

	function __construct($head=NULL,$body=NULL,$title=NULL,$b_at=NULL,$h_at=NULL){
		$this->start_time = (float) array_sum(explode(' ',microtime()));
		$this->oh_memory = round(memory_get_usage() / 1024);
	// Optional ..If you provide a title tag in your head you dont need it...
	// head attributes are rare... body attributes also kinda rare.. but provide assoc arrays
		if($title) $this->title = $title;
		if($$b_at) $this->b_at = $b_at;
		if($h_at) $this->h_at = $h_at;
		$args = func_get_args();
		// count the number of args to make sure it is no more than two
		if(is_string($args[0]) && !is_array($args[1]) && count($args) <= 2){
		// if true then load from path		
			return $this->load_json_page($args[0],( $args[1] == true?true:false));
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
		$result = new _html(array( new _head($this->head,$this->h_at) ,  new _body($this->body)) ,$this->b_at);
		echo $result->make();
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
	
		if(!$this->o){
		// maybe combine the a and global arrays to generate a better default syntax for each tag??
			if($this->a)
				$this->o = array_merge(array_keys($this->a),array_keys(html5_globals::$a));
			else
				$this->o = array_keys(html5_globals::$a);
			}
		if( ($arg_count > 1 && is_string($attr))  || ($arg_count >= 3 && is_string($attr . $tag) ) ){
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
		
		if($arg_count > count($this->o) + 1)
			return "\nToo many arguments ($arg_count) for $tag\n";
		
		if($arg_count > 0){
		// don't forget to validate class names etc..
			foreach($this->o as $loc=>$tag_name){
				if($args[$loc] && !is_array($args[$loc]) && !is_object($args[$loc])){
					if($tag_name == 'inner' && $args[$loc] != '')
						$this->in = $args[$loc];
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
	// switches the array to global if the class doesn't have it
		$array = ( $array == NULL ? ($this->a? $this->a: html5_globals::$a) : $array);
		if(is_array($array) && array_key_exists($param,$array))		
			return (is_array($array[$param]) && $value != NULL? (in_array($value,$array[$param])? true:false) : true);
		elseif(array_key_exists($param,html5_globals::$a))
			return $this->validate_param($param,$value,html5_globals::$a);
		else return false;
	}
	
	function make($inner=NULL,$a=NULL,$tag=null){
		if($tag == NULL) $tag = $this->t;
		if(is_array($this->in))
			foreach($this->in as $obj){
			// json decoded objects dont retain their classnames and become std object makking this statement not possible...
			// solution only store the values that are contained in whatever is passed into a new _a or new _whatever statement
				if(is_a($obj, 'stdClass')) $obj = json_core::std_to_tag($obj);
				$inner .= $obj->make();
			}
			
		else{
			if(is_a($obj, 'stdClass')) $obj = json_core::std_to_tag($obj);
			if($inner == NULL && $this->in)	$inner = $this->in;	
			// theres a problem with inner processing and the html attributes
			$inner =(is_object($this->in)? $this->in->make($this->in->inner,$this->in->at,$this->in->t) : ($inner!=NULL? $inner :  $this->in));
		}

		if($a == NULL){
			if($this->at) $a = $this->at;
			$this->a = ( $this->a? array_merge( $this->a,html5_globals::$a) : html5_globals::$a);
			// storing the a attributes in every tag is ineeficent ...
			}		
		
		if(is_array($a))
			foreach($a as $key=>$value){
				if((is_array($this->a[$key]) && array_key_exists($key, $this->a) && in_array($value, $this->a[$key]) )  ){
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
		if(!class_exists('json_core')){
			foreach($this as $key=>$item){
				unset($this->$key);
			}
		}else{
			unset($this->o);
			unset($this->a);
			
		}
		return "\n".   $delim ."<".$tag. ( $attr?" $attr":NULL). (in_array($tag,array('br','hr','link','meta'))?'/>' : ">$delim$inner$delim</$tag>" );
}
	}
	
	

class html5_globals{
// inner value isn't a html tag (i dont think) but used throughout the classes
// for handling inner values of tags
	public static $a = array(
						'inner'=>'',
						'class'=>'',
						'title'=>'',
						'id'=>'',
						'dir'=>array('ltr','rtl','auto'),
						'style'=>'',
						'accesskey'=>'',
						'contenteditable'=>array('true','false','inherit'),
						'contextmenu'=>'',
						'draggable'=>array('true','false','auto'),
						'dropzone'=>array('copy','move','link'),'hidden'=>'hidden',
						'lang'=>'',
						'spellcheck'=> array('true','false'),
						'tabindex'=>'');}
class _a extends tag{
	public $a = array('href' => '',
					  'hreflang'=>'',
					  'title'=>'',
					  'media'=>'',
					  'rel'=> array('alternate','author','bookmark','external','help','license','next','nofollow','noreferrer','prefetch','prev','search','sidebar','tag'),
					  'target'=>array('_blank','_parent','_self','_top','framename'),
					  'type'=>'MIME_type');
	// ideally $a could be used to keep track of order, but may create more problems
	// and unneeded complexity...				  
	public $o = array('href','inner','title','target');
	
	}
// these ones should have a shortened syntax for making b tags quickly?
class _b extends tag{}
class _blockquote extends tag{public $a = array ('cite'=>'');}
class _body extends tag{}
// has no end tag.. need to figure this out ?
class _br extends tag{}
class _div extends tag{
public $o = array('inner','id','class');
}
class _dl extends tag{}
class _dt extends tag{}
class _em extends tag{}
class _footer extends tag{}
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
class _img extends tag{	public $a = array('height'=>'','width'=>'','alt'=>'','ismap'=>'','usemap'=>'','src'=>'');}
// src and alt are required ... make a 'required' flag for these options ?
// value must be number... used only for <ol> lists
class _li extends tag{	public $a = array ('value'=>'');}
// to only appear in 'head' tag.. unsure how to implement ..
class _link extends tag{public $a = array (
									'href'=>'',
									'hreflang'=>'',
									'media'=>'',
									'rel'=> array('alternate','author','help','icon','licence','next','pingback','prefetch','prev','search','sidebar','stylesheet','tag'),
									'sizes'=>array('heightxwidth','any'),'type'=>'');
									}
// to only appear in 'head' tag.. unsure how to implement ..
class _meta extends tag{public $a = array (
									'charset'=>'',
									'content'=>'',
									'http-equiv'=> array('content-type','expires','refresh','set-cookie','others')
									,'name'=> array('author','description','keywords','generator','others'));
									}
// only supported in opera and chrome :/ come back and finish up
class _nav extends tag{}
class _noscript extends tag{}
class _ol extends tag{public $a = array ('reversed'=>'reversed','start'=>'','type'=>array('1','A','a','I','i'));}
class _p extends tag{public $a = array('name'=>'','value'=>'');	}
class _pre extends tag{}
//	Note: If the "src" attribute is present, the <script> element must be empty.
class _script extends tag{public $a= array('async'=>'async','defer'=>'defer','type'=>'','charset'=>'','src'=>'');	}
class _section extends tag{}
class _small extends tag{}
class _source extends tag{public $a= array('src'=>'','media'=>'','type'=>'MIME_type');}
class _span extends tag{}
class _strong extends tag{}
class _style extends tag{public $a= array('type'=>'text/css','media'=>'','scoped'=>'scoped');}
class _sub extends tag{}
// not sure wether to store as string or int...
class _table extends tag{public $a= array('border'=>'1');}
class _tbody extends tag{}
class _td extends tag{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'');}
class _tfoot extends tag{}
class _th extends tag{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'','scope'=> array('col','colgroup','row','rowgroup'));}
class _thead extends tag{}
class _title extends tag{}
class _tr extends tag{}
class _ul extends tag{}
class _var extends tag{}