<?php

// What self respecting web developer uses tables these days? Ok.. 
// I can forgive those of you displaying tabular data. So I've included table tags here

class _table extends tag{public $a= array('border'=>'1');}
class _tbody extends tag{}
class _td extends tag{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'');}
class _tr extends tag{}
class _tfoot extends tag{}
class _th extends tag{public $a= array('colspan'=>'','headers'=>'','rowspan'=>'','scope'=> array('col','colgroup','row','rowgroup'));}
class _thead extends tag{}