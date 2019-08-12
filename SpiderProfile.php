<?php

namespace Spider;

class SpiderProfile {
	protected $sameDomain;
	protected $depth;

	function __construct($sameDomain = true, $depth = 1) {
		$this->setSameDomain($sameDomain);
		$this->setDepth($depth);
	}

	public function setSameDomain($sameDomain) {
		$this->sameDomain = $sameDomain;
		return $this;
	}

	public function setDepth($depth) {
		$this->depth = $depth;
		return $this;
	}

	public function isSameDomain() {
		return; //boolean
	}
}
