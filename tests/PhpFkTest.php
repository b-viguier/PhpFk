<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use bviguier\PhpFk;


final class PhpFkTest extends TestCase
{
    private function assertValidString(string $str): void
    {
        $this->assertMatchesRegularExpression('/^[9\(\).^]*$/', $str);
    }

    /**
     * @dataProvider constantHelperProvider
     */
    public function testConstantHelper(string $expr, int|string $expected): void
    {
        $this->assertValidString($expr);
        $this->assertSame($expected, eval("return $expr;"));
    }

    public function constantHelperProvider(): iterable
    {
        yield 'INT_9' => [PhpFk\INT_9, 9];
        yield 'INT_0' => [PhpFk\INT_0, 0];
        yield 'STR_99' => [PhpFk\STR_99, '99'];
        yield 'INT_99' => [PhpFk\INT_99, 99];
        yield 'STR_00' => [PhpFk\STR_00, '00'];
        yield 'INT_106' => [PhpFk\INT_106, 106];
        yield 'STR_80' => [PhpFk\STR_80, '80'];
        yield 'INT_80' => [PhpFk\INT_80, 80];
        yield 'INT_83' => [PhpFk\INT_83, 83];
        yield 'INT_823' => [PhpFk\INT_823, 823];
        yield 'INT_861' => [PhpFk\INT_861, 861];
        yield 'STR_980' => [PhpFk\STR_980, '980'];
        yield 'STR_INF9' => [PhpFk\STR_INF9, 'INF9'];
        yield 'STR_NULL_NULL' => [PhpFk\STR_NULL_NULL, "\0\0"];
        yield 'STR_CHR' => [PhpFk\STR_CHR, 'CHr'];
    }

    /**
     * @dataProvider positiveIntegerProvider
     */
    public function testObfuscatePositiveInteger(int $nb): void
    {
        $obfuscatedStr = PhpFk\obfuscatePositiveInteger($nb);
        $this->assertValidString($obfuscatedStr);

        $result = eval("return $obfuscatedStr;");

        $this->assertSame($nb, $result);
    }

    public function positiveIntegerProvider(): iterable
    {
        for ($i = 0; $i < 10; ++$i) {
            yield "int $i" => [$i];
        }
        foreach ([42, 111, 1024] as $i) {
            yield "int $i" => [$i];
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
            'My Class',
        ];
    }
}
