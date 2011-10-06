html5 core
==========

Ronaldo Barbachano, October 2011

	
	
Why? Html is tedious. PHP programmers should not have to worry about slashes,
we have enough to worry about with semi colons parentheses and quotes... why not
work with html like any other object? Why not take it a step further and allow 
for tag parameter validation to avoid those difficult to detect html errors that 
can bring a php developer to tears while the front end guys are away.

HTML Core quickly turned from a way to map out html5, to a fast object oriented way
to define web pages. While writing straight html code may seem like 'faster' thing
to do, these classes could open up html editing capabilities to your web
application. And it makes more sense to store html data in json arrays that can be
searched, manipulated, and transformed into other formats, especially with the growing
popularity of NoSQL databases. Also I've found it easier to visualize html via
objects and arrays especially when working with php code. 

Changes to the HTML 5 specifications can be quickly implemented through the modification 
of a single associative array or the creation of new tag classes. 

How it works.
=============
Each tag is its own class, inside this class is a paramater $a which is an associative
array that contains either an empty string or another array with its' associated
key's valid entries, which are used for validation.

Next is a tag class that each tag extends that holds basic functions that perform
the validations above. If a parameter name does not exist in either the global 
allowed attributes array, or its own, then the attribute is not rendered inside the tag.

Also if the specific attribute contains defined values (in an additional array associated
with the attributes name) the value of the attribute must be in this list in order to 
be rendered.

This all happens after a tag is made and the make() function invoked.

Also included is the optional page class which assists users in creating full html 5 
compliant pages.

html 5 core is flexible.
========================
You may organize your page layouts into variables, or simply pass in arrays with nested new objects all 
inside of a single call.

Reference tag parameters, and valid settings inside its tag class.

	
Intended Use
============

*** Learning valid HTML 5

*** Creating a wysiwyg editor.

*** Storing html pages in a slightly more efficient manner using json

*** Using this core to render elements returned from a couchdb using a library such as couchCurl
	
Plans
=====

*** Self validation, self code corrections (when available)

*** Integration with couchCurl
