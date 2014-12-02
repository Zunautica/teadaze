<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * The abstract class for all controllers and handles the instantiation of controller objects
 *
 * This acts as the go between for views, models and the Entry object.
 * While not exactly how MVC controllers should work; they load the
 * model and view, interact with the model and pass the data to the view.
 * They pass the view back out to whatever is handling it.
 *
 * It also acts as the loader for all controllers, so you
 * use this class's static method to load an instance of a
 * controller.
 *
 * Controllers are the default go to from the first URL subdir unless
 * you use a router plugin to alias another controller. They can also 
 * be locked so they don't act as a root controller from the URL but
 * are only used in a composite.
 */
	abstract class Controller extends ControlType
	implements ControllerLoadingInterface, PluginLoadingInterface
	{
		/** @var string $name The name of the instantiated Controller */
		private $name = null;

		/** @var View $view The view object loaded by the Controller */
		private $view = null;

		/** @var string $frame (TEMPORARY) Used to define the frame, before composite controllers are working */
		private $frame = null;

		/** @var boolean $root Sets whether the controller can be called as a root controller or has to be composite (TRUE automatically) */
		private $root = true;

		private $access;

		private $reference;

		protected $controllerLoader = null;
		private $pluginLoader = null;

		/**
		 * When the object is instantiated, it is set it's name
		 * @method __construct(string $name)
		 * @param string $name The name of the controller
		 * @access public
		 */
		public function __construct($name)
		{
			$this->name = $name;
			$this->reference = $this;
		}

		public final function setControllerLoader(GenericLoader $controllerLoader) {
			$this->controllerLoader = &$controllerLoader;
		}

		public final function setPluginLoader(GenericLoader $pluginLoader) {
			$this->pluginLoader = &$pluginLoader;
		}


		/**
		 * Load a particular view
		 *
		 * This is used by child objects to load a specific
		 * view that is handled by the controller. The view
		 * is instantiated and set in the private $view
		 * property
		 *
		 * @method loadView(string $view)
		 * @param string $view The name of the view to load
		 * @access protected
		 */
		protected function loadView($view)
		{
			try { 
				$this->view = View::load($this->name, $view);
			} catch (exception $e) {
				echo $e;
			}
		}

		/**
		* this is called to load a template from the view
		*
		* This can be used internally to get the view to load
		* it's templated, fully parsed
		*
		* @method loadTemplate()
		* @access protected
		* @return string A fully parsed template
		*/
		protected final function loadTemplate()
		{
			if($this->view == null)
				return null;

			return $this->view->loadTemplate();
		}

		/**
		 * Set a template variable in the view
		 *
		 * This method can be used to pass in data from a model
		 * or just to generally set a variable for the template
		 * to handle
		 *
		 * @method setVariable(string $var, mixed $value)
		 * @param string $var The name of the template variable
		 * @param mixed $value The value assigned to the variable
		 * @access protected
		 */
		public function setVariable($var, $value)
		{
			if($this->view == null)
				return;

			$this->view->setVariable($var, $value);
		}

		/**
		 * Merge another controller's view into this controller's view to
		 * create a composite view.
		 *
		 * When you have a composite controller, that will bring other
		 * controllers together to create a composite view then you
		 * can use this method to merge the views into the handling
		 * controller
		 *
		 * Alternatively you could load up other views from within
		 * the composite controller
		 *
		 * @method mergeView(string $var, View $view)
		 * @param string $var The name of the template variable
		 * @param View $View The view to merge into the controller
		 * @access public
		 */
		public final function mergeView($var, $view)
		{
			$this->setVariable($var, $view);
			$this->view->mergeAssets($view->getAssets());
		}

		/**
		 * A temporary fix for setting a frame around the controller
		 *
		 * This method is a stand in until composite controllers are working.
		 * However, the controller can set what template is used to frame the
		 * view's template
		 *
		 * @method setFrame(string $frame)
		 * @param string $frame The name of the frame the controller will use
		 * @access protected
		 * @deprecated No longer required because of complex controllers
		 */
		protected final function setFrame($frame)
		{
			$this->frame = $frame;
		}

		/**
		 * A temporary fix for getting the frame for the controller
		 *
		 * This is used to externally request the frame that will be
		 * surrounding the view
		 *
		 * @method getFrame()
		 * @access public
		 * @return string The name of the frame template
		 * @deprecated Not longer required because of complex controllers
		 */
		public final function getFrame()
		{
			return $this->frame;
		}

		/**
		 * Used to chainload another controller
		 *
		 * If the controller needs to load up another controller
		 * instead of itself, then it can use this method to chainload and
		 * fully instantiate it. For instance, if you have a locked
		 * controller, you could chainload an error controller
		 *
		 * @method chainload(string Controller, array $target)
		 * @param string $controller The name of the controller to chainload
		 * @param array $target The new target array the controller will use
		 * @access protected
		 * @return An instantiated and initialised controller
		 */
		protected final function chainload($controller, $target)
		{
			$ctrl = $this->controllerLoader->load($controller);
			$this->reference = $ctrl->runInit($target);
			return $this->reference;
		}

		/**
		 * Run the initialisation for the controller
		 *
		 * This will run the controller's init method and
		 * return the reference to the controller or it's
		 * alias if has chainloaded other controllers
		 *
		 * @method runInit(array $target)
		 * @param array $target The target array to pass into the controll
		 * @return Controller reference, either this controller or a chainloaded controller
		 */
		public final function runInit(array $target)
		{
			$this->init($target);
			return $this->reference;
		}

		/**
		 * Used when the controller requires instantiation.
		 * 
		 * This method is defined by child object and is called
		 * when a controller is used for generating a full view
		 * (as opposed to a dynamic request). Here you load a view
		 * and handle any target information from the passed in
		 * target array.
		 *
		 * This method is where you can chainload other controllers
		 * if you need to. The process will be transparent.
		 *
		 * @todo
		 * 	This really needs to be named more in alignment with what
		 *	it is doing. Init is something that happens normally, not
		 *	just on a static load.
		 *
		 * @method init(array $target)
		 * @param array $target The target array to pass into the controller
		 * @access protected
		 */
		abstract protected function init(array $target);

		/**
		 * Called when a dynamic request is made on the controller
		 *
		 * This is an optional method to overload in a child controller since
		 * not all controllers will be handling dynamic request.
		 *
		 * It is called when a dynamic (ajax) request is performed and
		 * functions in the same way as init
		 *
		 * @method dynamic(array $target)
		 * @param array $target The target array to pass into the controller
		 * @access public
		 */
		public function dynamic(array $target)
		{
			return null;
		}

		/**
		 * Return the view loaded by the controller
		 *
		 * This is externally called by whatever is handling
		 * the controller; an Entry object or another controller
		 *
		 * @method getView()
		 * @access public
		 * @return View The view loaded by the controller
		 */
		public final function getView()
		{
			return $this->view;
		}

		/**
		 * Toggle the controller be a root controller or not
		 *
		 * Use this to change whether the controller can be
		 * called as the root (or composite) controller
		 *
		 * @method toggleRoot(boolean $flag)
		 * @param boolean $flag The value to toggle to
		 * @access public
		 */
		public final function toggleRoot($flag)
		{
			$this->root = $flag;
		}

		/**
		 * Returns whether a controller is configured to be a root one
		 *
		 * This can be used internally within the init
		 *
		 * @method isRoot
		 * @access protected
		 * @return boolean The state of the configured root flag
		 */
		protected final function isRoot()
		{
			return $this->root;
		}

		/**
		 * Used to load a specific model
		 *
		 * This method loads a model. It transparently handles
		 * any wrappers for the model and passes in any hooklines
		 * tht are attached to the model from this controller
		 *
		 * @method loadModel($model)
		 * @param string $model The model to load in 'package.model' format
		 * @access protected
		 * @return Model|ModelWrapper The model or wrapped model
		 */
		protected final function loadModel($model)
		{
			$obj = parent::loadModel($model);
			global $hooks;
			$pattern = get_class($this).".";
			$wildcard = "$pattern*.{$model}";
			$pattern .= get_class($this->view).".$model";
			if(isset($hooks[$wildcard]))
				return $this->loadWrapper($obj, $hooks[$wildcard]);

			if(isset($hooks[$pattern]))
				return $this->loadWrapper($obj, $hooks[$pattern]);

			return $obj;
		}

		/**
		* Used to load a model wrapper if a model has a hookline
		* attached to it from this instance of a controller
		*
		* This takes the model and wraps it with hooklines
		*
		* @method loadWrapper(Model $model, array $hooklines)
		* @param Model $model The model object to wrap
		* @param array $hooklines An array of hooklines for the model wrapper
		* @access private
		* @return ModelWrapper The wrapped model
		*/
		private final function loadWrapper($model, $hooklines)
		{
			return new ModelWrapper($model, $hooklines, $this->pluginLoader);
		}
	}
