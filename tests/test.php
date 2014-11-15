<?php

include '../src/Kemo/Strings/MethodDescription.php';
include '../src/Kemo/Strings/MethodMap.php';
include '../src/Kemo/Strings/Str.php';

$str = new \Kemo\Strings\Str(' r testiraj ove noci pov j e ruj da je ljubav \\ ');

echo '<pre>';
echo $str.'<br/>';
echo $str->trim().'<br/>';
echo $str->replace(['ljubav' => 'kurac']).'<br/>';
echo $str->ireplace(['testiraj' => 'reskiraj']).'<br/>';
echo $str->pad(140, 'tuki').'<br/>';
echo $str->ltrim('r').'<br/>';
echo $str->ltrim(' ').'<br/>';
echo $str->rtrim('tuki\\ ').'<br/>';
echo $str->ireplace(['pov J E ruj' => 'kurac', 'povjeruj' => 'ljubav']).'<br/>';

echo '</pre>';

var_dump($str->values());
