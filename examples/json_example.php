<?php
require('html5_core.php');

// Super easy example. Could go a step further and remove all attribute names and
// track them based on the position in the a parameters's lists 
$json = '{"head":[{"tag_name":"meta","at":{"charset":"utf-8"}},{"tag_name":"meta","at":{"name":"description","content":"Web site portfolio for open source developer, designer, Ronaldo Barbachano."}},{"tag_name":"meta","at":{"name":"author","content":"Ronaldo Barbacahno"}},{"tag_name":"meta","at":{"name":"viewport","content":"width=device-width,initial-scale=1"}},{"tag_name":"link","at":{"href":"http:\/\/fonts.googleapis.com\/css?family=Geo","rel":"stylesheet","type":"text\/css"}},{"tag_name":"link","at":{"rel":"stylesheet","href":"css\/style.css"}}],"body":[{"in":[{"in":[{"in":"Ronaldo Barbachano","tag_name":"h1"},{"in":[{"in":"web","tag_name":"a","at":{"href":"web.html"}},{"in":"design","tag_name":"a","at":{"href":"design.html"}},{"in":"video","tag_name":"a","at":{"href":"video.html"}},{"in":"sound","tag_name":"a","at":{"href":"sound.html"}},{"in":"open source","tag_name":"a","at":{"href":"foss.html"}},{"in":"contact","tag_name":"a","at":{"href":"contact.html"}}],"tag_name":"nav"}],"tag_name":"header"},{"in":[{"in":"What I <em>Do<\/em>","tag_name":"h2"},{"in":"Not Much","tag_name":"h3"},{"in":"I am Ronaldo Barbachano and a LAMP developer. I specifically design database-driven web applications using PHP and MySQL. I have expertise in PHP, JavaScript, CSS, HTML, MySQL (query writing). I am the developer of framework <a href=\"http:\/\/myparse.org\">myparse<\/a> and Wordpress display engine <a href=\"http:\/\/www.ikipress.org\">ikipress.<\/a>","tag_name":"p"},{"in":"I enjoy adapting existing open source technologies to fit the specific needs of a client. My sites strive to be standards compliant, fast loading, and logically organized.","tag_name":"p"},{"in":"I also create music on the side, for fun, and have had a background creating experimental video, and in graphic design and photography","tag_name":"p"}],"tag_name":"div","at":{"id":"main","role":"main"}},{"in":{"in":"Ronaldo Barbachano 2011","tag_name":"h4"},"tag_name":"footer"}],"tag_name":"div","at":{"id":"container"}}]}';

$page = new page($json,true);
print_r($page);


//$page = $page->load_json_page($json);
			
//echo			$page->json_page();