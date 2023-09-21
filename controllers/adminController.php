<?php
class adminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();
        $title = 'Panel de Administración';

		$this->_view->load('admin/index', compact('title'));    
    }
}