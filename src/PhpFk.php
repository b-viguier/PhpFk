<?php declare(strict_types=1);

namespace bviguier\PhpFk;

function obfuscateCode(string $code): string
{
    return sprintf(
        "[[%s,%s](%s),%s](%s,%s,%s)",
        obfuscateString('FFI'),
        obfuscateString('cdef'),
        obfuscateString('typedef char zend_result;zend_result zend_eval_string(const char *, int, const char *);'),
        obfuscateString('zend_eval_string'),
        obfuscateString($code),
        obfuscatePositiveInteger(0),
        obfuscateString('')
    );
}

const CHAR_MAP = [
    '(' => "'('",
    ')' => "')'",
    '*' => "'.'^','^'('",
    '+' => "']'^'^'^'('",
    ',' => "','",
    '-' => "','^')'^'('",
    '.' => "'.'",
    '/' => "'.'^')'^'('",
    '0' => "'C'^'['^'('",
    '1' => "'C'^'['^')'",
    '2' => "'C'^','^']'",
    '3' => "'C'^'.'^'^'",
    '4' => "'C'^'^'^')'",
    '5' => "'C'^'^'^'('",
    '6' => "'C'^']'^'('",
    '7' => "'C'^']'^')'",
    '@' => "'C'^']'^'^'",
    'A' => "'C'^'.'^','",
    'B' => "'C'^')'^'('",
    'C' => "'C'",
    'D' => "'C'^'.'^')'",
    'E' => "'C'^'.'^'('",
    'F' => "'C'^','^')'",
    'G' => "'C'^','^'('",
    'X' => "'.'^'^'^'('",
    'Y' => "','^']'^'('",
    'Z' => "'['^')'^'('",
    '[' => "'['",
    '\\' => "]'^')'^'('",
    ']' => "']'",
    '^' => "'^'",
    '_' => "'^'^')'^'('",
    'h' => "'C'^']'^'^'^'('",
    'i' => "'C'^'.'^','^'('",
    'j' => "'C'^')'",
    'k' => "'C'^'('",
    'l' => "'C'^'.'^')'^'('",
    'm' => "'C'^'.'",
    'n' => "'C'^','^')'^'('",
    'o' => "'C'^','",
    'p' => "'.'^'^'",
    'q' => "','^']'",
    'r' => "'['^')'",
    's' => "'['^'('",
    't' => "']'^')'",
    'u' => "']'^'('",
    'v' => "'^'^'('",
    'w' => "'^'^')'",
];

function obfuscateString(string $str): string
{
    return '' === $str ? "''" : join(
        '.',
        array_map(
            fn($char) => sprintf('(%s)',
                CHAR_MAP[$char]
                ?? sprintf('((%s).(%s).(%s))(%s)',
                CHAR_MAP['C'],
                CHAR_MAP['h'],
                CHAR_MAP['r'],
                obfuscatePositiveInteger(ord($char))
            )
            ),
            str_split($str)
        )
    );
}

function obfuscatePositiveInteger(int $nb): string
{
    assert($nb >= 0);
    return match ($nb) {
        0, 1, 2, 3, 4, 5, 6, 7 => CHAR_MAP[$nb],
        8, 9 => sprintf('((%s).(%s).(%s).(%s).(%s).(%s))(%s)',
            CHAR_MAP['o'], CHAR_MAP['C'], CHAR_MAP['t'], CHAR_MAP['D'], CHAR_MAP['E'], CHAR_MAP['C'],
            join('.', array_map(
                    fn($digit) => sprintf('(%s)', CHAR_MAP[$digit]),
                    str_split(decoct($nb)))
            )
        ),
        default => sprintf('(%s)', join(').(', array_map(__FUNCTION__, str_split("$nb")))),
    };
}
