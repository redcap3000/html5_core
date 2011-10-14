<?php
// some of the less common  and wordy tags to reduce memory use 



class _abbr extends tag{}
class _address extends tag{}
// Some trickiness concerning quotes...
class _area extends tag{public $a = array(
						'alt' => '',
						'coords'=>'',
						'href'=>'',
						'hreflang'=>'',
						'media'=>'',
						'rel'=> array('alternate','author','bookmark','external','help','license','next','nofollow','noreferrer','prefetch','prev','search','sidebar','tag'),
						'shape'=> array('rect','rectangle','circ','circle','poly','polygon'),
						'target'=>array('_blank','_parent','_self','_top','framename'),'type'=>'MIME_type');
						}
class _article extends tag{}
class _aside extends tag{}
// Any text inside the between <audio> and </audio> will be displayed in browsers that does not support the audio element.
class _audio extends tag{public $a = array(
						'autoplay' => 'autoplay',
						'controls'=>'controls',
						'loop'=>'loop',
						'preload'=>array('auto','metadata','none'),
						'src'=>'');
						}


class _base extends tag{}
class _bdo extends tag{}
class _blockquote extends tag{public $a = array ('cite'=>'');}
class _button extends tag{
// to do if no $o is provided then we can get the top level keys for the global object and use that instead!
// allow for people to define empty keys for parameters whos values are identical
		public $a = array(
				'autofocus' => 'autofocus',
				'disabled'=>'disabled',
				'form'=>'',
				'formaction'=>'',
				'formenctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),
				'formmethod'=>array('get','post'),
				'formnovalidate'=>'formnovalidate',
				'formtarget'=>array('_blank','_parent','_self','_top','framename'),
				'name'=>'',
				'type'=>array('button','reset','submit'),
				'value'=>'');
}

class _canvas extends tag{public $a = array('height'=>'','width'=>'');}
class _caption extends tag{}
class _cite extends tag{}
class _col extends tag{public $a= array('span'=>'');}
class _colgroup extends tag{public $a= array('span'=>'');}
class _command extends tag{public $a= array(
								'checked'=>'checked',
								'disabled'=>'disabled',
								'icon'=>'',
								'radiogroup'=>'',
								'type'=>array('button','reset','submit'));
								}

// FF and opera ONLY
class _datalist extends tag{}
class _dd extends tag{}
class _del extends tag{public $a=  array ('cite'=>'','datetime'=>'');}
// CHROME Only
class _details extends tag{public $a= array('open'=>'open');}
class _dfn extends tag{}
class _dl extends tag{}
class _dt extends tag{}
class _embed extends tag{public $a = array(
			'height'=>'',
			'width'=>'',
			'type'=>'MIME_type',
			'src'=>'');
			}
class _fieldset extends tag{public $a= array('disabled'=>'disabled','form'=>'','name'=>'');}
class _figcaption extends tag{}
class _figure extends tag{}

class _form extends tag{public $a= array('accept-charset'=>'charset_list',
									'action'=>'','autocomplete'=>array('on','off'),
									'enctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),
									'method'=>array('get','post'),
									'name'=>'', 'novalidate'=>'novalidate',
									'target'=> array('_blank','_parent','_self','_top','framename'));
					}
					

// probably the most advanced #attr
class _iframe extends tag{public $a = array(
									'height'=>'',
									'width'=>'',
									'name'=>'',
									'sandbox'=>array('allow-forms','allow-same-origin','allow-scripts','allow-top-navigation'),
									'seamless'=>'seamless',
									'src'=>'','
									srcdoc'=>'');
							}

class _input extends tag{public $a = array(
									'accept'=>'MIME_type',
									'autocomplete'=>array('on','off') ,
									'autofocus' => 'autofocus',
									'checked'=>'checked',
									'disabled'=>'disabled',
									'form'=>'',
									'formaction'=>'',
									'formenctype'=>array('application/x-www-form-urlencoded','multipart/form-data','text/plain'),
									'formmethod'=>array('get','post'),
									'formnovalidate'=>'formnovalidate',
									'formtarget'=>array('_blank','_parent','_self','_top','framename'),'name'=>'',
									'type'=>array('button','checkbox','color','date', 'datetime','datetime-local', 'email','file','hidden','image','month', 'number', 'password','radio','range','reset','search','submit','tel','text','time', 'url','week'),
									'height'=>'',
									'list'=>'',
									'max'=>'',
									'maxlength'=>'',
									'min'=>'',
									'multiple'=>'multiple',
									'pattern'=>'regexp',
									'readonly'=>'readonly',
									'required'=>'required',
									'size'=>'',
									'step'=>'',
									'value'=>'',
									'width'=>'');
									}
class _ins extends tag{public $a = array('cite'=>'','datetime'=>'');}
// not supported in IE and Safari
// autofocus's only value is 'disabled' sounds fishy...

class _keygen extends tag{	public $a = array(
									'autofocus'=>'disabled',
									'challenge'=>'challenge',
									'disabled'=>'disabled',
									'form'=>'',
									'keytype'=> array('rsa','other'),'name'=>'');
									}
class _kbd extends tag{}
class _label extends tag{public $a = array('for'=>'','form'=>'');}
class _legend extends tag{}
class _map extends tag{public $a = array('name'=>'');}
class _mark extends tag{}
class _meter extends tag{}
class _noscript extends tag{}
class _object extends tag{public $a = array ('data'=>'','form'=>'','height'=>'','name'=>'','type'=>'MIME_Type', 'usemap'=>'', 'width'=>'');}
class _optgroup extends tag{public $a = array('label'=>'','disabled'=>'disabled');}
class _option extends tag{public $a = array('label'=>'','disabled'=>'disabled','selected'=>'selected','value'=>'');}
// only supported in opera
class _output extends tag{public $a = array('for'=>'','form'=>'','name'=>'');}

//No Support in IE or Safari
class _progress extends tag{public $a= array('max'=>'','value'=>'');}
class _q extends tag{public $a= array('cite'=>'');}
// The <rp> tag is used in ruby annotations, to define what to show if a browser does not support the ruby element.
class _rp extends tag{}
// The <rt> tag defines an explanation or pronunciation of characters (for East Asian typography).
class _rt extends tag{}
// The <ruby> tag specifies a ruby annotation (for East Asian typography).
class _ruby extends tag{}
class _s extends tag{}
class _samp extends tag{}
class _section extends tag{}
// Only supported in chrome
class _select extends tag{public $a = array('autofocus'=>'autofocus','disabled'=>'disabled','form'=>'','multiple'=>'','name'=>'','size'=>'');}
class _source extends tag{public $a= array('src'=>'','media'=>'','type'=>'MIME_type');}
class _summary extends tag{}
class _sup extends tag{}
class _sub extends tag{}


class _textarea extends tag{public $a= array(
										'autofocus'=>'autofocus',
										'cols'=>'',
										'disabled'=>'disabled',
										'dirname'=>'',
										'form'=>'',
										'maxlength'=>'',
										'name'=>'',
										'placeholder'=>'',
										'readonly'=>'readonly',
										'required'=>'required',
										'rows'=>'',
										'wrap'=>array('hard','soft'));
										}

class _time extends tag{public $a= array('datetime'=>'','pubdate'=>'pubdate');}
class _video extends tag{
	public $a= array(
						'audio'=>'muted',
						'autoplay'=>'autoplay',
						'controls'=>'controls',
						'height'=>'',
						'loop'=>'loop',
						'poster'=>'',
						'src'=>'',
						'preload'=> array('auto','metadata','none'),
						'width'=>'');
}
// Not supported in opera The <wbr> tag defines where in a word it would be ok to add a line-break.
class _wbr extends tag{}
class _var extends tag{}