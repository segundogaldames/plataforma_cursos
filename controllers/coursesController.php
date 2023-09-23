<?php
use models\Course;
use models\Category;
use models\Level;
use models\Price;
use models\User;

final class coursesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Cursos',
            'subject' => 'Lista de Cursos',
            'button' => [
                'name' => 'Nuevo Curso',
                'link' => 'courses/create'
            ],
            'root_link' => [
                'name' => 'Admin',
                'link' => 'admin/index'
            ],
            'model_button' => 'courses',
            'current_link' => 'Course', 
            'message' => 'No hay cursos registrados'
        ];

        $courses = Course::with(['user','level','category','price'])->orderBy('id','desc')->get();

        $this->_view->load('courses/index', compact('options','courses','msg_success','msg_error'));  
    }

    //metodo courses para mostrar los cursos activos a los usuarios/clientes
    //metodo coursesLast para mostrar los ultimos 4 cursos activos a clientes

    public function create()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Cursos',
            'subject' => 'Nuevo Curso',
            'button' => 'Guardar',
            'back' => [
                'name' => 'Volver',
                'link' => 'courses/index'
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => 'courses/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $course = Session::get('data');
        $levels = Level::select('id','name')->orderBy('name')->get();
        $categories = Category::select('id','name')->orderBy('name')->get();
        $prices = Price::select('id','name','value')->orderBy('value')->get();

        $this->_view->load('courses/create', compact('options','course','msg_success','msg_error','levels','categories','prices'));
    }

    public function store()
    {
        $this->validateForm("courses/create", [
            'title' => Filter::getText('title'),
            'subtitle' => Filter::getText('subtitle'),
            'description' => Filter::getText('description'),
            'level' => Filter::getText('level'),
            'category' => Filter::getText('category'),
            'price' => Filter::getText('price')
        ]);

        $course = Course::select('id')->where('title', Filter::getText('title'))->first();

        if ($course) {
            Session::set('msg_error','El curso ya está registrado... intente con otro');
            $this->redirect('courses/create');
        }

        $course = new Course;
        $course->title = Filter::getText('title');
        $course->subtitle = Filter::getText('subtitle');
        $course->description = Filter::getText('description');
        $course->status = 1; //pendiente
        $course->slug = Helper::friendlyRoute(Filter::getText('title'));
        $course->user_id = Session::get('user_id');
        $course->level = Filter::getInt('level');
        $course->category = Filter::getInt('category');
        $course->price = Filter::getInt('price');
        $course->save();

        Session::destroy('data');
        Session::set('msg_success','El curso se ha registrado correctamente');
        $this->redirect('courses/index');
    }

    public function show($id = null)
    {
        Validate::validateModel(Course::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Cursos',
            'subject' => 'Detalle Curso',
            'back' => [
                'name' => 'Volver',
                'link' => 'courses/index'
            ],
            'root_link' => [
                'name' => 'Niveles',
                'link' => 'courses/index'
            ],
            'current_link' => 'Show',
        ];

        $course = Course::with(['user','level','category','price'])->find(Filter::filterInt($id));

        $this->_view->load('courses/show', compact('options','course','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Course::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Cursos',
            'subject' => 'Editar Curso',
            'button' => 'Editar',
            'back' => [
                'name' => 'Volver',
                'link' => 'courses/index'
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'current_link' => 'Edit',
            'action' => 'edit',
            'process' => "courses/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $course = Course::with(['user','level','category','price'])->find(Filter::filterInt($id));
        $levels = Level::select('id','name')->orderBy('name')->get();
        $categories = Category::select('id','name')->orderBy('name')->get();
        $prices = Price::select('id','name','value')->orderBy('value')->get();

        $this->_view->load('courses/create', compact('options','course','msg_success','msg_error','levels','categories','prices'));
    }

    public function update($id = null)
    {
        $this->validateForm("courses/create", [
            'title' => Filter::getText('title'),
            'subtitle' => Filter::getText('subtitle'),
            'description' => Filter::getText('description'),
            'status' => Filter::getText('status'),
            'level' => Filter::getText('level'),
            'category' => Filter::getText('category'),
            'price' => Filter::getText('price')
        ]);

        $course = Course::find(Filter::filterInt($id));
        $course->title = Filter::getText('title');
        $course->subtitle = Filter::getText('subtitle');
        $course->description = Filter::getText('description');
        $course->status = Filter::getInt('status');
        $course->slug = Helper::friendlyRoute(Filter::getText('title'));
        $course->level = Filter::getInt('level');
        $course->category = Filter::getInt('category');
        $course->price = Filter::getInt('price');
        $course->save();

        Session::destroy('data');
        Session::set('msg_success','El curso se ha modificado correctamente');
        $this->redirect('courses/show/' . $id);
    }
}
