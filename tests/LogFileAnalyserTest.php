<?php declare(strict_types=1);

namespace AccessLogAnalyser\App\Tests;

use AccessLogAnalyser\App\Controllers\LogFileController;
use PHPUnit\Framework\TestCase;

final class LogFileAnalyserTest extends TestCase
{
	private LogFileController $logFileController;

	protected function setUp(): void
	{
		$this->logFileController = new LogFileController();
	}

	/**
	 * Тест на открытие несуществующего файла
	 */
	public function testExceptionWithNoValidFileName(): void
	{
		$this->expectException(\Exception::class);
		$this->logFileController->fileStatistic('');
	}

	/**
	 * Тест на корректность результата обработки файла 
	 */
	public function testCorrectExpectationStatistic(): void
	{
		$actualFileStats = $this->logFileController->fileStatistic(__DIR__ . '/common/access.log');
		$expectation = [
			"views" => 16,
			"urls" => 5,
			"traffic" => 212816,
			"crawlers" => [
				"Firefox" => 2,
				"Internet Explorer" => 2,
				"Chrome" => 12
			],
			"statusCodes" => [
				"200" => 14,
				"301" => 2
			]
		];
		$this->assertEquals(
			$expectation,
			$actualFileStats
		);
	}
}
