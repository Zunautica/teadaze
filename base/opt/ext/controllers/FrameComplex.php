<?php
abstract class FrameComplex extends \Teadaze\ComplexController {
	private $primary = null;
	protected $templateName;

	public final function init(array $target) {
		$this->primary = $this->chainload($this->templateName, $target);
		$this->configure($target);
	}

	public final function dynamic(array $target) {}

	protected final function importMerge($name, $target) {
		$ctrl = $this->importLoad($name, $target);
		$this->primary->mergeView('view', $ctrl->getView());
	}

	protected abstract function configure(array $target);
}
