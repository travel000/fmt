<?php
class ExternalPass {
	private $passName = '';

	public function __construct($passName) {
		$this->passName = $passName;
	}

	public function candidate() {
		return true;
	}

	public function format($source) {
		$descriptorspec = [
			0 => ['pipe', 'r'], // stdin is a pipe that the child will read from
			1 => ['pipe', 'w'], // stdout is a pipe that the child will write to
			2 => ['pipe', 'w'], // stderr is a file to write to
		];

		$cwd = getcwd();
		$env = [];
		$argv = $_SERVER['argv'];
		$pipes = null;

		$external = str_replace('fmt.', 'fmt-external.', $cwd . DIRECTORY_SEPARATOR . $argv[0]);

		$cmd = $_SERVER['_'] . ' ' . $external . ' --pass=' . $this->passName;
		$process = proc_open(
			$cmd,
			$descriptorspec,
			$pipes,
			$cwd,
			$env
		);
		if (!is_resource($process)) {
			fclose($pipes[0]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			proc_close($process);
			return $source;
		}
		fwrite($pipes[0], $source);
		fclose($pipes[0]);

		$source = stream_get_contents($pipes[1]);
		fclose($pipes[1]);

		fclose($pipes[2]);
		proc_close($process);
		return $source;
	}
}
