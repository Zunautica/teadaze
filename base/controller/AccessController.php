<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

/**
 * A controller class type that is assigned to controllers that have inbuilt access control
 *
 * This can be used to remind about authentication routines - maybe deprecate?
 * Could let plugins handle authentication
 */
	abstract class AccessController extends Controller {

		/**
		 * The method that is defined by child object to handle authentication
		 *
		 * This method can be run to make sure that authentication is handled
		 * with the option of chainloading another controller.
		 *
		 * This method is defined by the child class
		 *
		 * @method auth()
		 * @access protected
		 * @return boolean True on successful authentication; otherwise false
		 */
		abstract protected function auth();
	}
