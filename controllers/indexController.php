<?php

class indexController extends Controller
{

	public function __construct(){
		// $this->verificarSession();
		// Session::tiempo();
		parent::__construct();
	}

	public function index()
	{
		list($msg_success, $msg_error) = $this->getMessages();

		$this->_view->load('index/index', compact('msg_success','msg_error'));
	}
}