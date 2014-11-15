<?php
class ReturnNull extends FormatterPass {
	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$this->use_cache = true;
		$touched_return = false;
		while (list($index, $token) = each($this->tkns)) {
			list($id, $text) = $this->get_token($token);
			$this->ptr = $index;
			$this->cache = [];

			if (ST_PARENTHESES_OPEN == $id && $this->is_token([T_RETURN], true)) {
				$paren_count = 1;
				$touched_another_valid_token = false;
				$stack = $text;
				while (list($index, $token) = each($this->tkns)) {
					list($id, $text) = $this->get_token($token);
					$this->ptr = $index;
					$this->cache = [];
					if (ST_PARENTHESES_OPEN == $id) {
						++$paren_count;
					}
					if (ST_PARENTHESES_CLOSE == $id) {
						--$paren_count;
					}
					$stack .= $text;
					if (0 == $paren_count) {
						break;
					}
					if (
						!(
							(T_STRING == $id && strtolower($text) == 'null') ||
							ST_PARENTHESES_OPEN == $id ||
							ST_PARENTHESES_CLOSE == $id
						)
					) {
						$touched_another_valid_token = true;
					}
				}
				if ($touched_another_valid_token) {
					$this->append_code($stack, false);
				}
				continue;
			}
			if (T_STRING == $id && strtolower($text) == 'null') {
				list($prev_id, ) = $this->prev_token([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]);
				list($next_id, ) = $this->next_token([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]);
				if (T_RETURN == $prev_id && ST_SEMI_COLON == $next_id) {
					continue;
				}
			}

			$this->append_code($text, false);
		}

		return $this->code;
	}
}
