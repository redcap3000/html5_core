<?php


/* Walks through a typical web page, first defining it using a number of different
method construction arguments, creates the page with make(), then cleans it up with 
json_clean which creates a more efficient structure for HTML data.. (than just dumping the page
object directly)

Then you can load it back in with json_to_page .. (it doesnt have to be a page) and 
use make() again to display ..


*/
require('html5_core.php');
require('json_core.php');

$meta_description = 'HTML5 Core Json Example';

$content = 'Here is an example of html5 json i/o.';

$page = array( new _head(array(new _meta(NULL,array('charset'=>'utf-8')),
								new _meta (NULL,array('name'=>'description', 'content'=>$meta_description)),
								new _meta(NULL,array('name'=>'author', 'content'=>'Ronaldo Barbachano')),
								new _meta(NULL,array('name'=>'viewport', 'content'=>'width=device-width,initial-scale=1')),
								new _link(NULL,array('href'=>'http://fonts.googleapis.com/css?family=Geo', 'rel'=>'stylesheet' ,'type'=>'text/css')),
								new _link(NULL,array('rel'=>"stylesheet", 'href'=>'css/style.css')))), 
								
								new _body(
							
								
								new _div(
									array(
										new _header(
											array(
												new _h1('Ronaldo Barbachano'),
												new _nav(
													array(
														 new _a('who',array('href'=>'index.php')  ),
														 new _a('web.php','web','Web Projects'), 
														 new _a('open source',array('href'=>'foss.php')  )

														)
													)
											)
											),
										new _div( $content,
												array('id'=>'main','role'=>'main')
											),
										new _footer(
											new _h4("Ronaldo Barbachano 2011
			<a href='http://www.linkedin.com/in/ronaldob' title=Linkedin>Linked In</a>
			<a href='https://launchpad.net/%7Eronaldo-barbachano' title=Launchpad>Launchpad</a>
			<a href='https://github.com/redcap3000' title=github>Github</a>")
										)
										
										),array('id'=>'container')
								
								)));						

// create html object with page above
$the_page = new _html($page);

// make it
echo $the_page->make();

// Clean up the page for json storage..
$a= json_core::json_clean($the_page);

// Echo's out the string
echo json_encode($a,true);

// Read in 'clean' structure to json page
// Can also pass json as string to core..
$c = json_core::json_to_page($a);

// Make it again from json!
echo $c->make();

