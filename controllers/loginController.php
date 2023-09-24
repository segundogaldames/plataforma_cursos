<?php
use models\User;

final class loginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }    

    public function login()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Login',
            'subject' => 'Acceder',
            'button' => 'Ingresar',
            'process' => 'login/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $this->_view->load('login/login', compact('options','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm("login/login",[
            'email' => $this->validateEmail(Filter::getPostParam('email')),
            'password' => Filter::getSql('password')
        ]);

        $user = User::select('id','name','email')->where('email',Filter::getPostParam('email'))
            ->where('password', Helper::encryptPassword(Filter::getSql('password')))
            ->first();

        if (!$user) {
            Session::set('msg_error','El email o el password no están registrados... intente nuevamente');
            $this->redirect('login/login');
        }

        Session::set('authenticate', true);
        Session::set('user_id', $user->id);
        Session::set('user_name', $user->name);
        Session::set('time', time());

        $this->redirect('index/index');
    }

    public function logout()
    {
        Session::destroy();
        $this->redirect('index/index');
    }
}
