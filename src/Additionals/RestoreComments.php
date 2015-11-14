<?php
final class RestoreComments extends AdditionalPass {
	public $commentStack = [];

	/**
	 * @codeCoverageIgnore
	 */
	public function candidate($source, $foundTokens) {
		if (isset($foundTokens[T_COMMENT])) {
			return true;
		}

		return false;
	}

	public function format($source) {
		$commentStack = array_reverse($this->commentStack);
		$this->tkns = token_get_all($source);
		$this->code = '';
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			$this->tkns[$this->ptr] = [$id, $text];
			if (T_COMMENT == $id) {
				$comment = array_pop($commentStack);
				$this->tkns[$this->ptr] = $comment;
			}
		}
		return $this->renderLight($this->tkns);
	}

	public function getDescription() {
		return 'Revert any formatting of comments content.';
	}

	public function getExample() {
		return '';
	}
}