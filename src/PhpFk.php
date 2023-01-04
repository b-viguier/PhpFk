<?php declare(strict_types=1);

namespace bviguier\PhpFk;

function obfuscateCode(string $code): string
{
    return sprintf(
        "(%s)((%s)((%s)(),%s,(%s)(%s)),%s,%s)(%s,%s,%s)",
        obfuscateString('array_pad'),
        obfuscateString('array_pad'),
        obfuscateString('array_merge'),
        obfuscatePositiveInteger(1),
        obfuscateString('FFI::cdef'),
        obfuscateString('char zend_eval_string(const char *, int, const char *);'),
        obfuscatePositiveInteger(2),
        obfuscateString('zend_eval_string'),
        obfuscateString($code),
        obfuscatePositiveInteger(0),
        obfuscateString('')
    );
}

const INITIAL_CHAR_MAP = [
    '(' => "'('",
    ')' => "')'",
    '*' => "('.'^','^'(')",
    '+' => "(')'^'.'^',')",
    ',' => "','",
    '-' => "(')'^','^'(')",
    '.' => "'.'",
    '/' => "(')'^'.'^'(')",
    'X' => "('^'^'.'^'(')",
    'Y' => "(')'^'^'^'.')",
    'Z' => "('^'^','^'(')",
    '[' => "(')'^'^'^',')",
    '\\' => "('^'^'.'^',')",
    ']' => "(')'^'^'^'.'^','^'(')",
    '^' => "'^'",
    '_' => "(')'^'^'^'(')",
    'p' => "('^'^'.')",
    'q' => "(')'^'^'^'.'^'(')",
    'r' => "('^'^',')",
    's' => "(')'^'^'^','^'(')",
    't' => "('^'^'.'^','^'(')",
    'u' => "(')'^'^'^'.'^',')",
    'v' => "('^'^'(')",
    'w' => "(')'^'^')",
];

const STRSTR = '(' . INITIAL_CHAR_MAP['s'] . '.' . INITIAL_CHAR_MAP['t'] . '.' . INITIAL_CHAR_MAP['r'] . '.' . INITIAL_CHAR_MAP['s'] . '.' . INITIAL_CHAR_MAP['t'] . '.' . INITIAL_CHAR_MAP['r'] . ')';
const SQRT = '(' . INITIAL_CHAR_MAP['s'] . '.' . INITIAL_CHAR_MAP['q'] . '.' . INITIAL_CHAR_MAP['r'] . '.' . INITIAL_CHAR_MAP['t'] . ')';
const _FALSE = STRSTR . "('','.')";
const ZERO_INT = SQRT . '(' . _FALSE . ')';
const ZERO_CHAR = '(' . ZERO_INT . ".'')";

const CHAR_MAP = INITIAL_CHAR_MAP + [
        '0' => ZERO_CHAR,
        '1' => '(' . ZERO_CHAR . "^')'^'(')",
        '2' => '(' . ZERO_CHAR . "^'.'^',')",
        '3' => '(' . ZERO_CHAR . "^')'^'.'^','^'(')",
        '4' => '(' . ZERO_CHAR . "^','^'(')",
        '5' => '(' . ZERO_CHAR . "^')'^',')",
        '6' => '(' . ZERO_CHAR . "^'.'^'(')",
        '7' => '(' . ZERO_CHAR . "^')'^'.')",
        '@' => '(' . ZERO_CHAR . "^'^'^'.')",
        'A' => '(' . ZERO_CHAR . "^')'^'^'^'.'^'(')",
        'B' => '(' . ZERO_CHAR . "^'^'^',')",
        'C' => '(' . ZERO_CHAR . "^')'^'^'^','^'(')",
        'D' => '(' . ZERO_CHAR . "^'^'^'.'^','^'(')",
        'E' => '(' . ZERO_CHAR . "^')'^'^'^'.'^',')",
        'F' => '(' . ZERO_CHAR . "^'^'^'(')",
        'G' => '(' . ZERO_CHAR . "^')'^'^')",
        'h' => '(' . ZERO_CHAR . "^'^'^'.'^'(')",
        'i' => '(' . ZERO_CHAR . "^')'^'^'^'.')",
        'j' => '(' . ZERO_CHAR . "^'^'^','^'(')",
        'k' => '(' . ZERO_CHAR . "^')'^'^'^',')",
        'l' => '(' . ZERO_CHAR . "^'^'^'.'^',')",
        'm' => '(' . ZERO_CHAR . "^')'^'^'^'.'^','^'(')",
        'n' => '(' . ZERO_CHAR . "^'^')",
        'o' => '(' . ZERO_CHAR . "^')'^'^'^'(')",
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
