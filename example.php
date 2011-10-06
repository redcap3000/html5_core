<?php

require('html5_core.php');
// some examples

$para = 'here is a paragraph';
// making para into a simple tag bu defining an inner value
$para_tag = new _p($para);

// make another tag object, this time supplying second parameter, its attributes.
$para_tag = new _p($para,array('class'=>'aClass'));

// You may also pass another tag object, or even an array of tag objects,
// Illustrated below:


// Using the page object 

// When 'making a new page' first param /second can be an array of objects, or a string, 
// or a single object that defines what to put inside of the header (first param) and 
// footer (second), third param is the title of the page (you could also manually pass 
// a _title object as well

$page = new page(
			array(
					new _meta(NULL,array('charset'=>'utf-8')),
					new _meta(NULL,array('name'=>'description', 'content'=>'Description of this page.')),		
				 ),
			array(
					new _div(
						array(
							new _h1('Here is a header 1'),
							new _p($para)
							),array('id'=>'main')						
						)
					 )
			);
			
// the function make_page actually echos the page back to screen, provide an array with 
//the html's tag attributes as first param, the head tags params (if any), and the
// page title as the third parameter - all parameters are optional
$page->make_page(array('lang'=>'en'),NULL,'My Page');
// Now that the page is made you can get back the json... this is done to avoid writing the a parameter
// which only contains valid fields/values to the json object..
$json = $page->json_page();
// You can also import json  and display json... this will override page class variables 
$page->load_json_page($json);

// Working with the ph5_ class to easy shorthand...

$a_tag = ph5_::_a('http://www.redcapmedia.com','My Web site');
// We get a new a object ..
print_r($a_tag);

echo $a_tag->make();