<?php

namespace Spider;

use XWEB\DOM as DOM;
use XWEB\WEB as WEB;

class SpiderObserver {
	protected $getData;

	function __construct(array $getData) {
		$this->setGetData($getData);
	}

	public function setGetData($getData) {
		$this->getData = $getData;
		return $this;
	}

	public function getGetData() {
		return $this->getData;
	}

	public function get() {
		$getData = $this->getGetData();
		$result = [];

		array_map(function($data) use (&$result){
			extract($data);
			if (!isset($tag))
				$tag = 'element';

			if (!isset($method))
				$method = 'get_inner_text';

			$action = DOM::$$tag->get_by_xpath($xpath)->{$method}();

			if (isset($callback))
				if (is_callable($callback))
					$action = $callback($action);

			if (isset($json))
				if ($json)
					$action = json_encode($action);

			if (!isset($column))
				$result[] = $action;
			else $result[$column] = $action;	

		}, $getData);


		return $result;
	}

}
