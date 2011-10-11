<?php
// a template class to build tags
// includes a few sample construction classes

// Allows syntax to be defined via $o array, and containers to wrap around the method arguments
// as $c (array)

// To create a new 'class template' simply make a new class that extends html5_template
// define  object parameters $o (an array with the names of the tags to accept as method
// arguments, and $c as an array that contains container tags to put all of the method arguments
// into

// To do - allow for designation of inner element attributes and etc.
// combine $o and $c into one param, allow designation of parameter location in $o

require('html5_core.php');

class html5_template{
	static function _(){
		$called_class = get_called_class();
		$container_class = '_'.$called_class::$c[0];
		foreach(func_get_args() as $loc=>$item)
			if($called_class::$o[$loc] && $item != NULL){
				$class_name = '_'.$called_class::$o[$loc];
				$li [] = new $class_name($item);
			}
		foreach($li as $item)
			$raw .= $item->make();
	
		$called_class_count = count(  $called_class::$c );
		if($called_class_count > 1)
		// processing any inner container items
			foreach( array_reverse($called_class::$c) as $loc=>$element)
				if($loc != $called_class_count-1){
					$class_name = '_'.$element;
					$raw = new $class_name($raw);
					}
	 return new $container_class($raw);
	}
}

class m_ul extends html5_template{
// these are the tags which are ordered in in which the syntax calls to this class will assemble
	public static $o = array('h5','strong','p');
// $c  containers (tags) in which to insert the above variables
	public static $c = array('ul','li');

}
// write a function in html5_template to process class tags based on classnames ?

class img_li extends html5_template{
	public static $o = array('p','h3','p');
	// $c refers to the containers in which to insert the above variables
	public static $c = array('li');
}