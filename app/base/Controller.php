<?php

namespace AccessLogAnalyser\App\Base;

class Controller
{
	/**
	 * Поле класса для работы с ответами сервера
	 */
	protected Response $response;
	
	function __construct() {
		$this->response = new Response();
	}
}