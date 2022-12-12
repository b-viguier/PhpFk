---
layout: page
title: What is the minimum number of characters needed to run every possible program in Php?
---

This is just the kind of question that I love: short, simple to understand... but damn challenging!
This question already led to the creation of the [brainfuck](https://en.wikipedia.org/wiki/Brainfuck) language,
using only 8 characters.
This inspired [JsFuck](https://en.wikipedia.org/wiki/JSFuck), a subset of Javascript using only 6 characters and executable in every Javascript interpreter.
I was curious about PHP and found the [PhpFuck](https://github.com/splitline/PHPFuck) project, running with 7 characters.
Unfortunately, it relies on [`create_function`](https://www.php.net/manual/en/function.create-function), deprecated in Php 7.2,
and it produces a lot of warnings `Array to string conversion`.

Here is my journey to write [`PhpFk`](https://github.com/b-viguier/PhpFk), a small library to convert every Php program to a valid Php 8.2
program with a minimum number of characters.
We will see some tricky parts of Php and I hope you'll have as much fun reading it as I had to code it!

## Rules
Here are some arbitrary rules that I fixed for this challenge:
* Must work _out of the box_ with PHP 8.2 CLI.
* No warnings, no notices... and forbidden to disable them!
* Spaces, new lines, tabulations... even blank characters are counted
* PHP tags are not part of the challenge, we can assume that the code will be used as per below
```php
<?php
// <your code here>
?>
```

## Basic: the `eval` approach
[JsFuck](https://en.wikipedia.org/wiki/JSFuck) and [PhpFuck](https://github.com/splitline/PHPFuck) use the same concepts:
* a way to create every possible string
* a way to _execute_ a piece of code stored in a string. 

The [`eval`](https://www.php.net/manual/en/function.eval) function is a good start, but note that it will already count for 6 characters:
`e`, `v`, `a`, `l`, `(`, `)`... and we still need to write a string!
But let's start easy.
Good news: the last semicolon `;` is not required before the [PHP ending tag](https://www.php.net/manual/en/language.basic-syntax.phptags.php).

To create an arbitrary string with a restricted set of characters, it's possible to use the [`chr`](https://www.php.net/manual/en/function.chr) function,
allowing us to create a character from its single byte codepoint.
Then, the remaining question is to know how to generate numbers from `0` to `255` (or `1` to `256`, since a [modulo is applied to the codepoint](https://www.php.net/manual/en/function.chr.php#refsect1-function.chr-parameters)).
An intuitive way is to sum `1` to itself, as many times as needed: `1+1+1+1` is evaluated to `4`, using only 2 characters.

Here we are now, we can reduce every PHP program to a valid PHP program using the following characters:
* `1` and `+` to create some integers/codepoints,
* `(` and `)` to call a function (see below),
* `c`, `h` and `r` to execute the [`chr`](https://www.php.net/manual/en/function.chr) function and to transform an integer into a character,
* `.` to concatenate these characters to string,
* `e`, `v`, `a` and `l` to finally execute the PHP code stored in the string.

A total of **12** characters, not so bad for a very naive approach! Here a _"Hello World"_ example:
 ```php
<?php
eval(chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1).chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1))
?>
```

Now, every character that we want to remove will need some effort... brace yourself!

## Callable and XOR
PHP is a very _flexible_ language, especially for function calls.
Concept of [variable functions](https://www.php.net/manual/en/functions.variable-functions.php) 
allows us to execute a function from a string containing its name.
It means that `chr(42)` can be replaced by `'chr'(42)`.
It's interesting, because if we can generate the `chr` string, it may save us some characters!
But... our current method to generate strings requires `chr`, how could we build `chr` string without `chr` function?
It's a [chicken/egg problem](https://en.wikipedia.org/wiki/Chicken_or_the_egg).
But PHP still has some powerful _secrets_...

The [bitwise XOR operator (`^`)](https://www.php.net/manual/en/language.operators.bitwise.php) will help us,
because it can be used to create a new character by XORing the ASCII code of two input characters.
For example, `'1'^'v'` is evaluated to the character `'G'`.
By testing all possible combinations from our current characters set (see previous paragraph),
we can find out these:
* `'.'^'('^'e'` produces `'c'`
* `'l'^'a'^'e'` produces `'h'`
* `'a'^'v'^'e'` produces `'r'`
* `'+'^'l'^'v'` produces `'1'` 

That's it! We can produce the `'chr'` and `'1'` strings as soon as we include characters `'` and `^` in our set, we are saving 2 characters!
Here is our new character's selection (as a string): `eval(.^'+)`, **10** characters.

This trick is heavily used in the original [PhpFuck](https://github.com/splitline/PHPFuck),
but it's interesting to notice that they don't even need the `'`.
It's because they are using  expression `[].[]` to produce the string `ArrayArray`, and then XOR some new characters from there.
But this syntax produces a warning, and then is excluded by our rules.

If you're wondering why we don't try to use the same logic for the `eval` function, it's because it's impossible.
`eval` is not a function, it's a [language construct](https://www.php.net/manual/en/function.eval.php#refsect1-function.eval-notes),
meaning that we still have to deal with corresponding 4 characters for now.

You can find our new _Hello world_ program [here](hello_world_10.html).
Sure, that's a long code to just write _"Hello World"_...
Fortunately, the next trick will reduce the size of generated code.
 

## Producing integers from strings
The next character to be removed is the `+`, and it won't need too complex processing.
The `chr` function requires an integer input, but PHP can easily [convert a string representing an integer](https://www.php.net/manual/en/language.types.integer.php#language.types.integer.casting),
to an actual one. Fortunately, the XOR operator can help us to produces all numerical digits with our current set of characters:
* `'0'`: `'.'^'l'^'a'^'v'^'e'`,
* `'1'`: `')'^'^'^'.'^'l'^'a'^'e'`,
* `'2'`: `'^'^'l'`,
* `'3'`: `')'^'l'^'v'`,
* `'4'`: `'.'^'l'^'v'`,
* `'5'`: `')'^'^'^'.'^'l'`,
* `'6'`: `'^'^'l'^'a'^'e'`,
* `'7'`: `')'^'l'^'a'^'v'^'e'`,
* `'8'`: `')'^'^'^'.'^'a'`,
* `'9'`: `'.'^'a'^'v'`,

Then, every positive integer can be produced as the concatenation of digits above,
and PHP will take care of type conversion while calling `chr`.
For example, `chr(42)` (`*` character) can be replaced by `chr(('.'^'l'^'v').('^'^'l'))`.
It will also reduce the size of generated code!

Here is new _Hello world_", generated with only **9 characters** (`eval().^'`):
```php
<?php
eval(((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').(')'^'^'^'.'^'l'^'a'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))((('.'^'a'^'v').('.'^'a'^'v')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').('.'^'l'^'v')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'l'^'v').('^'^'l')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'l'^'v').('.'^'a'^'v')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'l'^'a'^'v'^'e').('^'^'l')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').(')'^'^'^'.'^'l'^'a'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').(')'^'^'^'.'^'a')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').(')'^'^'^'.'^'a')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'l'^'v').('^'^'l')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'a').(')'^'l'^'a'^'v'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').(')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'v')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').(')'^'^'^'.'^'a')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l'^'a'^'e').('.'^'l'^'a'^'v'^'e').('.'^'l'^'a'^'v'^'e')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'l'^'v').('.'^'a'^'v')))).((('.'^'('^'e').('l'^'a'^'e').('a'^'v'^'e'))(((')'^'^'^'.'^'l').('.'^'a'^'v')))))
?>
```

## Removing the `eval`

### FFI to the rescue
FFI stands for [_Foreign Function Interface_](https://www.php.net/manual/en/book.ffi.php).
Since 7.4, it allows to load shared libraries and to call contained C functions directly from PHP.
The fun part is that it is even possible to call C functions from the PHP ZendEngine itself!
In particular, it's possible to call the [`zend_eval_string`](https://github.com/php/php-src/blob/b96b88b669370e1d85b6e98e359649d73b548029/Zend/zend_execute.h#L53)
to reproduce the `eval` behavior, but without having to use this language construct.
```php
<?php
FFI::cdef('char zend_eval_string(const char *, int, const char *);')->zend_eval_string('echo "Hello World";',0,'');
``` 
This code can be rewritten to maximize the usage of [variable functions syntax](https://www.php.net/manual/en/functions.variable-functions.php).
```php
<?php
['FFI::cdef'('char zend_eval_string(const char *, int, const char *);'),'zend_eval_string']('echo "Hello World";',0,'');
```
We introduced 3 new characters (`[`, `]` and `,`) to finally get rid of the 4 composing `eval`, we saved 1 character!
Our final set of 8 characters is `[(,.^')]`, but... how can we _XOR_ some new characters without `e`, `v`, `a` and `l`? 

### Retrieving the lost characters
We still can _XOR_ some combinations from `[(,.^)]` characters: `pqrstuvwXYZ`
Notice that `chr0123456789` are missing.
Having upper/lower case characters is not an issue, because [function names are case-insensitive in PHP](https://www.php.net/manual/en/functions.user-defined.php).
Our last chance is to find a way to generate a new character, by calling some functions with characters above.
* [`sqrt`](https://www.php.net/manual/en/function.sqrt) can produce a number, and numbers can easily be converted to string.
  That's a good idea, but how to produce an input number for the function?
* [`strstr`](https://www.php.net/manual/en/function.strstr) returns a string, or false.
  That's it, it's quite easy for PHP to [convert false to `0`](https://www.php.net/manual/en/language.types.integer.php#language.types.integer.casting).

Here the expression to produce the `0` character from ou current set:
```php
''.sqrt(strstr('','.'))
```
And with this additional character, we can finally produce the `Chr` string:
* `'C'`: `'0'^'('^'['`
* `'h'`: `'0'^']'^'^'^'['`
* `'r'`: `')'^'['`

The only downside is that we cannot directly generate characters `'8'` and `'9'`.
Since we can produce `oCtDEC` string, we can use [`octdec` function](https://www.php.net/manual/en/function.octdec)
to convert `'10'` and `'11'` from base 8 to `'8'` and `'9'` in base 10.

That's it, we did it! **8 characters** to rewrite every PHP program: `[(,.^')]`.
Our final _Hello World_ is a little heavy to be displayed here, but look at [this file](hello_world_8.html). 


## Conclusion

Of course, all of this is _useless_... Do NOT use `PhpF**k` in production.
But keep in mind that some nasty minds could try to send you some dirty PHP instructions, hidden in only 8 characters.
Anyway, it was a fun challenge for me, I hope you enjoyed all those juicy details like I did.

If you're curious, I wrote some functions to transform any PHP code, in [this Github repository](https://github.com/b-viguier/PhpFk).
It's also a good place if you want to [discuss it](https://github.com/b-viguier/PhpFk/discussions),
ask questions... or even propose some new ideas.
More information about me on [my website](https://b-viguier.github.io/).
