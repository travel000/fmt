<?php
function showHelp($argv, $enableCache, $inPhar) {
	echo 'Usage: ' . $argv[0] . ' [-h] --pass=Pass ', PHP_EOL;

	$options = [];
	if ($inPhar) {
		$options['--version'] = 'version';
	}

	ksort($options);
	$maxLen = max(array_map(function ($v) {
		return strlen($v);
	}, array_keys($options)));
	foreach ($options as $k => $v) {
		echo '  ', str_pad($k, $maxLen), '  ', $v, PHP_EOL;
	}

	echo PHP_EOL, 'It reads input from stdin, and outputs content on stdout.', PHP_EOL;
	echo PHP_EOL, 'It will derive "Pass" into a file in local directory appended with ".php" ("Pass.php"). Make sure it inherits from SandboxedPass.', PHP_EOL;
}

$getoptLongOptions = ['help', 'pass::'];
if ($inPhar) {
	$getoptLongOptions[] = 'version';
}
$opts = getopt('h', $getoptLongOptions);

if (isset($opts['version'])) {
	if ($inPhar) {
		echo $argv[0], ' ', VERSION, PHP_EOL;
	}
	exit(0);
}

if (!isset($opts['pass'])) {
	fwrite(STDERR, 'pass is not declared. cannot run.');
	exit(1);
}

$pass = sprintf('%s.php', basename($opts['pass']));
if (!file_get_contents($pass)) {
	fwrite(STDERR, sprintf('pass file "%s" is not found. cannot run.', $pass));
	exit(1);
}
include $pass;

if (isset($opts['h']) || isset($opts['help'])) {
	showHelp($argv, $enableCache, $inPhar);
	exit(0);
}

$fmt = new CodeFormatter(basename($opts['pass']));
echo $fmt->formatCode(file_get_contents('php://stdin'));
exit(0);
