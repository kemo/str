# PHP string object class
### The missing PHP string library
[![Author](http://img.shields.io/badge/author-delalick-blue.svg)](http://twitter.com/delalick)

This will make living with PHP strings easier by;
- providing a simple chained API to string operations
- not mixing up the needle haystack stuff
- allowing you to extend and add your own methods in seconds

```php
$text = 'Something to translate';
$text = strtr($text, $translation);
$text = htmlspecialchars($text);
$text = nl2br($text);
echo $text;
```

Str objects allow you this;

```php
echo (new Str('Something to translate'))
	->tr($translation)
	->chars()
	->nl2br();
```
