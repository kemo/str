# PHP string object class
### Author: Kemal Delalic { twitter.com/delalick }

Instead of writing shit like this;

	$text = 'Something to translate';
	$text = strtr($text, $translation);
	$text = htmlspecialchars($text);
	$text = nl2br($text);
	echo $text;

these objects will allow this;

	echo (new Str('Something to translate'))
		->tr($translation)
		->chars()
		->nl2br();

Also, adding new string manipulation methods is easy.
