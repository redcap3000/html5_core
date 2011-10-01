html5 core
==========

Ronaldo Barbachano, October 2011

	
An object oriented implementation of HTML 5, with json support.

This project started out of my want to learn HTML 5. So I begin to organize each tag as a class,
and created html5_core that validates most valid values and creates tags. Then added the __store function to 
create a json object that the core can use to reassemble itself.
	
Currently the implementation does not have rhobust support for inner elements (tags within tags).
	
Intended Use
============

*** Learning valid HTML 5

*** Creating a wysiwyg editor.

*** Storing html pages in a slightly more efficient manner using json

*** Using this core to render elements returned from a couchdb using a library such as couchCurl
	
Plans
=====

*** Self validation, self code corrections (when available)

*** Support for hierarchy (store the entire page object using DOM, or define parent child relationships/id's etc within object)

*** Integration with couchCurl
