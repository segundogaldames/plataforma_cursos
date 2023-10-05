<?php
use models\Level;

final class levelsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();
        
        $options = [
            'title' => 'Niveles',
            'subject' => 'Lista de Niveles',
            'button' => [
                'name' => 'Nuevo Nivel',
                'link' => 'levels/create'
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'model_button' => 'levels',
            'current_link' => 'Level', 
            'message' => 'No hay niveles registrados'
        ];

        $levels = Level::select('id','name')->orderBy('id','desc')->get();

        $this->_view->load('levels/index', compact('options','levels','msg_success','msg_error'));      
    }

    public function create()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Niveles',
            'subject' => 'Nuevo Nivel',
            'button' => 'Guardar',
            'back' => [
                'name' => 'Volver',
                'link' => 'levels/index'
            ],
            'root_link' => [
                'name' => 'Niveles',
                'link' => 'levels/index'
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => 'levels/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $level = Session::get('data');

        $this->_view->load('levels/create', compact('options','level','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm("levels/create", [
            'name' => Filter::getText('name'),
        ]);

        $level = Level::select('id')->where('name', Filter::getText('name'))->first();

        if ($level) {
            Session::set('msg_error','El nivel ya está registrado... intente con otro');
            $this->redirect('levels/create');
        }

        $level = new Level;
        $level->name = Filter::getText('name');
        $level->ruta = Helper::friendlyRoute(Filter::getText('name'));
        $level->save();

        Session::destroy('data');
        Session::set('msg_success','El nivel se ha registrado correctamente');
        $this->redirect('levels/index');
    }

    public function show($id = null)
    {
        Validate::validateModel(Level::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Niveles',
            'subject' => 'Detalle Nivel',
            'back' => [
                'name' => 'Volver',
                'link' => 'levels/index'
            ],
            'root_link' => [
                'name' => 'Niveles',
                'link' => 'levels/index'
            ],
            'current_link' => 'Show',
        ];

        $level = Level::find(Filter::filterInt($id));

        $this->_view->load('levels/show', compact('options','level','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Level::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Niveles',
            'subject' => 'Editar Nivel',
            'button' => 'Editar',
            'back' => [
                'name' => 'Volver',
                'link' => 'levels/index'
            ],
            'root_link' => [
                'name' => 'Niveles',
                'link' => 'levels/index'
            ],
            'current_link' => 'Edit',
            'action' => 'edit',
            'process' => "levels/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $level = Level::find(Filter::filterInt($id));

        $this->_view->load('levels/edit', compact('options','level','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Level::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("levels/edit/{$id}", [
            'name' => Filter::getText('name'),
        ]);

        $level = Level::find(Filter::filterInt($id));
        $level->name = Filter::getText('name');
        $level->ruta = Helper::friendlyRoute(Filter::getText('name'));
        $level->save();

        Session::destroy('data');
        Session::set('msg_success', 'El nivel se ha modificado correctamente');
        $this->redirect('levels/show/' . $id);
    }
}
