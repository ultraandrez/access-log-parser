<?php

namespace AccessLogAnalyser\App\Base;

class Response
{
	/**
	 * Преобразование данных к ответу в виде json
	 *
	 * @param array $data - массив данных
	 * @return string
	 */
	public function toJson(array $data = []): string {
		header("Content-Type: application/json");

		$result = json_encode($data);

		if ($result === false) {
			$result = json_encode(['convert to json error']);
			http_response_code(500);
		}

		return $result;
	}
}