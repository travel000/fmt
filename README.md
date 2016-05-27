phpfmt
======

***[This project follows a Code of Conduct.](https://github.com/phpfmt/code-of-conduct)***

## Build statuses
- Master: [![Build Status](https://travis-ci.org/phpfmt/fmt.svg?branch=master)](https://travis-ci.org/phpfmt/fmt)

[![Throughput Graph](https://graphs.waffle.io/phpfmt/fmt/throughput.svg)](https://waffle.io/phpfmt/fmt/metrics)

## Requirements
- PHP >= 7.0.0 to run the formatter. Note that the formatter can parse and format even a PHP file version 4 in case needed. HHVM is not supported.

## Editor Plugins

* [Sublime Text 3](https://github.com/phpfmt/sublime-phpfmt)
* [Vim](https://github.com/phpfmt/vim-phpfmt)
* [PHPStorm](https://github.com/phpfmt/phpstorm-phpfmt)

## Usage

```ShellSession
$ php fmt.phar filename.php

$ php fmt.phar --help
Usage: fmt.phar [-hv] [-o=FILENAME] [--config=FILENAME] [--cache[=FILENAME]] [options] <target>
  --cache[=FILENAME]                cache file. Default: .php.tools.cache
  --config=FILENAME                 configuration file. Default: .phpfmt.ini
  --dry-run                         Runs the formatter without atually changing files;
                                    returns exit code 1 if changes would have been applied
  --enable_auto_align               disable auto align of ST_EQUAL and T_DOUBLE_ARROW
  --exclude=pass1,passN,...         disable specific passes
  --help-pass                       show specific information for one pass
  --ignore=PATTERN-1,PATTERN-N,...  ignore file names whose names contain any PATTERN-N
  --lint-before                     lint files before pretty printing (PHP must be declared in %PATH%/$PATH)
  --list                            list possible transformations
  --list-simple                     list possible transformations - greppable
  --no-backup                       no backup file (original.php~)
  --passes=pass1,passN,...          call specific compiler pass
  --profile=NAME                    use one of profiles present in configuration file
  --smart_linebreak_after_curly     convert multistatement blocks into multiline blocks
  --version                         version
  --visibility_order                fixes visibiliy order for method in classes - PSR-2 4.2
  --yoda                            yoda-style comparisons
  -h, --help                        this help message
  -o=-                              output the formatted code to standard output
  -o=file                           output the formatted code to "file"
  -v                                verbose

If <target> is "-", it reads from stdin
```

# What does the Code Formatter do?

### K&R configuration
<table>
<tr>
<td>Before</td>
<td>After</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
for($i = 0; $i &lt; 10; $i++)
{
if($i%2==0)
echo "Flipflop";
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
for ($i = 0; $i &lt; 10; $i++) {
	if ($i%2 == 0) {
		echo "Flipflop";
	}
}
</code></pre>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
$a = 10;
$otherVar = 20;
$third = 30;
</code></pre>
</td>
<td>
<pre><code>&lt;?php
$a        = 10;
$otherVar = 20;
$third    = 30;
</code></pre>
<i>This can be disabled with the option "disable_auto_align"</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
namespace NS\Something;
use \OtherNS\C;
use \OtherNS\B;
use \OtherNS\A;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
</td>
<td>
<pre><code>&lt;?php
namespace NS\Something;

use \OtherNS\A;
use \OtherNS\C;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
<i>note how it sorts the use clauses, and removes unused ones</i>
</td>
</tr>
</table>