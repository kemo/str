<?php
// because I'm too lazy to write real tests at this point

include '../src/Kemo/Strings/Str.php';
include '../src/Kemo/Strings/StrException.php';
include '../src/Kemo/Strings/MethodMap.php';
include '../src/Kemo/Strings/MethodDescription.php';

$str = new \Kemo\Strings\Str('Some random string');
echo $str->concat('( this was appended) #1').PHP_EOL;
echo $str->concat('( this was appended) #2').PHP_EOL;
echo $str->concat('( this was appended #3)').PHP_EOL;
echo $str->undo(2).PHP_EOL;