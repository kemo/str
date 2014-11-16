<?php
// because I'm too lazy to write real tests at the moment
if ( ! file_exists('../vendor/autoload.php'))
	throw new \Exception('Please run `composer install` first.');

require '../vendor/autoload.php';

$str = new \Kemo\Strings\Str('Some random string');
echo $str->concat('( this was appended) #1').PHP_EOL;
echo $str->concat('( this was appended) #2').PHP_EOL;
echo $str->concat('( this was appended #3)').PHP_EOL;
echo $str->undo(2).PHP_EOL;