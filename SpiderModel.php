<?php

namespace XWEB;

use XWEB\Spider as Spider;

class SpiderModel {
	protected $spider;
	protected $model;

	function __construct(Spider $spider, $model) {
		if (!is_subclass_of($model, 'Model')) {
			throw new Exception("Provided model object should be an instance of Model class!", 1);
		}

		$this->setSpider($spider);
		$this->setModel($model);
	}

	public function setSpider($spider) {
		$this->spider = $spider;
		return $this;
	}

	public function setModel($model) {
		$this->model = $model;
		return $this->model;
	}

	public function getModel() {
		return $this->model;
	}

	public function getSpider() {
		return $this->spider;
	}

	public function init() {
		$spider = $this->getSpider();
		$model = $this->getModel();

		$data = $spider->crawl();
		
		foreach($data as $key => $value) {
			$model->$key = $value;
		}

		$model->save();
	}
}
