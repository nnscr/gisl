<?php
namespace nnscr\GISL;

use nnscr\GISL\Exception\ParseErrorException;
use nnscr\GISL\Node\ArrayNode;
use nnscr\GISL\Node\Expression\CallExpressionNode;
use nnscr\GISL\Node\Expression\ConstantExpressionNode;
use nnscr\GISL\Node\Expression\IdentifierExpressionNode;
use nnscr\GISL\Node\Node;
use nnscr\GISL\Node\NodeInterface;

class ExpressionParser {
	/**
	 * @var Parser
	 */
	protected $parser;

	/**
	 * @var TokenStream
	 */
	protected $stream;

	/**
	 * @param Parser $parser
	 */
	public function __construct(Parser $parser) {
		$this->parser = $parser;
	}

	/**
	 * Parse the expression that is followed next in the TokenStream of the main parser
	 *
	 * @return CallExpressionNode|NodeInterface
	 * @throws Exception\ParseErrorException
	 */
	public function parseExpression() {
		$this->stream = $this->parser->getStream();

		$token = $this->parser->getStream()->current();
		switch($token->getType()) {
			case Token::TYPE_NUMBER:
			case Token::TYPE_STRING:
				$token = $this->stream->next();
				$node = new ConstantExpressionNode($token->getValue());
				break;

			case Token::TYPE_IDENTIFIER:
				$token = $this->stream->next();
				$node = new IdentifierExpressionNode($token->getValue());
				break;

			default:
				throw ParseErrorException::unexpectedToken($token);
		}

		return $this->parsePostfixExpression($node);
	}

	/**
	 * Parse the code behind an expression (detects if there are methods to be called)
	 *
	 * @param NodeInterface $node
	 * @return CallExpressionNode|NodeInterface
	 */
	public function parsePostfixExpression(NodeInterface $node) {
		while(true) {
			$token = $this->stream->current();

			if($token->test(Token::TYPE_PUNCTUATION, '.')) {
				$this->stream->next();
				$node = $this->parseSubscriptExpression($node);
			} else {
				break;
			}
		}

		return $node;
	}

	/**
	 * Parses a subscript expression (like methods to be called)
	 *
	 * @param NodeInterface $node
	 * @return CallExpressionNode
	 * @throws Exception\ParseErrorException
	 */
	public function parseSubscriptExpression(NodeInterface $node) {
		$token = $this->stream->next();
		if(!$token->test(Token::TYPE_NAME)) {
			throw ParseErrorException::unexpectedToken($token, Token::TYPE_NAME);
		}

		$arguments = new ArrayNode();
		$fn = new ConstantExpressionNode($token->getValue());

		if($this->stream->test(Token::TYPE_PUNCTUATION, '(')) {
			foreach($this->parseArguments() as $arg) {
				$arguments->pushNode($arg);
			}
		}

		return new CallExpressionNode($node, $fn, $arguments);
	}

	/**
	 * Parses arguments from the main TokenStream. Returns the arguments as an array.
	 *
	 * @return array
	 */
	public function parseArguments() {
		$arguments = [];
		$this->stream->expect(Token::TYPE_PUNCTUATION, '(');
		while(!$this->stream->test(Token::TYPE_PUNCTUATION, ')')) {
			if(!empty($arguments)) {
				$this->stream->expect(Token::TYPE_PUNCTUATION, ',');
			}

			$arguments[] = $this->parseExpression();

		}
		$this->stream->expect(Token::TYPE_PUNCTUATION, ')');
		return $arguments;
	}
}
