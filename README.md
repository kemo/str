# Kohana 3 Str Helper
### Author: Kemal Delalic { twitter.com/delalick }

`
	echo $str = Str::factory("This is some text and kemal.delalic@gmail.com is some email")
		->chars()			// HTML::chars()
		->reduce_slashes()	// Text::reduce_slashes()
		->plural()			// Inflector::plural()
		->limit_words(4)	// Text::limit_words()
		->auto_p(); 		// HTML::auto_p();
`
