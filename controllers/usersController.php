<?php
use models\User;

class usersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'subject' => 'Lista de Usuarios',
            'button' => [
                'name' => 'Nuevo Usuario',
                'link' => 'users/create'
            ],
            'root_link' => [
                'name' => 'Admin',
                'link' => 'admin/index'
            ],
            'model_button' => 'users',
            'current_link' => 'Users', 
            'message' => 'No hay usuarios registrados'
        ];

        $users = User::select('id','name','email')->orderBy('id','desc')->get();

        $this->_view->load('users/index', compact('options','users','msg_success','msg_error'));
    }

    public function create()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'subject' => 'Nuevo Usuario',
            'button' => 'Registrar',
            'back' => [
                'name' => 'Volver',
                'link' => 'users/index'
            ],
            'root_link' => [
                'name' => 'Users',
                'link' => 'users/index'
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => 'users/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $user = Session::get('data');

        $this->_view->load('users/create', compact('options','user','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm("users/create", [
            'name' => Filter::getText('name'),
            'email' => $this->validateEmail(Filter::getPostParam('email')),
            'password' => Filter::getSql('password')
        ]);

        //validar existencia de user
        $user = User::select('id')->where('email', Filter::getPostParam('email'))->first();

        if ($user) {
            Session::set('msg_error','El usuario ya está registrado... intente con otro');
            $this->redirect('users/create');
        }

        //validacion de password
        if (strlen(Filter::getSql('password')) < 8) {
            Session::set('msg_error','El password debe contener al menos 8 caracteres');
            $this->redirect('users/create');
        }

        if (Filter::getSql('repassword') != Filter::getSql('password')) {
            Session::set('msg_error','Los passwords ingresados no coinciden');
            $this->redirect('users/create');
        }

        $user = new User;
        $user->name = Filter::getText('name');
        $user->email = Filter::getPostParam('email');
        $user->password = Helper::encryptPassword(Filter::getSql('password'));
        $user->save();

        Session::destroy('data');
        Session::set('msg_success','El usuario se ha registrado correctamente');
        $this->redirect('users/index');
    }

    public function show($id = null)
    {
        Validate::validateModel(User::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'subject' => 'Detalle Usuario',
            'back' => [
                'name' => 'Volver',
                'link' => 'users/index'
            ],
            'root_link' => [
                'name' => 'Users',
                'link' => 'users/index'
            ],
            'current_link' => 'Show',
        ];

        $user = User::find(Filter::filterInt($id));

        $this->_view->load('users/show', compact('options','user','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(User::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'subject' => 'Editar Usuario',
            'button' => 'Editar',
            'back' => [
                'name' => 'Volver',
                'link' => 'users/index'
            ],
            'root_link' => [
                'name' => 'Users',
                'link' => 'users/index'
            ],
            'current_link' => 'Edit',
            'action' => 'edit',
            'process' => "users/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $user = User::find(Filter::filterInt($id));

        $this->_view->load('users/edit', compact('options','user','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(User::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("users/edit/{$id}", [
            'name' => Filter::getText('name'),
            'email' => $this->validateEmail(Filter::getPostParam('email')),
        ]);

        $user = User::find(Filter::filterInt($id));
        $user->name = Filter::getText('name');
        $user->email = Filter::getPostParam('email');
        $user->save();

        Session::destroy('data');
        Session::set('msg_success', 'El usuario se ha modificado correctamente');
        $this->redirect('users/show/' . $id);
    }
}