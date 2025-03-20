---
layout: post
title: Crafting valid PHP 8 code using only five different characters
date: Mar. 17th, 2025
---

<style>
.codeblock-label {
  font-size: 0.8em;
  color: #777;
  margin-top: 2em;
  margin-bottom: 0em;
  text-align: center;
}

.codeblock-label > code {
  font-size: 0.8em;
}
</style>

# Unlocking creativity: transforming an absurd coding challenge into a thorough exploration of trickiest PHP features.

When imagining a bike, you might picture someone racing or enjoying a leisurely ride.
However, another option to consider is BMX, which is great for performing stunts and freestyle tricks.
Which type of bike is the best? Ultimately, it depends on your goals.
For instance, you’re unlikely to win the _Tour de France_ on a BMX, but the freestyle discipline offers an excellent way to challenge your balance and express your creativity.

This article will explore how to be creative with PHP by writing valid code using a minimal number of characters.
We'll utilize some of the language's trickiest features to achieve this.

## Context
This type of constraint has been used in the past to create various programming languages, with [Brainfuck](https://en.wikipedia.org/wiki/Brainfuck) being a notable example.
Created in 1993 by Urban Müller, Brainfuck’s primary purpose was to be compiled with the smallest possible compiler.
Müller crafted a binary of just 296 bytes, which compiles a language made up of only eight single-character commands.
The README for Brainfuck poses a challenging question: _"Who can program anything useful with it? :)"_.

<p id="example-1" class="codeblock-label">Example 1: “Hello World” written in Brainfuck</p>
```brainfuck
++++++++[>++++[>++>+++>+++>+<<<<-]>+>+>->>+[<]<-]>>.>---.+++++++..+++.>>.<-.<.+++.------.--------.>>+.>++.
```


Years later, around 2010, some JavaScript developers discovered a way to obfuscate code using just eight characters.
This technique allowed them to bypass malicious code detection in cross-site scripting scenarios.
They named this method [JSFuck](https://jsfuck.com/) as a tribute to Brainfuck.

In PHP, some individuals proposed methods to write valid PHP code using only 
[seven](https://github.com/splitline/PHPFuck),
[six](https://github.com/lebr0nli/PHPFun),
or even [five](https://github.com/arxenix/phpfuck) characters.
Unfortunately, these solutions are not compatible with PHP 8 due to several deprecations.
Thus, our challenge is to find a way to write valid PHP 8 code using only 5 characters.
However, _freestyle_ does not mean _rule-free_.
We must adhere to the following commonly accepted guidelines:
* We should use the standard PHP interpreter without any fancy extensions.
* Our code must not produce any warnings or deprecation messages; it must be 100% legitimate.
* We assume that the produced code will be executed between standard PHP opening and closing tags: `<?php /* code */ ?>`.

With these constraints in mind, let's embark on the challenge!


## Generating strings

Let's start with the classic _"Hello World"_ example.
This brief text requires a 13-character program for display (`echo "HloWrd;` see [Example 2](#example-2)), and as the length of the displayed string increases, more characters will be needed.

<p id="example-2" class="codeblock-label">Example 2: "Hello World" in PHP</p>
```php
echo "Hello World";
```

To begin, we need to find a way to generate all possible strings with a limited number of characters.
Fortunately, PHP offers a convenient function for this task: [`chr()`](https://www.php.net/manual/en/function.chr.php).
This function returns the character corresponding to the provided positive integer code point.
This is useful because positive integers can be represented using just 10 digits (0-9).
Thus, the `"Hello World"` string can be represented using only 16 characters (`chr().0123456789`), and we can generate all possible strings accordingly.

<p id="example-3" class="codeblock-label">Example 3: <code>"Hello World"</code> string written with <code>chr().0123456789</code> characters</p>
```php
"Hello World" === chr(72).chr(101).chr(108).chr(108).chr(111).chr(32).chr(87).chr(111).chr(114).chr(108).chr(100);
```

## Strings are code

That’s a good start, but PHP is not just about strings; we also need to consider how to handle all code instructions such as `if` statements, functions, `try`/`catch` blocks, includes...
The solution lies in the great flexibility offered by interpreted languages through [`eval()`](https://www.php.net/manual/en/function.eval.php).
If we can generate all strings using a small set of characters, we can write any program and evaluate it with `eval()`.
For instance, our _"Hello World"_ example can be simplified, and we can conclude that 21 characters (`eval()chr.0123456789;`) are sufficient to write and execute any PHP program.

<p id="example-4" class="codeblock-label">Example 4: "Hello World" program written with 21 characters: <code>eval()chr.0123456789;</code></p>
```php
eval(chr(101).chr(99).chr(104).chr(111).chr(32).chr(34).chr(72).chr(101).chr(108).chr(108).chr(111).chr(32).chr(87).chr(111).chr(114).chr(108).chr(100).chr(34).chr(59));
```

The strategy described above is similar to what is used in JsFuck and other PHP clones, but this is only the beginning.
The challenge is to exploit all the tricks that the language offers to reduce this character set below the upper limit of 21 characters.
However, it’s important to note that this approach differs from Brainfuck.
Brainfuck is a proper programming language where each character corresponds to a specific instruction.
Here, we are simply leveraging PHP functions and syntax to minimize the number of characters used; this does not constitute a new language.

Before we continue, let’s explore two simple ideas that illustrate the mindset needed to advance further.
First, we generate all input integers for the `chr()` function with 10 digits, which turns out to be excessive.
Our goal is to use the fewest different characters possible, rather than aiming for the smallest program, and we are not prioritizing performance or readability.
All strictly positive integers can be computed by adding 1 repeatedly; for example, instead of writing `3` we can write `1+1+1`.
Although we can't compute `0` this way, it’s not an issue for `chr()`, since the function applies an algorithm similar to modulo,
meaning that `chr(0)` has the same result as `char(256)`.

<p id="example-5" class="codeblock-label">Example 5: Expression producing an exclamation mark (<code>"!"</code>), using only 7 characters: <code>chr()1+</code></p>
```php
"!" === chr(1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1+1)
```

So far, we have reduced our character count to 13 (`eval()chr.1+;`)  just by employing some simple math and carefully reviewing the documentation.
As an aside, did you know that the last semicolon is optional as long as we use a closing tag? With that, we can cut down to 12 characters: `eval()chr.1+`.

<p id="example-6" class="codeblock-label">Example 6: PHP "Hello World" without trailing comma</p>
```php
<?php
echo "Hello World"
?>
```

## Strings are functions

Our current proposal uses eight characters (`chr()1+.`) to produce all strings, how could we improve this?
Once again, the solution involves using strings themselves.
We will take advantage of two flexible features of PHP:
* [Variable Functions](https://www.php.net/manual/en/functions.variable-functions.php), allowing us to call a function through a string that contains the name of the target function: `"chr"(101)`
* [Type Juggling](https://www.php.net/manual/en/language.types.type-juggling.php), to convert strings to numbers when needed: `chr("101")`
If we manage to create a `"chr"` string and all digit strings, we can then make calls like `"chr"("101")` to generate more strings.
It may seem like a chicken-and-egg problem, but we just need a way to create these short strings, ideally using less than eight characters.

### Bitwise XOR
In PHP, the caret character (`^`) represents the [_XOR_ bitwise operator](https://www.php.net/manual/en/language.operators.bitwise.php),
which is an exclusive _OR_.
This operator compares two integers bit by bit and produces a new number where bits are set to 1 if and only if the corresponding bits in the input numbers are different.
While it is designed to work with integers, the _XOR_ operator also functions with strings, as strings are composed of bytes.

<p id="example-7" class="codeblock-label">Example 7: Bitwise XOR operator</p>
```php
60 === (15 ^ 51);
"chr" === ("RZA" ^ "123");
```

PHP is inherently flexible with types, and it often performs type juggling between numbers and number-containing strings.
We can exploit this by using the concatenation operator (`.`) along with the _XOR_ operator (`^`), leveraging the following two properties:
* Concatenating two numbers produces a string: `(9).(9) === "99"`
* _XORing_ a string-number with a number produces an integer: `("99" ^ 0) === 99`

By utilizing these properties, we can generate all digits from characters like `9.^()`, like in [Example 8](#example-8).
However, to create the `"chr"` string, we need another trick since we require new characters to incorporate into our _XOR_ operator.

<p id="example-8" class="codeblock-label">Example 8: Producing some numbers from characters <code>9.^()</code></p>
```php
9 === 9;
0 === (9^9);
1 === ((9^9).(9^99)^((9).(9)^(9).(9))^(9^9));
2 === (((9^9).(9^99)^((9).(9)^(9).(9))^(9^9))^((9^99).(9)^((9).(9)^(9).(9))^(9)));
3 === ((9^99).(9)^((9).(9)^(9).(9))^(9));
5 === ((999^(9).(9^9)).(9)^(9).(9^9)^(9^9).(9^9)^(9^9));
8 === ((9)^((9^9).(9^99)^((9).(9)^(9).(9))^(9^9)));
```

### The infinity trick

When a number exceeds what PHP can handle, the language represents it as the constant `INF`, but this constant does not have any actual value — it is simply `INF`.
We can produce `INF` by constructing a large number composed of a single digit repeated numerous times, such as a number made up of 309 nines.
Concatenating a final nine to this `INF` value yields the string `"INF9"`, constructed solely from `9.^()`.

<p id="example-9" class="codeblock-label">Example 9: String <code>"INF9"</code> produced from characters <code>9.()</code></p>
```php
"INF9" === (999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999).(9)
```

Although it may not be immediately clear, this is a significant advancement.
These new characters enable us to create the `"CHr"` string (see [Example 10](#example-10)) by using our _XOR_ operator alongside some intermediate string numbers developed from the previous method.
It's worth noting that we are mixing upper and lower case, but this poses no problem for PHP; function names are not case-sensitive.


<p id="example-10" class="codeblock-label">Example 10: String <code>"CHr"</code> created from intermediate strings</p>
```php
"CHr" === ("INF9" ^ "334" ^ "95\0")
```

Now, with `9.^()`, we can generate the `"CHr"` string, all digit strings, and from there produce any string.
When combined with the `eval()` instruction, we can execute any possible code using just `eval()9.^`, which totals nine characters.
But what about the `eval()` function? Why can't we call it directly from the string `"eval"`?
Unfortunately, `eval()` is not a standard function; it is a language construct.
PHP must recognize the `eval` token to call the corresponding function in its engine.
Before PHP 8.0, we had the [`create_function()`](https://www.php.net/manual/en/function.create-function.php) function, which could create a function from a string.
This function was essentially a less efficient clone of `eval()` and became obsolete with the introduction of closures in PHP 5.3, leading to its deprecation and eventual removal from the language.
Fortunately, PHP has also added several new features.

## _Hacking_ PHP with FFI

FFI stands for _Foreign Function Interface_.
It was introduced in PHP 7.4 and allows PHP to call C functions directly.
This feature simplifies the development of new functionalities that rely on external C libraries without the need to write a dedicated PHP extension in C.
Once you load a library into memory and know the exact signature of one of its functions, you can call that function.
Interestingly, this also applies to the PHP engine itself, as internal C functions are already loaded in memory.
Therefore, knowing the prototype of a C function is sufficient for making calls to it.

One specific function we are interested in is [`zend_eval_string()`](https://github.com/php/php-src/blob/master/Zend/zend_execute_API.c)
which is the C function behind the `eval` language construct.

<p id="example-11" class="codeblock-label">Example 11: Prototype of <code>zend_eval_string</code> function</p>
```c
zend_result zend_eval_string(
    const char *str,
    zval *retval_ptr,
    const char *string_name
);
```

Let's examine the input parameters:
* `str`: This is a string that contains the code to be executed.
* `retval_ptr`: This is an optional pointer to a variable where the result of the evaluated expression will be stored.
* `string_name`: This is an identifier that provides context for the evaluated code.

The returned value is similar to a boolean flag to indicate whether the evaluation was successful.
Since we are working with a C function, it is crucial to declare each type correctly; otherwise, FFI will not be able to determine their size and how to map them to PHP types.
C strings (`char*`) and integers can be easily handled, as they are natively mapped to PHP strings and integers.
However, for all other structures, we need to declare them, which can be challenging since we should accurately describe them by copying the C definitions into a PHP string.
Consequently, we must find a workaround to manage the `zval*` type (for `retval_ptr` parameter) and the `zend_result` type (for return type).

The C compiler, along with the FFI, requires knowledge of the memory size for each variable and return type.
However, substituting one 8-bit structure for another 8-bit structure is acceptable from a memory point of view (although mostly discouraged in a real-life application).
Let’s explore whether we can identify some compatible types.
* The `zval* `type is a pointer, specifically a memory address represented by an integer value.
Since it is optional (in this case), it can also accept a special `NULL` pointer, corresponding to the `0` value.
The size of a pointer can vary depending on the target architecture (such as 32 bits, 64 bits, or more).
Fortunately, there is a type called `intptr_t` that is guaranteed to be large enough to store a pointer.
Using this type, FFI will cast a zero value into a `NULL` pointer.
* The `zend_result` type is an alias that represents an enumeration (`enum`) with two possible values: `SUCCESS` and `FAILURE`.
The size of an enum may vary depending on the compiler and the values it contains.
Defining a similar enum with two values is concise and will use the same amount of memory.
Therefore, we can employ `enum{s,f}` as a compatible return type.

Concerning the `const` keyword, it serves as a safeguard in the language and does not affect memory storage; therefore, it can be safely removed.
By utilizing the tips provided, we can redefine the `zend_eval_string()` function with a corresponding signature that uses basic types, eliminating the need for redefinition.

<p id="example-12" class="codeblock-label">Example 12: Simplified prototype of <code>zend_eval_string</code> function</p>
```c
enum{s,f} zend_eval_string(
    char *str,
    intptr_t retval_ptr,
    char *string_name
);
```

Now, let's illustrate in [Example 13](#example-13) how we can evaluate some code without using `eval()`, thanks to [`FFI::cdef()`](https://www.php.net/manual/en/ffi.cdef.php).
To obtain a `NULL` pointer, we use the string `"0"`, which PHP converts to `0` (int) through type juggling, and then to the `NULL` pointer by FFI.
For the identifier parameter, we simply use an empty string.

<p id="example-13" class="codeblock-label">Example 13: Evaluating a string with FFI</p>
```php
FFI::cdef(
    'enum{s,f}zend_eval_string(char*,intptr_t,char*);'
)->zend_eval_string('echo "Hello World";', '0', '')
```

We can further simplify this by leveraging variable functions and callable arrays.

<p id="example-14" class="codeblock-label">Example 14: Evaluating a string with FFI, using variable functions and callable arrays</p>
```php
[
    'FFI::cdef'(
        'enum{s,f}zend_eval_string(char*,intptr_t,char*);'
    ),
    'zend_eval_string'
]('echo "Hello World";', '0', '')
```

As seen previously, all the strings used above can be generated from the characters `9.^()`.
Additionally, we require the characters `[],` to call the FFI function, which brings us down to just 8 characters: `9.^()[],`.

## Creating arrays from strings

To remove the characters `[],`, we will utilize several approaches.
Firstly, we can create arrays of scalar values by deserializing a JSON string using the [`json_decode()`](https://www.php.net/manual/en/function.json-encode.php) function. 

<p id="example-15" class="codeblock-label">Example 15: Creating an array from a string</p>
```php
json_decode('["echo \"Hello World\";", "0", ""]') === [
    'echo "Hello World";',
    '0',
    ''
]
```

Additionally, we can unpack function parameters from an array using the spread operator ([`...`](https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list)),
which allows us to reduce the number of commas required when passing multiple parameters to a function.

<p id="example-16" class="codeblock-label">Example 16: Replacing function parameters by unpacked arrays</p>
```php
[
    'FFI::cdef'(
        'enum{s,f}zend_eval_string(char*,intptr_t,char*);'
    ),
    'zend_eval_string'
](...'json_decode'('["echo \"Hello World\";", "0", ""]'))
```

However, this technique does not directly help us eliminate the last brackets since we need to handle a function call within an array to create the FFI instance.
A workaround is to use the [`array_map()`](https://www.php.net/manual/en/function.array-map.php) function, which applies a callable to elements extracted from different input arrays.
Yet, since this callable must be applied to all elements of the array, we cannot use the [`FFI::cdef()`](https://www.php.net/manual/en/ffi.cdef.php) function directly.
Instead, we will employ the [`call_user_func()`](https://www.php.net/manual/en/function.call-user-func.php) function to apply `FFI::cdef()` for creating the instance, and then call [`strval()`](https://www.php.net/manual/en/function.strval.php) on the second element.
Invoking `strval()` on a string will have no effect, which is exactly what we need; this allows us to create a callable array with an FFI instance.

<p id="example-17" class="codeblock-label">Example 17: Using <code>array_map()</code> to create FFI instance</p>
```php
'array_map'(
    'call_user_func',
    ['FFI::cdef', 'strval'],
    [
        'enum{s,f}zend_eval_string(char*,intptr_t,char*);',
        'zend_eval_string'
    ]
)(...'json_decode'('["echo \"Hello World\";", "0", ""]'))
```

Once again, by using `json_decode()` and the spread operator (`...`) we can now write all our FFI logic with strings without needing the characters `[],`.

<p id="example-18" class="codeblock-label">Example 18: "Hello World" that could be written only from strings and <code>().^</code> characters</p>
```php
'array_map'(
    ...'json_decode'(
      '[
        "call_user_func",
        ["FFI::cdef","strval"],
        [
          "enum{s,f}zend_eval_string(char*,intptr_t,char*);",
          "zend_eval_string"
        ]
      ]'
    )
)(...'json_decode'('["echo \"Hello World\";", "0", ""]'))
```

With this approach, we can express all possible strings and call the `eval()` function through FFI using just five characters!
Consequently, our _"Hello World"_ program can be written with around 118K bytes of code, utilizing merely `9.^()` characters.
A small excerpt is included below, but you can view the full code [here](hello_world_5.html).

<p id="example-19" class="codeblock-label">Example 19: <b>Excerpt</b> of the "Hello World" written only from <code>9.^()</code> characters</p>
```php
<?php
((((999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999).(9)^((9^99).(9)^((9).(9)^(9).(9))^(9)).((9^99)/*....*/(9)^((9).(9)^(9).(9))^(9)).((9^99).(9)^((9).(9)^(9).(9))^(9)).((9).(99^(((9^9).(9)^(9^99).(9)^(9).(9))^(9^9)))^(9).(9^99)^(9^9).(9^9)^(9^9))^(9).((999^(9).(9^9)).(9)^(9).(9^9)^(9^9).(9^9)^(9^9)).((9).(9)^(9).(9)))(((9)).(((9^99).(9)^((9).(9)^(9).(9))^(9)))^(9^9))))
?>
```

## What have we done…
At first sight, it’s remarkable that this type of program runs smoothly with a standard PHP interpreter.
Initially, nothing suggests that this could be valid PHP code, yet here we are!
Additionally, the performance overhead is minimal because PHP can pre-compute many of these constant expressions when generating opcodes.
However, the memory footprint is quite large, as the engine must load and parse everything.
Fortunately, the goal of this exercise was not to create production-ready code.
Nonetheless, it's important to remember that some malicious users might attempt to send you valid PHP code written with unexpected characters.

If you’re interested in implementation details, a tool is available that can convert your regular PHP code into this cryptic format: [PhpFk](https://github.com/b-viguier/PhpFk).
The method presented here does not claim to be the most efficient or the least verbose, so feel free to suggest your own ideas!
Although this exercise may seem pointless, I hope it illustrates how such silly constraints can help us maintain a creative mindset.