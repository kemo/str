# Kohana 3 Str Helper
### Author: Kemal Delalic { twitter.com/delalick }

Instead of always repeating code like:

	$text = __('Something to translate');
	$text = HTML::chars($text);
	$text = HTML::smile($text);
	$text = nl2br($text);
	$text = Text::limit_words($text, 5);
	$text = HTML::bb($text);

	echo $text;

	$text = Inflector::plural($text);

	echo $text;

this class enables us to do the above with:

	$text = Str::factory(__('Something to translate'))
		->chars()
		->smile()
		->nl2br()
		->limit_words(5)
		->bb();
		
	echo $text;

	echo $text->plural();	// Inflector::plural()