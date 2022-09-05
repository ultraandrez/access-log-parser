<?php

namespace AccessLogAnalyser\App\Controllers;

use AccessLogAnalyser\App\Base\Controller;
use AccessLogAnalyser\App\Services\FileService;
use AccessLogAnalyser\App\Services\LogFileAnalyserService;
use Exception;

class LogFileController extends Controller
{
	private FileService $fileService;
	private LogFileAnalyserService $logFileService;
	
	function __construct() {
		parent::__construct();
		$this->fileService = new FileService();
		$this->logFileService = new LogFileAnalyserService();
	}

	/**
	 * Получение статистики log-файла
	 *
	 * @param string $fileName - путь к файлу
	 * @return array
	 * 
	 * @throws Exception
	 */
	public function fileStatistic(string $fileName): array {
		if (!$this->fileService::isFileExists($fileName)) {
			throw new Exception('Файла не существует');
		}
		$descriptor = $this->fileService::openFile($fileName, 'r');
		$iterator = $this->fileService::readLine($descriptor);

		foreach ($iterator as $iteration) {
			$currentLogEntry = $this->logFileService->logLineToArray($iteration);
			if (!$currentLogEntry) {
				continue;
			}
			if (count($currentLogEntry) > 0) {
				$this->logFileService->addView()
					->addUrl($currentLogEntry['path'])
					->addCrawler($currentLogEntry['agent'])
					->addStatusCode($currentLogEntry['status']);
			}
		}
		$this->fileService::closeFile($descriptor);
		$result = $this->logFileService->statistic();
		return $result;
	}

	/**
	 * Преобразование данных к формату json
	 * 
	 * @param $data
	 * @return string
	 */
	public function toJson($data): string {
		return $this->response->toJson($data);
	}
}