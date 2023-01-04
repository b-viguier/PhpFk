<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use bviguier\PhpFk;


final class PhpFkTest extends TestCase
{
    private function assertValidString(string $str): void
    {
        $this->assertMatchesRegularExpression('/^[,\(\).^\']*$/', $str);
    }

    /**
     * @dataProvider positiveIntegerProvider
     */
    public function testObfuscatePositiveInteger(int $nb): void
    {
        $obfuscatedStr = PhpFk\obfuscatePositiveInteger($nb);
        $this->assertValidString($obfuscatedStr);

        $result = eval("return $obfuscatedStr;");

        $this->assertEquals("$nb", $result);
    }

    public function positiveIntegerProvider(): iterable
    {
        for ($i = 0; $i < 10; ++$i) {
            yield "$i" => [$i];
        }
        foreach ([42, 111, 1024] as $i) {
            yield "$i" => [$i];
        }
    }

    /**
     * @dataProvider stringProvider
     */
    public function testObfuscateString(string $str): void
    {
        $obfuscatedStr = PhpFk\obfuscateString($str);
        $this->assertValidString($obfuscatedStr);

        $result = eval("return $obfuscatedStr;");

        $this->assertSame($str, $result);
    }

    public function stringProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'ASCII' => ['azertyuiopqsdfghjklmwxcvbn'];
        yield 'Specials' => ["\n\t "];
        yield 'Emoji' => ['😼'];
    }

    /**
     * @dataProvider codeProvider
     */
    public function testObfuscateCode(string $code, string $expectedOutput): void
    {
        $obfuscatedStr = PhpFk\obfuscateCode($code);
        $this->assertValidString($obfuscatedStr);

        $this->expectOutputString($expectedOutput);
        eval("$obfuscatedStr;");
    }

    public function codeProvider(): iterable
    {
        yield 'Hello' => ['echo "Hello World";', 'Hello World'];

        yield 'Class' => [
            'echo (new class() {public function __toString(): string {return "My Class";}});',
            'My Class'
        ];
    }
}
