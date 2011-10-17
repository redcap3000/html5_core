class page{public $head,$body;
// rewrite..
	function stats(){
		return  "<em>Memory use: " . round(memory_get_usage() / 1024) . 'k'. "</em> <p><em>Load time : "
	. sprintf("%.4f", (((float) array_sum(explode(' ',microtime())))-$this->start_time)) . " seconds</em></p><p><em>Overhead memory : ".$this->oh_memory." k</em></p>";
	}

	function __construct(){
	// make a new json only constructor ??? forget all the crap above... and accept a html object
	
	
		$this->start_time = (float) array_sum(explode(' ',microtime()));
		$this->oh_memory = round(memory_get_usage() / 1024);
		$args = func_get_args();
		//print_r($args);
		$args_count = count($args);
		
		
		
	// Optional ..If you provide a title tag in your head you dont need it...
	// head attributes are rare... body attributes also kinda rare.. but provide assoc arrays
		if($title) $this->title = $title;
		if($$b_at) $this->b_at = $b_at;
		if($h_at) $this->h_at = $h_at;
		// if args equals one then process json ??
	
		// count the number of args to make sure it is no more than two
		
		// to do for json - use orders inside of json structures and tag classes 
		// instead of array/key values- could save a significant amount of space!
		// and also create coded structures ... for a level of security?
		$this->head = $args[0][0];
		$this->body = $args[0][1];
	//	die(print_r($this));
	}
	
	
	function make_page($json=false){
	// pass in what you want to use for the html tag attributes as array in the first function,
	// and a page title for the second (if one is not provided inside $this->head)
		if($this->title != null)
			$this->head ['in'] []=  new _title($this->title);
		echo "<!doctype html>";
		$result = new _html(array($this->head, $this->body));
	//	die(print_r($result));
		echo $result->make();
		if(class_exists('json_core') && $json ==false)
		// avoid building this json structure if page was already loaded from json...
		{	
		// probably let make_page accept a json param
			unset($this->json);
			// just store this head and this body and maybe attributes ??
			// cleanup json from here ?
			$this->json = $result;
					
		}

		foreach($this as $key=>$value)
			if($key != 'json')
				unset($this->$key);
		
		}			
	
	
}