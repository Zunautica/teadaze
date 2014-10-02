<?php

	class portalController extends Controller
	{
		public function init(array $url)
		{
			$this->loadView('main');
			$this->setVariable('title', "FOOBAR");

			return $this;
		}

		public function dynamic(array $url)
		{
			$this->loadView("ajax");

			$model = $this->loadModel('Users.Users');
			$users = $model->getUsers();
			$this->setVariable('users', json_encode_object(array('users'=>$users)));
		}
	}
