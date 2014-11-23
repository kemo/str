<?php namespace Kemo\Strings;
/**
 * Created by PhpStorm.
 * User: kemo
 * Date: 23/11/14
 * Time: 11:25
 */

class StrTest extends \PHPUnit_Framework_TestCase {

    public function providerRenderValue()
    {
        return array(
            array('foo'),
            array('something going on'),
        );
    }

    /**
     * @covers \Kemo\Strings\Str::__construct
     * @covers \Kemo\Strings\Str::__toString
     * @covers \Kemo\Strings\Str::value
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
     * @covers \Kemo\Strings\Str::values
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
     * @covers \Kemo\Strings\Str::addCslashes
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
     * @covers \Kemo\Strings\Str::addSlashes
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
     * @covers \Kemo\Strings\Str::chunkSplit
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
     * @covers \Kemo\Strings\Str::compare
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
     * @covers \Kemo\Strings\Str::compareInsensitive
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
     * @covers \Kemo\Strings\Str::concat
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
     * @covers \Kemo\Strings\Str::contains
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
     * @covers \Kemo\Strings\Str::countChars
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
     * @covers \Kemo\Strings\Str::explode
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


    public function providerUniqueChars()
    {
        return array(
            array('abcabdabcd','abcd'),
            array('zd111cba', '1abcdz'),
            array('z2d3c88ba', '238abcdz'),
        );
    }

    /**
     * @covers \Kemo\Strings\Str::uniqueChars
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
}
 