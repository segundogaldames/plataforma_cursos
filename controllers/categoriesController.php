<?php
use models\Category;

final class categoriesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();
        
        $options = [
            'title' => 'Categorias',
            'subject' => 'Lista de Categorías',
            'button' => [
                'name' => 'Nueva Categoría',
                'link' => 'categories/create'
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'model_button' => 'categories',
            'current_link' => 'Category', 
            'message' => 'No hay categorías registradas'
        ];

        $categories = Category::select('id','name')->orderBy('id','desc')->get();

        $this->_view->load('categories/index', compact('options','categories','msg_success','msg_error'));      
    }

    public function create()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Categorias',
            'subject' => 'Nueva Categoría',
            'button' => 'Guardar',
            'back' => [
                'name' => 'Volver',
                'link' => 'categories/index'
            ],
            'root_link' => [
                'name' => 'Categorías',
                'link' => 'categories/index'
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => 'categories/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $category = Session::get('data');

        $this->_view->load('categories/create', compact('options','category','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm("categories/create", [
            'name' => Filter::getText('name'),
        ]);

        $category = Category::select('id')->where('name', Filter::getText('name'))->first();

        if ($category) {
            Session::set('msg_error','La categoría ya está registrada... intente con otra');
            $this->redirect('categories/create');
        }

        $category = new Category;
        $category->name = Filter::getText('name');
        $category->ruta = Helper::friendlyRoute(Filter::getText('name'));
        $category->save();

        Session::destroy('data');
        Session::set('msg_success','La categoría se ha registrado correctamente');
        $this->redirect('categories/index');
    }

    public function show($id = null)
    {
        Validate::validateModel(Category::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Categorias',
            'subject' => 'Detalle Categoría',
            'back' => [
                'name' => 'Volver',
                'link' => 'categories/index'
            ],
            'root_link' => [
                'name' => 'Categorias',
                'link' => 'categories/index'
            ],
            'current_link' => 'Show',
        ];

        $category = Category::find(Filter::filterInt($id));

        $this->_view->load('categories/show', compact('options','category','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Category::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Categorias',
            'subject' => 'Editar Categoría',
            'button' => 'Editar',
            'back' => [
                'name' => 'Volver',
                'link' => 'categories/index'
            ],
            'root_link' => [
                'name' => 'Categorías',
                'link' => 'categories/index'
            ],
            'current_link' => 'Edit',
            'action' => 'edit',
            'process' => "categories/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $category = Category::find(Filter::filterInt($id));

        $this->_view->load('categories/edit', compact('options','category','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Category::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("categories/edit/{$id}", [
            'name' => Filter::getText('name'),
        ]);

        $category = Category::find(Filter::filterInt($id));
        $category->name = Filter::getText('name');
        $category->ruta = Helper::friendlyRoute(Filter::getText('name'));
        $category->save();

        Session::destroy('data');
        Session::set('msg_success', 'La categoría se ha modificado correctamente');
        $this->redirect('categories/show/' . $id);
    }
}
