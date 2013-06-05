<?php
namespace nnscr\GISL;

class GISL {
	/**
	 * @var Lexer
	 */
	private $lexer;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var Interpreter
	 */
	private $interpreter;

	/**
	 * @return \nnscr\GISL\Lexer
	 */
	public function getLexer() {
		if(!$this->lexer) {
			$this->lexer = new Lexer();
		}

		return $this->lexer;
	}

	/**
	 * @param  \nnscr\GISL\Lexer $lexer
	 * @return $this
	 */
	public function setLexer($lexer) {
		$this->lexer = $lexer;
		return $this;
	}

	/**
	 * @return \nnscr\GISL\Parser
	 */
	public function getParser() {
		if(!$this->parser) {
			$this->parser = new Parser();
		}

		return $this->parser;
	}

	/**
	 * @param  \nnscr\GISL\Parser $parser
	 * @return $this
	 */
	public function setParser($parser) {
		$this->parser = $parser;
		return $this;
	}

	/**
	 * @return \nnscr\GISL\Interpreter
	 */
	public function getInterpreter() {
		if(!$this->interpreter) {
			$this->interpreter = new Interpreter();
		}

		return $this->interpreter;
	}

	/**
	 * @param  \nnscr\GISL\Interpreter $interpreter
	 * @return $this
	 */
	public function setInterpreter($interpreter) {
		$this->interpreter = $interpreter;
		return $this;
	}

	/**
	 * Executes the given GISL code
	 *
	 * @param string $gisl
	 * @param array $identifiers
	 * @return string
	 */
	public function execute($gisl, array $identifiers = []) {
		$tokenStream = $this->getLexer()->tokenize($gisl);
		$parseTree   = $this->getParser()->parse($tokenStream);

		return $this->getInterpreter()->interpret($parseTree, $identifiers);
	}
}
