# PHP string object class
### Author: Kemal Delalic { twitter.com/delalick }

Instead of writing shit like this;

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
