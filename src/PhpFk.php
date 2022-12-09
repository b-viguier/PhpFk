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

function obfuscateString(string $str): string
{
    $c = sprintf("'['^(''.(%s))", obfuscatePositiveInteger(8));
    $h = sprintf("'['^(''.(%s))", obfuscatePositiveInteger(3));
    $r = "'['^')'";

    return '' === $str ? "''" : join(
        '.',
        array_map(
            fn($char) => "(($c).($h).($r))(" . obfuscatePositiveInteger(ord($char)) . ")",
            str_split($str)
        )
    );
}

function obfuscatePositiveInteger(int $nb): string
{
    assert($nb >= 0);
    return match ($nb) {
        0 => '+!![]',
        1 => '+![]',
        2, 3, 4, 5, 6, 7, 8, 9 => join('+', array_fill(0, $nb, '![]')),
        default => sprintf('+(%s)', join('.', array_map(__FUNCTION__, str_split("$nb")))),
    };
}
