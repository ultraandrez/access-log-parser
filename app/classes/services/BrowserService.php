<?php

namespace AccessLogAnalyser\App\Services;
/**
 * Author: Chris Schuld (http://chrisschuld.com/)
 */
class BrowserService {
	private string $_agent = '';
	private string $_browser_name = '';

	const BROWSER_UNKNOWN = 'unknown';

	const BROWSER_OPERA = 'Opera';
	const BROWSER_OPERA_MINI = 'Opera Mini';
	const BROWSER_WEBTV = 'WebTV';
	const BROWSER_IE = 'Internet Explorer';
	const BROWSER_POCKET_IE = 'Pocket Internet Explorer';
	const BROWSER_KONQUEROR = 'Konqueror';
	const BROWSER_ICAB = 'iCab';
	const BROWSER_OMNIWEB = 'OmniWeb';
	const BROWSER_FIREBIRD = 'Firebird';
	const BROWSER_FIREFOX = 'Firefox';
	const BROWSER_ICEWEASEL = 'Iceweasel';
	const BROWSER_SHIRETOKO = 'Shiretoko';
	const BROWSER_MOZILLA = 'Mozilla';
	const BROWSER_AMAYA = 'Amaya';
	const BROWSER_LYNX = 'Lynx';
	const BROWSER_SAFARI = 'Safari';
	const BROWSER_IPHONE = 'iPhone';
	const BROWSER_IPOD = 'iPod';
	const BROWSER_IPAD = 'iPad';
	const BROWSER_CHROME = 'Chrome';
	const BROWSER_ANDROID = 'Android';
	const BROWSER_GOOGLEBOT = 'GoogleBot';
	const BROWSER_SLURP = 'Yahoo! Slurp';
	const BROWSER_W3CVALIDATOR = 'W3C Validator';
	const BROWSER_BLACKBERRY = 'BlackBerry';
	const BROWSER_ICECAT = 'IceCat';
	const BROWSER_NOKIA_S60 = 'Nokia S60 OSS Browser';
	const BROWSER_NOKIA = 'Nokia Browser';
	const BROWSER_MSN = 'MSN Browser';
	const BROWSER_MSNBOT = 'MSN Bot';

	const BROWSER_NETSCAPE_NAVIGATOR = 'Netscape Navigator';
	const BROWSER_GALEON = 'Galeon';
	const BROWSER_NETPOSITIVE = 'NetPositive';
	const BROWSER_PHOENIX = 'Phoenix';

	public function __construct(string $userAgent = '') {
		$this->reset();
		if ($userAgent != '') {
			$this->setUserAgent($userAgent);
		} else {
			$this->determine();
		}
	}

	/**
	 * Сброс полей класса
	 * 
	 * @return void
	 */
	public function reset(): void {
		$this->_agent = $_SERVER['HTTP_USER_AGENT'] ?? "";
		$this->_browser_name = self::BROWSER_UNKNOWN;
	}
	/**
	 * Получение названия браузера
	 * 
	 * @return string - имя браузера
	 */
	public function getBrowser(): string { 
		return $this->_browser_name; 
	}

	/**
	 * Установка значения поля с названием браузера
	 *
	 * @param string $browserName - название браузера
	 * @return string
	 */
	public function setBrowser(string $browserName): string { 
		return $this->_browser_name = $browserName; 
	}
	
	/**
	 * Get the user agent value in use to determine the browser
	 * 
	 * @return string - The user agent from the HTTP header
	 */
	public function getUserAgent(): string { 
		return $this->_agent; 
	}

	/**
	 * Установка полей класса
	 * 
	 * @param string $agent
	 * @return void
	 */
	public function setUserAgent(string $agent): void {
		$this->reset();
		$this->_agent = $agent;
		$this->determine();
	}
	/**
	 * Used to determine if the browser is actually "chromeframe"
	 * @return boolean
	 */
	public function isChromeFrame(): bool {
		return (strpos($this->_agent,'chromeframe') !== false);
	}
	/**
	 * Protected routine to calculate and determine what the browser is in use (including platform)
	 */
	protected function determine() {
		$this->checkBrowsers();
	}
	/**
	 * Protected routine to determine the browser type
	 * @return boolean
	 */
	protected function checkBrowsers(): bool {
		return (
			$this->checkBrowserWebTv() ||
			$this->checkBrowserInternetExplorer() ||
			$this->checkBrowserOpera() ||
			$this->checkBrowserGaleon() ||
			$this->checkBrowserNetscapeNavigator9Plus() ||
			$this->checkBrowserFirefox() ||
			$this->checkBrowserChrome() ||
			$this->checkBrowserOmniWeb() ||

			// common mobile
			$this->checkBrowserAndroid() ||
			$this->checkBrowseriPad() ||
			$this->checkBrowseriPod() ||
			$this->checkBrowseriPhone() ||
			$this->checkBrowserBlackBerry() ||
			$this->checkBrowserNokia() ||

			// common bots
			$this->checkBrowserGoogleBot() ||
			$this->checkBrowserMSNBot() ||
			$this->checkBrowserSlurp() ||

			// WebKit base check (post mobile and others)
			$this->checkBrowserSafari() ||

			// everyone else
			$this->checkBrowserNetPositive() ||
			$this->checkBrowserFirebird() ||
			$this->checkBrowserKonqueror() ||
			$this->checkBrowserIcab() ||
			$this->checkBrowserPhoenix() ||
			$this->checkBrowserAmaya() ||
			$this->checkBrowserLynx() ||
			$this->checkBrowserShiretoko() ||
			$this->checkBrowserIceCat() ||
			$this->checkBrowserW3CValidator() ||
			$this->checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */
		);
	}

	protected function checkBrowserBlackBerry() {
		if (stripos($this->_agent,'blackberry') !== false) {
			$this->_browser_name = self::BROWSER_BLACKBERRY;
			return true;
		}
		return false;
	}

	protected function checkBrowserGoogleBot() {
		if (stripos($this->_agent,'googlebot') !== false) {
			$this->_browser_name = self::BROWSER_GOOGLEBOT;
			return true;
		}
		return false;
	}

	protected function checkBrowserMSNBot() {
		if (stripos($this->_agent,"msnbot") !== false) {
			$this->_browser_name = self::BROWSER_MSNBOT;
			return true;
		}
		return false;
	}

	protected function checkBrowserW3CValidator() {
		if (stripos($this->_agent,'W3C-checklink') !== false) {
			$this->_browser_name = self::BROWSER_W3CVALIDATOR;
			return true;
		} else if (stripos($this->_agent,'W3C_Validator') !== false) {
			$this->_browser_name = self::BROWSER_W3CVALIDATOR;
			return true;
		}
		return false;
	}

	protected function checkBrowserSlurp() {
		if( stripos($this->_agent,'slurp') !== false ) {
			$this->_browser_name = self::BROWSER_SLURP;
			return true;
		}
		return false;
	}

	protected function checkBrowserInternetExplorer() {

		if (stripos($this->_agent,'microsoft internet explorer') !== false) {
			$this->setBrowser(self::BROWSER_IE);
			return true;
		}
		else if (stripos($this->_agent,'msie') !== false && stripos($this->_agent,'opera') === false) {
			// See if the browser is the odd MSN Explorer
			if( stripos($this->_agent,'msnb') !== false ) {
				$this->setBrowser(self::BROWSER_MSN);
				return true;
			}
			$this->setBrowser(self::BROWSER_IE);
			return true;
		}
		else if (stripos($this->_agent,'mspie') !== false || stripos($this->_agent,'pocket') !== false) {
			$this->setBrowser( self::BROWSER_POCKET_IE );
			return true;
		}
		return false;
	}

	protected function checkBrowserOpera() {
		if (stripos($this->_agent,'opera mini') !== false) {
			$this->_browser_name = self::BROWSER_OPERA_MINI;
			return true;
		} else if (stripos($this->_agent,'opera') !== false) {
			$this->_browser_name = self::BROWSER_OPERA;
			return true;
		}
		return false;
	}

	protected function checkBrowserChrome() {
		if (stripos($this->_agent,'Chrome') !== false) {
			$this->setBrowser(self::BROWSER_CHROME);
			return true;
		}
		return false;
	}

	protected function checkBrowserWebTv() {
		if (stripos($this->_agent,'webtv') !== false) {
			$this->setBrowser(self::BROWSER_WEBTV);
			return true;
		}
		return false;
	}

	protected function checkBrowserNetPositive() {
		if (stripos($this->_agent,'NetPositive') !== false) {
			$this->setBrowser(self::BROWSER_NETPOSITIVE);
			return true;
		}
		return false;
	}

	protected function checkBrowserGaleon() {
		if (stripos($this->_agent,'galeon') !== false) {
			$this->setBrowser(self::BROWSER_GALEON);
			return true;
		}
		return false;
	}

	protected function checkBrowserKonqueror() {
		if (stripos($this->_agent,'Konqueror') !== false) {
			$this->setBrowser(self::BROWSER_KONQUEROR);
			return true;
		}
		return false;
	}

	protected function checkBrowserIcab() {
		if (stripos($this->_agent,'icab') !== false) {
			$this->setBrowser(self::BROWSER_ICAB);
			return true;
		}
		return false;
	}

	protected function checkBrowserOmniWeb() {
		if (stripos($this->_agent,'omniweb') !== false) {
			$this->setBrowser(self::BROWSER_OMNIWEB);
			return true;
		}
		return false;
	}

	protected function checkBrowserPhoenix() {
		if (stripos($this->_agent,'Phoenix') !== false) {
			$this->setBrowser(self::BROWSER_PHOENIX);
			return true;
		}
		return false;
	}

	protected function checkBrowserFirebird() {
		if (stripos($this->_agent,'Firebird') !== false) {
			$this->setBrowser(self::BROWSER_FIREBIRD);
			return true;
		}
		return false;
	}

	protected function checkBrowserNetscapeNavigator9Plus() {
		if (stripos($this->_agent,'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i',$this->_agent,$matches)) {
			$this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
			return true;
		}
		else if (stripos($this->_agent,'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i',$this->_agent,$matches)) {
			$this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
			return true;
		}
		return false;
	}

	protected function checkBrowserShiretoko() {
		if (stripos($this->_agent,'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i',$this->_agent,$matches)) {
			$this->setBrowser(self::BROWSER_SHIRETOKO);
			return true;
		}
		return false;
	}

	protected function checkBrowserIceCat() {
		if (stripos($this->_agent,'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i',$this->_agent,$matches)) {
			$this->setBrowser(self::BROWSER_ICECAT);
			return true;
		}
		return false;
	}

	protected function checkBrowserNokia() {
		if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i",$this->_agent,$matches)) {
			if (stripos($this->_agent,'Series60') !== false || strpos($this->_agent,'S60') !== false) {
				$this->setBrowser(self::BROWSER_NOKIA_S60);
			}
			else {
				$this->setBrowser(self::BROWSER_NOKIA);
			}
			return true;
		}
		return false;
	}

	protected function checkBrowserFirefox() {
		if (stripos($this->_agent,'safari') === false) {
			if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i",$this->_agent,$matches)) {
				$this->setBrowser(self::BROWSER_FIREFOX);
				return true;
			}
			else if (preg_match("/Firefox$/i",$this->_agent,$matches)) {
				$this->setBrowser(self::BROWSER_FIREFOX);
				return true;
			}
		}
		return false;
	}

	protected function checkBrowserIceweasel() {
		if (stripos($this->_agent,'Iceweasel') !== false) {
			$this->setBrowser(self::BROWSER_ICEWEASEL);
			return true;
		}
		return false;
	}

	protected function checkBrowserMozilla() {
		if (stripos($this->_agent,'mozilla') !== false  && preg_match('/rv:[0-9].[0-9][a-b]?/i',$this->_agent) && stripos($this->_agent,'netscape') === false) {
			$this->setBrowser(self::BROWSER_MOZILLA);
			return true;
		} else if (stripos($this->_agent,'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i',$this->_agent) && stripos($this->_agent,'netscape') === false) {
			$this->setBrowser(self::BROWSER_MOZILLA);
			return true;
		} else if (stripos($this->_agent,'mozilla') !== false  && preg_match('/mozilla\/([^ ]*)/i',$this->_agent,$matches) && stripos($this->_agent,'netscape') === false) {
			$this->setBrowser(self::BROWSER_MOZILLA);
			return true;
		}
		return false;
	}

	protected function checkBrowserLynx() {
		if (stripos($this->_agent,'lynx') !== false) {
			$this->setBrowser(self::BROWSER_LYNX);
			return true;
		}
		return false;
	}

	protected function checkBrowserAmaya() {
		if (stripos($this->_agent,'amaya') !== false) {
			$this->setBrowser(self::BROWSER_AMAYA);
			return true;
		}
		return false;
	}

	protected function checkBrowserSafari() {
		if (stripos($this->_agent,'Safari') !== false && stripos($this->_agent,'iPhone') === false && stripos($this->_agent,'iPod') === false) {
			$this->setBrowser(self::BROWSER_SAFARI);
			return true;
		}
		return false;
	}

	protected function checkBrowseriPhone() {
		if (stripos($this->_agent,'iPhone') !== false) {
			$this->setBrowser(self::BROWSER_IPHONE);
			return true;
		}
		return false;
	}

	protected function checkBrowseriPad() {
		if (stripos($this->_agent,'iPad') !== false) {
			$this->setBrowser(self::BROWSER_IPAD);
			return true;
		}
		return false;
	}

	protected function checkBrowseriPod() {
		if (stripos($this->_agent,'iPod') !== false) {
			$this->setBrowser(self::BROWSER_IPOD);
			return true;
		}
		return false;
	}

	protected function checkBrowserAndroid() {
		if (stripos($this->_agent,'Android') !== false) {
			$this->setBrowser(self::BROWSER_ANDROID);
			return true;
		}
		return false;
	}
}