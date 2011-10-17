<?php
// define wether to use single or double quotes where needed for tag attributes
define ('AT_DOUBLE',false);
// turn off if performance is an issue,removes quotes for attributes that dont need them
define ('AT_QUOTE_CHECK',true);

class tag{
function __construct($inner='',$attr=NULL,$tag=NULL){
/*
	// inner refers to the data between the tags, parent child refers to another object ...
	// make option to return built tag on construction ?
	// at is for attribute, and in is for inner, was careful to pick 2 letter keys
	// for space, also these are not tags so it should be less confusing than 'i' and 'a'
	// , or '0' and '3'
	
	*/
	
	$arg_count = func_num_args();
	if(!$this->o)
	// maybe combine the a and global arrays to generate a better default syntax for each tag??
		// auto load the global class if it does not exist ?
		$this->o = ($this->a?array_merge(array_keys($this->a),array_keys(html5_globals::$a)) :$this->o = array_keys(html5_globals::$a) );
	
	if( ($arg_count > 1 && is_string($attr))  || ($arg_count >= 3 && is_string($attr . $tag) ) ){
		$this->t =  ltrim(get_called_class(),'_');
		$this->do_arg(func_get_args());
	}else{
	// for normal syntax calls, also builds objects from json and properly selects
	// tag class
		if($inner != '' && is_string($inner)) $this->in = $inner;
		elseif($inner != '' && is_array($inner) && $arg_count == 1){
			foreach($inner as $loc=>$obj){
				$this->in []= $obj;
			}
		}elseif(is_object($inner)){
		// may not need to add an extra element here... 
			$this->in []= $inner;
		
		}else{
			if($tag == NULL && !$this->t)
				$this->t =  ltrim(get_called_class(),'_');
			elseif($tag != NULL && !$this->t)
			// for loading from a json object
				$this->t = $tag;
			if(is_array($attr) && !is_array($inner)){ 
				foreach($attr as $k=>$v){
					$this->$k =$v;
				}
			}elseif(is_array($attr) && is_array($inner)){
			// if we pass an array of tag objects as the inner value and we have an assoc array of 
			// tag parameters/values
				$this->in = $inner;
				foreach($attr as $k=>$v)
					$this->$k = $v;
					}
		}
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
						$this->$tag_name= $args[$loc];
				}elseif(is_object($args[$loc]) && $tag_name == 'inner'){
					// process the object like any other tag ? store to inner ?
					$this->in = $args[$loc];
				}
				elseif(is_array($args[$arg_count-1])){
					foreach($args[$arg_count-1] as $key=>$value){
						$att_name = $this->o[$key];
						if($this->validate_param($key,$value))
							$this->$key = $value;
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
		if($tag == NULL && $this->t) $tag = $this->t;
		else{
			$tag = ltrim(get_called_class(),'_');
		}
		if(is_array($this->in))
			foreach($this->in as $obj){
				if(is_object($obj))
					$inner .= $obj->make();
				elseif(is_string($obj)){
					$inner .= $obj;
					}
			}
			
		else{
			if($inner == NULL && $this->in)	$inner = $this->in;	
			$inner =(is_object($this->in)? $this->in->make($this->in->inner,$this->in->at,$this->in->t) : ($inner!=NULL? $inner :  $this->in));
		}

		if($a == NULL){
			//if($this->at) $a = $this->at;
			$this->a = ( $this->a? array_merge( $this->a,html5_globals::$a) : html5_globals::$a);
			// storing the a attributes in every tag is ineeficent ...
			}		
		
		if( (count( get_class_vars('_'.$this->t) ) + 1) != count(get_object_vars($this) )){
			foreach($this as $key=>$value){
				unset($to_quote);
				if($value && $this->validate_param($key,$value) ){
				
				// quoting is tricky.. but the ATT_QUOTES definition is a step in the right
				// direction .. 
 					if(AT_QUOTE_CHECK == true){
						foreach(array(' ','=','>','"',"'") as $char)
							if( strrpos($value,$char) !== false ){
									$to_quote = true;
									break;
								}
					}else
						$to_quote = true;
					$q = ( AT_DOUBLE  ? '"' : "'");
					$attr .= ($to_quote  && $key != 'charset'?" $key=$q$value$q":" $key=$value");
				}
			}
		}
		
		if(!class_exists('json_core')){
			foreach($this as $key=>$item){
				unset($this->$key);
			}
		}else{
			unset($this->o);
			unset($this->a);
		}
		// force everything to have the 't' variable for json processing...
		$this->t = $tag;
		// delimiter... for tabs/new lines
		$d = (!in_array($tag,array('body','head','html')) ?"  ":" ");
		return "\n$d<".  $tag. ( $attr?"$attr":NULL). (in_array($tag,array('br','hr','link','meta','img'))?">\n$d" : ">$inner</$tag>\n$d" );
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
class _body extends tag{}
// has no end tag.. need to figure this out ?
class _br extends tag{}
class _div extends tag{
public $o = array('inner','id','class');
}
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
class _img extends tag{
	public $a = array('height'=>'','width'=>'','alt'=>'','ismap'=>'','usemap'=>'','src'=>'');
	public $o = array('src','alt','inner');
	}
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
class _meta extends tag{public $a = array ('charset'=>'','content'=>'','http-equiv'=> array('content-type','expires','refresh','set-cookie','others'),'name'=> array('author','description','keywords','generator','others'));
						public $o = array('name','content','inner');}
// only supported in opera and chrome :/ come back and finish up
class _nav extends tag{}
class _ol extends tag{public $a = array ('reversed'=>'reversed','start'=>'','type'=>array('1','A','a','I','i'));}
class _p extends tag{public $a = array('name'=>'','value'=>'');	}
class _pre extends tag{}
//	Note: If the "src" attribute is present, the <script> element must be empty.
class _script extends tag{public $a= array('async'=>'async','defer'=>'defer','type'=>'','charset'=>'','src'=>'');	}
class _small extends tag{}
class _span extends tag{}
class _strong extends tag{}
class _style extends tag{public $a= array('type'=>'text/css','media'=>'','scoped'=>'scoped');}
// not sure wether to store as string or int...
class _ul extends tag{}