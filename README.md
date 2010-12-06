# Kohana 3 Str Helper
### Author: Kemal Delalic { twitter.com/delalick }

Sick of this kind of code:

	$text = HTML::chars($topic->text);
	$text = HTML::smile($text);
	$text = nl2br($text);
	$text = Text::limit_words($text, 5);
	$text = HTML::bb($text);

	echo $text;

	$text = Inflector::plural($text);

	echo $text;

this class enables us to do all above with:

	$text = Str::factory($topic->text)
		->chars()
		->smile()
		->nl2br()
		->limit_words(5)
		->bb();
		
	echo $text;

	echo $text->plural();