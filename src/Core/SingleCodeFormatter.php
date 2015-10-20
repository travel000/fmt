<?php
/**
 * @codeCoverageIgnore
 */
final class CodeFormatter extends BaseCodeFormatter {
	public function __construct($passName) {
		if (get_parent_class($passName) != 'SandboxedPass') {
			throw new Exception($passName . ' is not a sandboxed pass (SandboxedPass)');
		}

		$this->passes = ['ExternalPass' => new $passName()];
	}

	public function disablePass($pass) {}

	public function enablePass($pass) {}
}
