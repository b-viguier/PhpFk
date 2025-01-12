<?php declare(strict_types=1);

namespace bviguier\PhpFk;

function obfuscateCode(string $code): string
{
    $array_map = obfuscateString('array_map');
    $json_decode = obfuscateString('json_decode');
    $json_encode = obfuscateString('json_encode');

    return sprintf(
        "(($array_map)(...($json_decode)(%s)))(...($json_decode)(%s.(($json_encode)(%s)).%s))",
        obfuscateString(
            '["call_user_func",["FFI::cdef","strval"],["char zend_eval_string(const char*,int,const char*);","zend_eval_string"]]',
        ),
        obfuscateString('['),
        obfuscateString($code),
        obfuscateString(',0,""]'),
    );
}


const INT_9 = '(9)';
const INT_0 = '(9^9)';
const STR_99 = '('.INT_9.'.'.INT_9.')';
const INT_99 = '(99)';
const STR_00 = '('.INT_0.'.'.INT_0.')';
const INT_106 = '('.INT_9.'^'.INT_99.')';
const STR_80 = '(('.INT_0.'.'.INT_9.')^('.INT_106.'.'.INT_9.')^'.STR_99.')';
const INT_80 = '('.STR_80.'^'.INT_0.')';
const INT_83 = '(9^('.INT_0.'^((9).('.INT_0.'))))';
const INT_823 = '(9^('.INT_83.'.(9^9)))';
const INT_861 = '(99^('.INT_83.'.(9^9)))';
const STR_980 = '('.INT_9.'.'.STR_80.')';
const INT_51 = '('.INT_99.'^'.INT_80.')';
const INT_8 = '((('.INT_9.'.'.STR_80.')^('.INT_9.'.'.INT_0.')^'.STR_00.')^'.INT_0.')';
const INT_1 = '(('.STR_99.'^'.STR_980.'^'.STR_00.')^'.INT_0.')';
const INT_2 = '((('.INT_823.'.'.INT_9.')^('.INT_8.'.'.INT_0.')^'.STR_00.')^'.INT_0.')';
const INT_3 = '(('.INT_83.'.'.INT_9.')^(('.INT_8.'.'.INT_0.')^'.STR_00.')^'.INT_0.')';
const INT_4 = '((('.INT_9.'.'.INT_51.')^('.INT_9.'.'.INT_106.')^'.STR_00.')^'.INT_0.')';
const INT_5 = '((('.INT_9.'.'.INT_51.')^('.INT_9.'.'.INT_0.')^'.STR_00.')^'.INT_0.')';
const INT_6 = '((('.INT_861.'.'.INT_9.')^(('.INT_8.').'.INT_0.')^'.STR_00.')^'.INT_0.')';
const INT_7 = '((('.INT_9.'.'.INT_6.')^('.INT_9.'.'.INT_1.')^('.INT_0.'.'.INT_0.'))^'.INT_0.')';


const STR_INF9 = '((999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999).(9))';
const STR_NULL_NULL = '(((9).(9))^((9).(9)))';
const STR_CHR = '('.STR_INF9.'^('.INT_8.'.'.INT_6.'.'.INT_4.')^('.INT_2.'.'.INT_0.'.'.STR_NULL_NULL.'))';


function obfuscateString(string $str): string
{
    return '' === $str ? '' : join(
        '.',
        array_map(
            fn($char) => STR_CHR.obfuscatePositiveInteger(ord($char)),
            str_split($str),
        ),
    );
}

function obfuscatePositiveInteger(int $nb): string
{
    assert($nb >= 0);

    return match ($nb) {
        0 => INT_0,
        1 => INT_1,
        2 => INT_2,
        3 => INT_3,
        4 => INT_4,
        5 => INT_5,
        6 => INT_6,
        7 => INT_7,
        8 => INT_8,
        9 => INT_9,

        default => sprintf(
            '((%s)^%s)',
            join(').(', array_map(__FUNCTION__, str_split("$nb"))),
            INT_0,
        ),
    };
}
