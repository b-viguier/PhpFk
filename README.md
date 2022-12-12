# PhpF**k

This repository contains some function to rewrite any Php code with only 8 characters `[(,.^')]`.
Inspired from https://github.com/splitline/PHPFuck, but updated to work on PHP 8+.


[See corresponding article for more details](https://b-viguier.github.io/PhpFk/).


## Running the code

```
php ./bin/phpfk.php "<your code>"
```

To run tests, you have first to `composer install` dependencies (PhpUnit).
```
php ./vendor/bin/phpunit ./tests
```

## Running Jekyll
```
docker run --rm -it  --volume="$PWD:/srv/jekyll" -p 4000:4000 -p 35729:35729 jekyll/jekyll jekyll serve --livereload
```
