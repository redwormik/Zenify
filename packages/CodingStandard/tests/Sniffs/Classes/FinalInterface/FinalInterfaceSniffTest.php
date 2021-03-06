<?php

declare(strict_types = 1);

namespace Zenify\CodingStandard\Tests\Sniffs\Classes\FinalInterface;

use PHPUnit\Framework\TestCase;
use Zenify\CodingStandard\Tests\CodeSnifferRunner;
use ZenifyCodingStandard\Sniffs\Classes\FinalInterfaceSniff;


final class FinalInterfaceSniffTest extends TestCase
{

	public function testDetection()
	{
		$codeSnifferRunner = new CodeSnifferRunner(FinalInterfaceSniff::NAME);

		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct2.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct3.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct4.php'));
	}


	public function testFixing()
	{
		$codeSnifferRunner = new CodeSnifferRunner(FinalInterfaceSniff::NAME);

		$fixedContent = $codeSnifferRunner->getFixedContent(__DIR__ . '/wrong.php');

		$this->assertSame(file_get_contents(__DIR__ . '/wrong-fixed.php'), $fixedContent);
	}

}
