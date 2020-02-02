# PatternCreator
Generates a specific, customisable & graphic pattern with php GD.

![Example of random pattern](https://raw.githubusercontent.com/theseconddre/PatternCreator/master/examples/result.jpg)

## How to install my package? :-)

Very easy: Just add the following line to your require block in composer.json
```
"require": {
	"theseconddre/pattern-creator": "dev-master"
}
```
## How to use my class?

Then if you want to **generate a blue pattern made of squares** in your browser, just type these lines (already coded in PatternCreator/examples/index.php)

```php
<?php 

// Example 1 : display pattern in browser
// See more examples in examples/index.php 

$pattern = new TheSecondDre\PatternCreator([
		'width' 	=>  1000,
		'height' 	=>  1000,
		'color' 	=>  '#0000ff',
		'pattern' 	=>  'square',
	]);

$img = $pattern->createPattern('browser');
die();
```
Of course **you can add your own pattern file in /src/img_patterns.**

