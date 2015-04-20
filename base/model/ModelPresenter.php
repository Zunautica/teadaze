<?php
namespace Teadaze;

class ModelPresenter extends Model {
	protected $model;

	public function __call($method, $arguments)
	{
		if(!method_exists($this->model, $method))
			throw new \Exception("Method does not exist for ModelPresenter ".get_class($this));
		return call_user_func_array(array($this->model, $method), $arguments);
	}
}
