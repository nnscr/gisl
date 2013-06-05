<?php
namespace nnscr\GISL;

use nnscr\GISL\Exception\SyntaxErrorException;

class Lexer {
	const STATE_TEXT   = 's_text';
	const STATE_BLOCK  = 's_block';

	// identifiers like function names
	const REGEX_NAME   = '/[a-zA-Z_][a-zA-Z_0-9]*/A';
	const REGEX_NUMBER = '/[0-9]+(?:\.[0-9]+)?/A';
	const REGEX_STRING = '/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/As';
	const REGEX_IDENTIFIER = '/\[([^\[\]\\\\]*(?:\\\\.[^\[\]\\\\]*)*)\]/As';

	const REGEX_LEX_START = '/(?<!@)@\{\s*/';
	const REGEX_BLOCK_END = '/\s*\}/A';

	const REGEX_OPERATOR = '/\+|-|\*|\//A';

	const PUNCTUATION = '.,()[]';

	/**
	 * The gisl code that should be scanned
	 *
	 * @var string
	 */
	private $script;

	/**
	 * The current cursor position
	 *
	 * @var int
	 */
	private $cursor;

	/**
	 * The end position of the gisl code
	 *
	 * @var int
	 */
	private $end;

	/**
	 * The current block position (every position points to the start of a block)
	 *
	 * @var int
	 */
	private $position;

	/**
	 * Array holding all found block positions (see $position)
	 *
	 * @var array
	 */
	private $positions;

	/**
	 * The current lexer state. Value is one of the STATE_* constants above
	 *
	 * @var string
	 */
	private $state;

	/**
	 * The state stack
	 *
	 * @var array
	 */
	private $stateStack;

	/**
	 * An array holding all opened brackets
	 *
	 * @var array
	 */
	private $brackets;

	/**
	 * The tokens for the current script (the result of the scanning)
	 *
	 * @var Token[]
	 */
	private $tokens;

	/**
	 * Scan the given $script and return a TokenStream holding all found tokens
	 *
	 * @param  string $script
	 * @throws \LogicException
	 * @throws Exception\SyntaxErrorException
	 * @return TokenStream
	 */
	public function tokenize($script) {
		// reset the lexer
		$this->tokens     = [];
		$this->state      = self::STATE_TEXT;
		$this->stateStack = [];
		$this->brackets   = [];

		// set the working data
		$this->script = $script;
		$this->cursor = 0;
		$this->end    = strlen($script);

		// find all positions of start blocks
		preg_match_all(self::REGEX_LEX_START, $this->script, $matches, PREG_OFFSET_CAPTURE);
		$this->position  = -1;
		$this->positions = $matches;

		while($this->cursor < $this->end) {
			switch($this->state) {
				case self::STATE_TEXT:
					$this->lexText();
					break;

				case self::STATE_BLOCK:
					$this->lexBlock();
					break;

				default:
					throw new \LogicException(sprintf('Unknown state %s', $this->state));
			}
		}

		$this->pushToken(Token::TYPE_EOF);

		if(0 !== count($this->brackets)) {
			throw SyntaxErrorException::unclosedBracket(array_pop($this->brackets));
		}

		return new TokenStream($this->tokens);
	}

	/**
	 * Lex a simple text. This is called when the current lexer state is STATE_TEXT.
	 */
	private function lexText() {
		if($this->position == count($this->positions[0]) - 1) {
			// no more blocks to read
			$this->pushToken(Token::TYPE_TEXT, substr($this->script, $this->cursor));
			$this->cursor = $this->end;

			return;
		}

		// get next block
		$position = $this->positions[0][++$this->position];
		while($position[1] < $this->cursor) {
			if($this->position == count($this->positions[0]) - 1) {
				return;
			}
			$position = $this->positions[0][++$this->position];
		}

		// push the latest text
		$text = substr($this->script, $this->cursor, $position[1] - $this->cursor);
		$this->pushToken(Token::TYPE_TEXT, $text);
		$this->moveCursor($text . $position[0]);

		// push block token
		$this->pushToken(Token::TYPE_BLOCK_START);
		$this->pushState(self::STATE_BLOCK);
	}

	/**
	 * Lexes a block. This is called when the current lexer state is STATE_BLOCK.
	 */
	private function lexBlock() {
		if(preg_match(self::REGEX_BLOCK_END, $this->script, $match, null, $this->cursor)) {
			// end of block reached
			$this->pushToken(Token::TYPE_BLOCK_END);
			$this->moveCursor($match[0]);
			$this->popState();
		} else {
			$this->lexExpression();
		}
	}

	/**
	 * Lexes an expression inside a block.
	 *
	 * @throws Exception\SyntaxErrorException
	 */
	private function lexExpression() {
		if(preg_match('/\s+/A', $this->script, $match, null, $this->cursor)) {
			// skip whitespaces
			$this->moveCursor($match[0]);

			if($this->cursor >= $this->end) {
				throw SyntaxErrorException::unclosedBlock();
			}
		}

		if(preg_match(self::REGEX_OPERATOR, $this->script, $match, null, $this->cursor)) {
			// operator
			$this->pushToken(Token::TYPE_OPERATOR, $match[0]);
			$this->moveCursor($match[0]);
		} else if(preg_match(self::REGEX_NAME, $this->script, $match, null, $this->cursor)) {
			// name (identifier)
			$this->pushToken(Token::TYPE_NAME, $match[0]);
			$this->moveCursor($match[0]);
		} else if(preg_match(self::REGEX_NUMBER, $this->script, $match, null, $this->cursor)) {
			// number
			$number = (float)$match[0];

			if(ctype_digit($match[0]) && $number <= PHP_INT_MAX) {
				$number = (int)$number;
			}

			$this->pushToken(Token::TYPE_NUMBER, $number);
			$this->moveCursor($match[0]);
		} else if(preg_match(self::REGEX_IDENTIFIER, $this->script, $match, null, $this->cursor)) {
			// identifier
			$this->pushToken(Token::TYPE_IDENTIFIER, stripcslashes(substr($match[0], 1, -1)));
			$this->moveCursor($match[0]);
		} else if(preg_match(self::REGEX_STRING, $this->script, $match, null, $this->cursor)) {
			// string
			$this->pushToken(Token::TYPE_STRING, stripcslashes(substr($match[0], 1, -1)));
			$this->moveCursor($match[0]);
		} else if(false !== strpos(self::PUNCTUATION, $this->script[$this->cursor])) {
			// punctuation
			if(false !== strpos('({[<', $this->script[$this->cursor])) {
				// opening bracket
				$this->brackets[] = $this->script[$this->cursor];
			} else if(false !== strpos(')}]>', $this->script[$this->cursor])) {
				// closing bracket
				if(empty($this->brackets)) {
					throw SyntaxErrorException::unexpectedCharacter($this->script[$this->cursor]);
				}

				$open = array_pop($this->brackets);
				if($this->script[$this->cursor] != ($close = strtr($open, '([{<', ')]}>'))) {
					throw SyntaxErrorException::unclosedBracket($this->script[$this->cursor], $close);
				}
			}

			$this->pushToken(Token::TYPE_PUNCTUATION, $this->script[$this->cursor]);
			++$this->cursor;
		} else {
			throw SyntaxErrorException::unexpectedCharacter($this->script[$this->cursor]);
		}
	}

	/**
	 * Push a new token to the TokenStream
	 *
	 * @param string $type
	 * @param string $value
	 */
	private function pushToken($type, $value = '') {
		if(Token::TYPE_TEXT === $type && '' === $value) {
			// empty text token are ignored
			return;
		}

		$this->tokens[] = new Token($type, $value);
	}

	/**
	 * Sets $state as the new lexer state and pushes the old state to the state stack.
	 *
	 * @param string $state
	 */
	private function pushState($state) {
		$this->stateStack[] = $this->state;
		$this->state = $state;
	}

	/**
	 * Pops a state from the state stack.
	 *
	 * @throws \Exception
	 */
	private function popState() {
		if(0 === count($this->stateStack)) {
			throw new \Exception('State stack is empty');
		}

		$this->state = array_pop($this->stateStack);
	}

	/**
	 * Move the cursor forward by the number of characters in the $string.
	 *
	 * @param $string
	 */
	private function moveCursor($string) {
		$this->cursor += strlen($string);
	}
}
