<?php
// Class for json functions.. Probably will put the couch class here too.
class json_core{
	
	public static function json_clean($object){
	// basically returns an array given an object ? and does the 't' tag processing
	// to give a cleaner json structure
//	print_r($object);
		if(is_object($object)){
		// process the variables we want on top all the time ...
		// 'tag'... and maaybbee 'inner' ..
			$r [0] = $object->t;
			unset($object->t);
			foreach($object as $x=>$y){
			// better way to do this ?, without reordering the object.. prolly not :(
				if(is_string($y)){
					$r[]="$x:$y";
					unset($object->$x);
				}
			}
			
			foreach($object as $x=>$y){
				if($x == 't'){
					$r [0]=$y;
					unset($y);
				}elseif($x == 'in' && is_array($y)){
				
					foreach($y as $iK =>$iV){
					// flips the order ... hmph
						$r[]= self::json_clean($iV);
						//$object[$x] [] =self::json_clean($iV); 
					}
				
				}elseif(is_object($y)){
					$r[0] = ltrim(get_class($object),'_');
					$r [] = self::json_clean($y);
				}elseif(is_string($y) && $x = 'in'){
					$r[] = "in:$y";
				}
			
			}
		}
		elseif(is_array($object)){
			foreach($object as $x=>$y){
				if($y->in){
					if(is_array($y->in)){
						foreach($y->in as $inKey=>$inVal){
// this may need some testing...
							$r [0] = $inVal->t;

							$r [] =self::json_clean($inVal);
							//unset($intVal->t);
							
							unset($y->t);
							$r []=self::json_clean($y);
							}
							
					}elseif(is_string($y->in)){
					
						$r[]= 'in:'.$y->in;
					}
					unset($y->in); 
				}else{
					unset($y->t);
				}
				unset($y->t);
				$r []=self::json_clean($y);
			}
		
		}
		return $r;
		
	
	}
	
	// because json wont store the name of an object class, when reading a json object 
	// back in use a t parameter as the objects class (or tag)
	
	public static function json_to_page($json){
		if(!is_array($json) && is_string($json)) $json = json_decode($json,true);
		return self::ar_to_tag($json);
	}
	
	private static function ar_to_tag($array){
		$tag = '_'. $array[0];
		foreach($array as $loc=>$val){
			if($loc != 0){
				if(is_array($val)){
					$r ['in'][]= self::ar_to_tag($val);
				}else{
					$temp = explode(':',$val,2);
					$r [$temp[0]]= $temp[1];
					unset($temp);
				}
			}
		}
		if($tag != '_')
		return new $tag(NULL,$r);
		else return $r;
	}
}