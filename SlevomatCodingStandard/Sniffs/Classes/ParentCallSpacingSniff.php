<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use SlevomatCodingStandard\Sniffs\ControlStructures\AbstractControlStructureSpacing;
use function array_key_exists;
use function array_merge;
use function in_array;
use const T_OPEN_SHORT_ARRAY;
use const T_PARENT;
use const T_RETURN;
use const T_YIELD;
use const T_YIELD_FROM;

class ParentCallSpacingSniff extends AbstractControlStructureSpacing
{

	/** @var int */
	public $linesCountBeforeParentCall = 1;

	/** @var int */
	public $linesCountBeforeFirstParentCall = 0;

	/** @var int */
	public $linesCountAfterParentCall = 1;

	/** @var int */
	public $linesCountAfterLastParentCall = 0;

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param File $phpcsFile
	 * @param int $parentPointer
	 */
	public function process(File $phpcsFile, $parentPointer): void
	{
		$tokens = $phpcsFile->getTokens();

		if (array_key_exists('nested_parenthesis', $tokens[$parentPointer])) {
			return;
		}

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $parentPointer - 1);
		if (in_array($tokens[$previousPointer]['code'], Tokens::$castTokens, true)) {
			$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $previousPointer - 1);
		}

		$tokensToIgnore = array_merge(
			Tokens::$assignmentTokens,
			Tokens::$equalityTokens,
			[T_RETURN, T_YIELD, T_YIELD_FROM, T_OPEN_SHORT_ARRAY]
		);
		if (in_array($tokens[$previousPointer]['code'], $tokensToIgnore, true)) {
			return;
		}

		parent::process($phpcsFile, $parentPointer);
	}

	/**
	 * @return string[]
	 */
	protected function getSupportedTokens(): array
	{
		return [T_PARENT];
	}

	/**
	 * @return string[]
	 */
	protected function getTokensToCheck(): array
	{
		return ['T_PARENT'];
	}

	protected function getLinesCountBefore(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountBeforeParentCall);
	}

	protected function getLinesCountBeforeFirst(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountBeforeFirstParentCall);
	}

	protected function getLinesCountAfter(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountAfterParentCall);
	}

	protected function getLinesCountAfterLast(): int
	{
		return SniffSettingsHelper::normalizeInteger($this->linesCountAfterLastParentCall);
	}

}
