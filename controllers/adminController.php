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

        $options = [
            'title' => 'Administracion',
            'subject' => 'Panel de Administración',
            'root_link' => [
                'name' => 'Admin',
                'link' => 'admin/index'
            ],
            'current_link' => 'Admin', 
        ];

		$this->_view->load('admin/index', compact('options'));    
    }
}