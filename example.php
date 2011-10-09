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


// Working with alternate new tag syntax (based on $o parameter)
// Use the tag->o parameter to define the order inside tag construct methods, add an assoc.
// array as final variable to add any other tag parameters

// From class _a {
//	public $o = array('href','inner','title','target');
// }

$test = new _a('http://redcapmedia.com','Red Cap Media',NULL,'_self');
$test2 = new _a('http://redcapmedia.com','Red Cap Media',array('target'=>'_self'));

echo $test->make();

echo $test2->make();
// From class _div{
//	public $o = array('inner','id','class');
// }
$test = new _div('main','my_id');
$test2 = new _div('inner content','main',array('class'=>'myclass'));
// Inner content can also be another tag object.
$test3 = new _div(new _p('Here is a paragraphy'),'my_id');
// the 'old school verbose method' new constructions dont take new tag objects but should be fine for 
// inner defs.... yet..
//$test = new _div('inside of the div',array('class'=>'myclass','id'=>'myid'));

print_r($test);

echo $test->make();

echo $test2->make();

echo $test3->make();