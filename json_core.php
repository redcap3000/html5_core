<?php
// Class for json functions.. Probably will put the couch class here too.
class json_core{
	public function load_page($json,$from_file=false){
		if($from_file != false)
			// attempt to load the json as a file path
			$json = file_get_contents($json);		
		$json = json_decode($json);
		return (is_object($json)? new page($json->head,$json->body,$json->title,$json->b_at,$json->h_at) :"\nInvalid json, or json file path\n");
	}
	// because json wont store the name of an object class, when reading a json object 
	// back in use a t parameter as the objects class (or tag)
	public static function std_to_tag($obj){
		$new_class = '_'.$obj->t;
		if(is_object($obj->in))
		// convert std class into its class tag
			$obj->in = self::std_to_tag($obj->in);
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

}