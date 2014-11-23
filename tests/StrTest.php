<?php namespace Kemo\Strings;
/**
 * @coversDefaultClass \Kemo\Strings\Str
 * @author Kemal Delalic
 */
class StrTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers ::__call
     */
    public function testCallThrowsStrException()
    {
        $this->setExpectedException('\BadMethodCallException');

        $str = new Str('foo');
        $str->method_that_will_never_exists();
    }

    public function providerRenderValue()
    {
        return array(
            array('foo'),
            array('something going on'),
        );
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::value
     * @dataProvider providerRenderValue
     * @param        string $string
     */
    public function testRenderValue($string)
    {
        $str = new Str($string);

        $this->assertEquals($string, (string) $str);
        $this->assertEquals($string, $str->value());
    }

    /**
     * @covers ::values
     */
    public function testValues()
    {
        $str = new Str(' something going on ');
        $str->ltrim();
        $str->replace(array('going' => 'not going'));

        $this->assertEquals($str->values(), array(
                ' something going on ',
                'something going on ',
                'something not going on '
        ));
    }

    public function providerAddCslashes()
    {
        return array(
            array('foo=bar', 'A..z', '\f\o\o=\b\a\r'),
            array('testescape','acep','t\est\es\c\a\p\e'),
        );
    }

    /**
     * @covers ::addCslashes
     * @covers ::value
     * @covers ::_set
     * @dataProvider providerAddCslashes
     * @param string $string
     * @param string $charlist
     */
    public function testAddCslashes($string, $charlist, $expected)
    {
        $str = new Str($string);
        $slashed = (string) $str->addCslashes($charlist);

        $this->assertEquals($expected, $slashed);
    }

    public function providerAddSlashes()
    {
        return array(
            array("aa'bb", "aa\'bb"),
            array("O'''Reilly","O\'\'\'Reilly"),
        );
    }

    /**
     * @covers ::addSlashes
     * @covers ::value
     * @covers ::_set
     * @dataProvider providerAddSlashes
     * @param string $string
     * @param string $expected
     */
    public function testAddSlashes($string, $expected)
    {
        $str = new Str($string);
        $slashed = $str->addSlashes();

        $this->assertEquals($expected, $slashed);
    }

    public function providerChunkSplit()
    {
        return array(
            array('foobar', 2, ':', 'fo:ob:ar:'),
            array('foo bar foobar', 3, '!', 'foo! ba!r f!oob!ar!'),
        );
    }

    /**
     * @covers ::chunkSplit
     * @covers ::value
     * @covers ::_default
     * @covers ::_set
     * @dataProvider providerChunkSplit
     * @param $string
     * @param $length
     * @param $end
     * @param $expected
     */
    public function testChunkSplit($string, $length, $end, $expected)
    {
        $str = new Str($string);
        $split = (string) $str->chunkSplit($length, $end);

        $this->assertEquals($expected, $split);
    }

    public function providerCompare()
    {
        return array(
            array('foo', FALSE, 3),
            array('bear', 'tear', -18),
            array('foobar', new Str('fooba'), 1),
        );
    }

    /**
     * @covers ::compare
     * @dataProvider providerCompare
     * @param $string
     * @param $target
     * @param $expected
     */
    public function testCompare($string, $target, $expected)
    {
        $str = new Str($string);
        $result = $str->compare($target);

        $this->assertEquals($expected, $result);
    }

    public function providerCompareInsensitive()
    {
        return array(
            array('hello wOrLd','HELLO world',0),
        );
    }

    /**
     * @covers ::compareInsensitive
     * @dataProvider providerCompareInsensitive
     * @param $str
     * @param $target
     * @param $expected
     */
    public function testCompareInsensitive($string, $target, $expected)
    {
        $str = new Str($string);
        $result = $str->compareInsensitive($target);

        $this->assertEquals($expected, $result);
    }

    public function providerConcat()
    {
        return array(
            array('foo','bar','foobar'),
            array('','foobar','foobar'),
        );
    }

    /**
     * @covers ::concat
     * @covers ::value
     * @covers ::_set
     * @dataProvider providerConcat
     * @param $string
     * @param $concat
     * @param $expected
     */
    public function testConcat($string, $concat, $expected)
    {
        $str = new Str($string);
        $str->concat($concat);

        $this->assertSame($expected, $str->value());
    }

    public function providerContains()
    {
        return array(
            array('containing string','ning',TRUE),
            array('noncontaining','foobar',FALSE),
        );
    }

    /**
     * @covers ::contains
     * @dataProvider providerContains
     * @param $string
     * @param $substring
     * @param $expected
     */
    public function testContains($string, $substring, $expected)
    {
        $str = new Str($string);
        $result = $str->contains($substring);

        $this->assertSame($result, $expected);
    }

    public function providerCountChars()
    {
        return array(
            array('abababcabc', TRUE, array(ord('a') => 4, ord('b') => 4, ord('c') => 2)),
            array('abababcabc', FALSE, array('a' => 4, 'b' => 4, 'c' => 2)),
        );
    }

    /**
     * @covers ::countChars
     * @dataProvider providerCountChars
     * @param $string
     * @param $ascii
     * @param $expected
     */
    public function testCountChars($string, $ascii, $expected)
    {
        $str = new Str($string);
        $result = $str->countChars($ascii);

        $this->assertEquals($expected, $result);
    }

    public function providerExplode()
    {
        return array(
            array('foo is bar sometimes', ' ', array('foo','is','bar','sometimes')),
            array('but_Bar_is_never_Foo', '_', array('but','Bar','is','never','Foo')),
        );
    }

    /**
     * @covers ::explode
     * @dataProvider providerExplode
     * @param $string
     * @param $delimiter
     * @param $expected
     */
    public function testExplode($string, $delimiter, $expected)
    {
        $str = new Str($string);
        $result = $str->explode($delimiter);

        $this->assertEquals($expected, $result);
    }

    public function providerImplode()
    {
        return array(
            array(' ', array('foo'), 'foo'),
            array('_-_', array('foo','bar'), 'foo_-_bar'),
            array('!', array('foo','is','bar','sometimes'), 'foo!is!bar!sometimes'),
            array(' ', array('but','Bar','is','never','Foo'), 'but Bar is never Foo'),
        );
    }

    /**
     * @covers ::implode
     * @dataProvider providerImplode
     * @param $string
     * @param $elements
     * @param $expected
     */
    public function testImplode($string, array $elements, $expected)
    {
        $str = new Str($string);
        $result = $str->implode($elements);

        $this->assertEquals($expected, $result);
    }

    public function providerIreplace()
    {
        return array(
            array('foo=bar', array('FOO' => 'bar'), 'bar=bar'),
            array('coolCoOlCooLCOOL', array('cool' => '0'), '0000'),
        );
    }

    /**
     * @covers ::ireplace
     * @covers ::_set
     * @dataProvider providerIreplace
     * @param $string
     * @param $elements
     * @param $expected
     */
    public function testIreplace($string, array $replacements, $expected)
    {
        $str = new Str($string);
        $result = $str->ireplace($replacements);

        $this->assertEquals($expected, $result);
    }

    public function providerLtrim()
    {
        return array(
            array(" \n\r foo", NULL, 'foo'),
            array('_-_foo', '_-', 'foo'),
        );
    }

    /**
     * @covers ::ltrim
     * @covers ::_default
     * @covers ::_formatCharacterMask
     * @covers ::_set
     * @dataProvider providerLtrim
     * @param $string
     * @param $mask
     * @param $expected
     */
    public function testLtrim($string, $mask, $expected)
    {
        $str = new Str($string);
        $trimmed = $str->ltrim($mask);

        $this->assertEquals($expected, $trimmed);
    }

    public function providerNl2Br()
    {
        return array(
            array("foo\nbar".PHP_EOL, "foo<br />\nbar<br />".PHP_EOL),
        );
    }

    /**
     * @covers ::nl2br
     * @covers ::value
     * @covers ::_set
     * @dataProvider providerNl2Br
     * @param $string
     * @param $mask
     * @param $expected
     */
    public function testNl2Br($string, $expected)
    {
        $str = new Str($string);
        $str->nl2br();

        $this->assertEquals($expected, $str->value());
    }

    public function providerPad()
    {
        return array(
            array('foo',    16, '-.-;', STR_PAD_RIGHT, 'foo-.-;-.-;-.-;-'),
            array('bar',    8,  'foo',  STR_PAD_LEFT,  'foofobar'),
            array('foobar', 10, ':',    STR_PAD_BOTH,  '::foobar::'),
        );
    }

    /**
     * @covers ::pad
     * @covers ::value
     * @covers ::_default
     * @covers ::_set
     * @dataProvider providerPad
     * @param $string
     * @param $pad_length
     * @param $pad_string
     * @param $pad_type
     * @param $expected
     */
    public function testPad($string, $pad_length, $pad_string, $pad_type, $expected)
    {
        $str = new Str($string);
        $str->pad($pad_length, $pad_string, $pad_type);

        $this->assertEquals($expected, $str->value());
    }

    public function providerPosition()
    {
        return array(
            array('foo is bar sometimes', ' is ', 0, 3),
        );
    }

    /**
     * @dataProvider providerPosition
     * @covers ::position
     * @covers ::value
     * @param $string
     * @param $substring
     * @param $offset
     * @param $expected
     */
    public function testPosition($string, $substring, $offset, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->position($substring, $offset));
    }

    public function providerRepeat()
    {
        return array(
            array('foo', 5, 'foofoofoofoofoo'),
        );
    }

    /**
     * @dataProvider providerRepeat
     * @covers ::repeat
     * @covers ::value
     * @covers ::_set
     */
    public function testRepeat($string, $multiplier, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->repeat($multiplier));
    }

    public function providerReplace()
    {
        return array(
            array(
                'foo can be bar but foo does not have to',
                array('foo' => 'fOo', 'DoEs Not' => 'fail', 'to' => '2'),
                NULL,
                'fOo can be bar but fOo does not have 2',
            ),
        );
    }

    /**
     * @dataProvider providerReplace
     * @covers ::replace
     * @covers ::value
     * @covers ::_set
     */
    public function testReplace($string, $replacements, $count, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->replace($replacements, $count));
    }

    public function providerReverse()
    {
        return array(
            array('knitS raW pupilS regaL','Lager Slipup War Stink'),
            array('deSseRTs','sTResSed'),
            array('long live the king','gnik eht evil gnol'),
        );
    }

    /**
     * @dataProvider providerReverse
     * @covers ::reverse
     * @covers ::value
     * @covers ::_set
     */
    public function testReverse($string, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->reverse());
    }

    public function providerRot13()
    {
        return array(
            array('999','999'),
            array('foobar','sbbone'),
            array('PHP 5.3', 'CUC 5.3'),
        );
    }

    /**
     * @dataProvider providerRot13
     * @covers ::rot13
     * @covers ::value
     * @covers ::_set
     * @param $string
     * @param $expected
     */
    public function testRot13($string, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->rot13());
    }

    public function providerRTrim()
    {
        return array(
            array(' foo      ', NULL, ' foo'),
            array('ofoooooo','o','of'),
            array('_foobar ="__-', array('_','-','=','"'),'_foobar '),
        );
    }

    /**
     * @dataProvider providerRTrim
     * @covers ::rtrim
     * @covers ::value
     * @covers ::_default
     * @covers ::_formatCharacterMask
     * @covers ::_set
     * @param $string
     * @param $mask
     * @param $expected
     */
    public function testRTrim($string, $mask, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->rtrim($mask));
    }

    /**
     * @covers ::shuffle
     * @covers ::value
     * @covers ::_set
     */
    public function testShuffle()
    {
        $str = new Str('foo bar foobar pebkac fubar');

        $original = $str->value();

        $str->shuffle();

        $this->assertNotEquals($str->value(), $original);
    }

    public function providerStripTags()
    {
        return array(
            array('<p>This should <br>not be wrapped</p>', NULL, 'This should not be wrapped'),
            array('<html><div><p><br/><br></p></div></html>', NULL, ''),
            array('<html><div><p><br/><br></p></div></html>', '<br>', '<br/><br>'),
        );
    }

    /**
     * @dataProvider providerStripTags
     * @covers ::stripTags
     * @covers ::value
     * @covers ::_set
     * @param $string
     * @param $allowed_tags
     * @param $expected
     */
    public function testStripTags($string, $allowed_tags, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->stripTags($allowed_tags));
    }

    public function providerTranslate()
    {
        return array(
            array(':foo :bar', array(':foo' => ':bar', ':bar' => ':newbar'), ':bar :newbar'),
            array('foo is bar', array('foo' => 'bar','bar' => 'foo'),'bar is foo'),
        );
    }

    /**
     * @dataProvider providerTranslate
     * @covers ::translate
     * @covers ::value
     * @covers ::_set
     * @param $string
     * @param $translations
     * @param $expected
     */
    public function testTranslate($string, $translations, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->translate($translations));
    }

    public function providerTrim()
    {
        return array(
            array(" \n foo      ", NULL, 'foo'),
            array('offsoooooo','o','ffs'),
            array('_foobar ="__-', array('_','-','=','"'),'foobar '),
        );
    }

    /**
     * @dataProvider providerTrim
     * @covers ::trim
     * @covers ::value
     * @covers ::_default
     * @covers ::_formatCharacterMask
     * @covers ::_set
     * @param $string
     * @param $mask
     * @param $expected
     */
    public function testTrim($string, $mask, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->trim($mask));
    }

    public function providerUndo()
    {
        return array(
            array(' foo is bar ', 1, 'foo is bar'),
            array(' foo is bar ', 2, ' foo is bar'),
            array(' foo is bar ', 10, ' foo is bar '),
        );
    }

    /**
     * @covers ::undo
     * @dataProvider providerUndo
     * @param $steps
     * @param $expected
     */
    public function testUndo($string, $steps, $expected)
    {
        $str = new Str($string);
        $str->rtrim();
        $str->ltrim();
        $str->replace(array(' is ' => '_is_'));

        $this->assertEquals($expected, $str->undo($steps));
    }

    /**
     * @covers ::undo
     * @param $steps
     * @param $expected
     */
    public function testUndoArgument()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $str = new Str('unimportant string');
        $str->undo('string');
    }

    public function providerUniqueChars()
    {
        return array(
            array('abcabdabcd','abcd'),
            array('zd111cba', '1abcdz'),
            array('z2d3c88ba', '238abcdz'),
        );
    }

    /**
     * @covers ::uniqueChars
     * @covers ::value
     * @dataProvider providerUniqueChars
     * @param $string
     * @param $expected
     */
    public function testUniqueChars($string, $expected)
    {
        $str = new Str($string);
        $unique = $str->uniqueChars();

        $this->assertEquals($expected, $unique);
    }

    public function providerWordCount()
    {
        return array(
            array('foo is here 3 times foo foo', NULL, 6),
        );
    }

    /**
     * @covers ::wordCount
     * @covers ::value
     * @dataProvider providerWordCount
     * @param $string
     * @param $charlist
     * @param $expected
     */
    public function testWordCount($string, $charlist, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->wordCount($charlist));
    }

    public function providerWords()
    {
        return array(
            array('foo foo bar foo', NULL, array(0 => 'foo', 4 => 'foo', 8 => 'bar', 12 => 'foo')),
        );
    }

    /**
     * @covers ::words
     * @covers ::value
     * @dataProvider providerWords
     * @param $string
     * @param $charlist
     * @param $expected
     */
    public function testWords($string, $charlist, $expected)
    {
        $str = new Str($string);

        $this->assertEquals($expected, $str->words($charlist));
    }

}
