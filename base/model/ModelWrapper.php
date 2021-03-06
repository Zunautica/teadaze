<?php
namespace Teadaze;
/**
 * A wrapper for models so they can act as hooks
 * 
 * If you want to have a hook for a model when called
 * by a controller, using a certain view (or any view)
 * then the model is wrapped in this class.
 *
 * Instances of this are completely transparent. The controller
 * will be unaware that it is dealing with a wrapper and will
 * interact with the model as per usual.
 *
 * The reason for having this is so you can setup hooklines
 * for particular methods of a model so the data can be processed.
 * For instance, the data format is arbitrary to the model and
 * the controller but the view may be expecting the data to be
 * converted from Markdown to HTML. You set up a hook on the model
 * based on the controller and view; when the data is passed to
 * the controller (it thinks it's talking directly to the model), 
 * it is actually passed down the hookline by the ModelWrapper and
 * then passed out to the controller. This is completely transparent;
 * all you need to do is setup the hook and hookline at the start
 */
class ModelWrapper
{
	/** @var Model $model The model being used */
	private $model;

	/** @var array $hooklines An array of hooklines */
	private $hooklines;

	private $pluginLoader;

	/**
	* Instantiate the object with a model and hooklines automatically
	*
	* @method __construct(Model $model, array $hooklines)
	* @param Model $model The model object to pass to the wrapper
	* @param array $hooklines An array of hooklines for the wrapper to use
	* @access public
	*/
	public function __construct($model, $hooklines, $pluginLoader)
	{
		$this->model = $model;
		$this->hooklines = $hooklines;
		$this->pluginLoader = $pluginLoader;
	}

	/**
	 * Magic method for transparently handling calls to the model
	 *
	 * If there is a hook on the method in question, it will deal with
	 * the hook, otherwise it will pass the data out as normal
	 * @method __call(string $method, array $arguments)
	 * @param string $method The method that is being called
	 * @param array $arguments An array of arguments passed to the method
	 * @access public
	 * @return mixed Whatever the call or hookline returns
	 */
	public function __call($method, $arguments)
	{
		if(!isset($this->hooklines[$method]))
			return call_user_func_array(array($this->model, $method), $arguments);

		return $this->__hook($method, $arguments);
	}

	/**
	 * A method for handling a hook on a method
	 *
	 * This will setup the hookline and pass the
	 * result of the Model call down the hookline
	 * for processing by passing it into each plugin
	 * and method that is defined on the hookline.
	 * It is passed down sequentially, so you can handle
	 * different things with different plugins.
	 *
	 * @method __hook(string $hook, array $arguments)
	 * @param string $hook The name of the hook
	 * @param array $arguments The array of arguments passed to the method that is hooked
	 * @access private
	 * @return mixed The result of the model data after processing
	 */
	private final function __hook($hook, $arguments)
	{
		$this->__hookline($this->hooklines[$hook]);
		$data = call_user_func_array(array($this->model, $hook), $arguments);
		foreach($this->hooklines[$hook] as $slot) {
			$plugin = $this->pluginLoader->load($slot[0]);
			$sinker = array('data' => &$data, 'args' => null);
			if(isset($slot[2]))
				$sinker['args'] = &$slot[2];
			else
				$sinker['args'] = array();

			$plugin->$slot[1]($sinker);
		}
		return $data;
	}

	/**
	 * Process a hookline string in the $hooks configuration
	 *
	 * This will process the string into a hookline array.
	 * A hookline string is formatted as:
	 *
	 * package.plugin::method
	 *
	 * of if you want to specify columns to be processed, add them:
	 *
	 * package.plugin::method(column1,column2)
	 *
	 * or if you want more than one plugin on a hookline:
	 *
	 * package.plugin::method;package.plugin::method(col)
	 *
	 * @method __hookline(&$line)
	 * @param array $line An array of hooklines for the model that has been wrapped
	 * @access private
	 */
	private final function __hookline(&$line)
	{
		$line = explode(';', $line);
		foreach($line as &$l) {
			$x = explode('::', $l);
			$f = explode('(', $x[1]);
			if(isset($f[1])) {
				$args = explode(',', $f[1]);
				$sz = sizeof($args)-1;
				$len = strlen($args[$sz])-1;
				if($args[$sz][$len] == ')')
					$args[$sz] = substr($args[$sz], 0, $len);

				unset($x[1]);
				$f[1] = $args;
				$l = array_merge($x, $f);
				
			} else {
				$l = $x;
				continue;
			}
		}
	}
}
