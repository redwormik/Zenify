<?php

declare(strict_types = 1);

namespace ZenifyCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;


/**
 * Rules:
 * - Method without parameter typehints should have docblock comment.
 */
final class MethodCommentSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * @var string
	 */
	const NAME = 'ZenifyCodingStandard.Commenting.MethodComment';

	/**
	 * @return int[]
	 */
	public function register() : array
	{
		return [T_FUNCTION];
	}


	/**
	 * @param PHP_CodeSniffer_File $file
	 * @param int $position
	 */
	public function process(PHP_CodeSniffer_File $file, $position)
	{
		if ($this->hasMethodDocblock($file, $position)) {
			return;
		}

		$parameters = $file->getMethodParameters($position);
		$parameterCount = count($parameters);

		// 1. method has no parameters
		if ($parameterCount === 0) {
			return;
		}

		// 2. all methods have typehints
		if ($parameterCount === $this->countParametersWithTypehint($parameters)) {
			return;
		}

		$file->addError('Method docblock is missing, due to some parameters without typehints.', $position);
	}


	private function hasMethodDocblock(PHP_CodeSniffer_File $file, int $position) : bool
	{
		$tokens = $file->getTokens();
		$currentToken = $tokens[$position];
		$docBlockClosePosition = $file->findPrevious(T_DOC_COMMENT_CLOSE_TAG, $position);

		if ($docBlockClosePosition === FALSE) {
			return FALSE;
		}

		$docBlockCloseToken = $tokens[$docBlockClosePosition];
		if ($docBlockCloseToken['line'] === ($currentToken['line'] - 1)) {
			return TRUE;
		}

		return FALSE;
	}


	private function countParametersWithTypehint(array $parameters) : int
	{
		$parameterWithTypehintCount = 0;
		foreach ($parameters as $parameter) {
			if ($parameter['type_hint']) {
				$parameterWithTypehintCount++;
			}
		}
		return $parameterWithTypehintCount;
	}

}
