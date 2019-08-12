<?php

namespace Spider;

use XWEB\WEB as WEB;

class Spider {
	protected $urls;
	protected $profile;
	protected $observer;

	function __construct($urls, SpiderProfile $profile, SpiderObserver $observer) {
		$this->setUrls($urls);
		if (!is_null($profile))
			$this->setProfile($profile);

		if (!is_null($observer))
			$this->setObserver($observer);
	}

	public function setUrls($urls) {
		if (!is_array($urls))
			$urls = array($urls);
		$this->urls = $urls;

		return $this;
	}

	public function addUrl($url) {
		array_push($this->getUrls(), $url);
	}

	protected function getUrls() {
		return $this->urls;
	}

	protected function getOneUrl() {
		return array_pop($this->urls);
	}

	protected function getProfile() {
		return $this->profile;
	}

	protected function getObserver() {
		return $this->observer;
	}

	public function setProfile(SpiderProfile $profile) {
		$this->profile = $profile;
		return $this;
	}

	public function setObserver(SpiderObserver $observer) {
		$this->observer = $observer;
		return $this;
	}

	public function _crawl($url) {
		WEB::$browser->navigate($url);
		WEB::$browser->wait();
		WEB::$browser->wait_js();

		$crawled = $this->observer->get();
	
		return $crawled;
	}

	public function crawl() {
		$result = array();

		while($url = $this->getOneUrl()) {
			$result = array_merge($result, $this->_crawl($url));
		}
		
		return $result;
	}

}
