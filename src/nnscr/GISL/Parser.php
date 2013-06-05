<?php
namespace nnscr\GISL;

use nnscr\GISL\Exception\ParseErrorException;
use nnscr\GISL\Node\Node;
use nnscr\GISL\Node\PrintNode;
use nnscr\GISL\Node\TextNode;

class Parser {
	/**
	 * @var TokenStream
	 */
	private $stream;

	/**
	 * @var ExpressionParser
	 */
	private $expressionParser;

	public function __construct() {

	}

	/**
	 * Get the token stream
	 *
	 * @return TokenStream
	 */
	public function getStream() {
		return $this->stream;
	}

	/**
	 * Parses the given TokenStream into a Node
	 *
	 * @param  TokenStream $stream
	 * @return Node
	 * @throws Exception\ParseErrorException
	 */
	public function parse(TokenStream $stream) {
		if(!$this->expressionParser) {
			$this->expressionParser = new ExpressionParser($this);
		}

		$this->stream = $stream;

		$tree = [];

		while(!$this->stream->isEOF()) {
			$current = $this->stream->current();
			switch($current->getType()) {
				case Token::TYPE_TEXT:
					$token = $this->stream->next();
					$tree[] = new TextNode($token->getValue());
					break;

				case Token::TYPE_BLOCK_START:
					$this->stream->next();
					$expr = $this->expressionParser->parseExpression();
					$this->stream->expect(Token::TYPE_BLOCK_END);
					$tree[] = new PrintNode($expr);
					break;

				default:
					throw ParseErrorException::unexpectedToken($current);
			}
		}

		return new Node($tree);
	}

}
