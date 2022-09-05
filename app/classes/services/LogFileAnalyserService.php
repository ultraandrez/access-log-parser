<?php

namespace AccessLogAnalyser\App\Services;

class LogFileAnalyserService
{
	/**
	 * Статистика обработанного log-файла
	 * @var array
	 */
	private array $statistic = [
		'views' => 0,
		'urls' => 0,
		'traffic' => 0,
		'crawlers' => [],
		'statusCodes' => [],
	];

	/**
	 * Коллекция уникальных url из лога запросов
	 * @var array
	 */
	private array $uniqueUrls = [];

	/**
	 * Добавление просмотра страницы к статистике
	 * 
	 * @return $this
	 */
	public function addView(): LogFileAnalyserService
	{
		$this->statistic['views']++;
		return $this;
	}
	
	/**
	 * Сбор количества уникальных urls
	 * 
	 * @param string $url - строка url
	 */
	public function addUrl(string $url): LogFileAnalyserService {
		if (!in_array($url, $this->uniqueUrls)) {
			$this->uniqueUrls[] = $url;
			$this->statistic['urls'] = count($this->uniqueUrls);
		}
		return $this;
	}

	/**
	 * Накопление трафика
	 * 
	 * @param int $traffic - количество переданных бит трафика
	 */
	public function addTraffic(int $traffic): LogFileAnalyserService {
		$this->statistic['traffic'] += $traffic;
		return $this;
	}

	/**
	 * Добавляем поисковые боты к статистике
	 *
	 * @param string $agent - поисковый бот
	 * @return $this
	 */
	public function addCrawler(string $agent): LogFileAnalyserService {
		$browserInfo = new BrowserService($agent);
		$botName = $browserInfo->getBrowser();
		if (!$botName) {
			return $this;
		}
		if (!array_key_exists($botName, $this->statistic['crawlers'])) {
			$this->statistic['crawlers'][$botName] = 1;
			return $this;
		}
		$this->statistic['crawlers'][$botName] += 1;
		return $this;
	}

	/**
	 * Добавляем коды статусов запросов
	 * 
	 * @param string $statusCode - код статуса
	 * @return $this
	 */
	public function addStatusCode(string $statusCode): LogFileAnalyserService {
		if ($statusCode !== '') {
			if (!array_key_exists($statusCode, $this->statistic['statusCodes'])) {
				$this->statistic['statusCodes'][$statusCode] = 1;
				return $this;
			}
			$this->statistic['statusCodes'][$statusCode]++;
		}
		return $this;
	}


	/**
	 * Преобразование строки лог-файла к массиву с информацией о нем
	 * 
	 * @param string $logLine
	 * @return array
	 */
	public function logLineToArray(string $logLine): array {
		$pattern = '/^(\S*).*\[(.*)]\s"(\S*)\s(\S*)\s([^"]*)"\s(\S*)\s(\S*)\s"([^"]*)"\s"([^"]*)"$/';
		$logLineParameters = RegexService::regexMatches($pattern, $logLine);
	
		if (!count($logLineParameters) == 10) {
			return [];
		}
		return [
			'ip' 		=> $logLineParameters[1],
			'date' 		=> $logLineParameters[2],
			'method' 	=> $logLineParameters[3],
			'path' 		=> $logLineParameters[4],
			'protocol' 	=> $logLineParameters[5],
			'status' 	=> $logLineParameters[6],
			'bytes' 	=> $logLineParameters[7],
			'referer' 	=> $logLineParameters[8],
			'agent' 	=> $logLineParameters[9],
		];
	}

	/**
	 * Получение собранной статистики по файлу
	 * 
	 * @return array
	 */
	public function statistic(): array {
		return $this->statistic;
	}
}