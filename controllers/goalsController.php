<?php
use models\Goal;
use models\Course;

class goalsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($course = null)
    {
        Validate::validateModel(Course::class, $course, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Metas',
            'subject' => 'Nueva Meta',
            'button' => 'Guardar',
            'back' => [
                'name' => 'Volver',
                'link' => "courses/show/{$course}"
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => "goals/store/{$course}",
            'send' => $this->encrypt($this->getForm())
        ];

        $goal = Session::get('data');
        $course = Course::select('id','title')->find(Filter::filterInt($course));

        $this->_view->load('goals/create', compact('options','goal','course','msg_success','msg_error'));
    }

    public function store($course = null)
    {
        Validate::validateModel(Course::class, $course, 'error/error');
        $this->validateForm("goals/create/{$course}", [
            'name' => Filter::getText('name'),
        ]);

        $goal = Goal::select('id')->where('name', Filter::getText('name'))
            ->where('course_id', Filter::filterInt($course))
            ->first();

        if ($goal) {
            Session::set('msg_error','La meta ya está registrada... intente con otra');
            $this->redirect('goals/create/' . $course);
        }

        $goal = new Goal;
        $goal->name = Filter::getText('name');
        $goal->course_id = Filter::filterInt($course);
        $goal->save();

        Session::destroy('data');
        Session::set('msg_success','La meta se ha registrado correctamente');
        $this->redirect('courses/show/' . $course);
    }

    public function show($id = null)
    {
        Validate::validateModel(Goal::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $goal = Goal::with('course')->find(Filter::filterInt($id));

        $options = [
            'title' => 'Metas',
            'subject' => 'Detalle Meta',
            'back' => [
                'name' => 'Volver',
                'link' => "courses/show/{$goal->course_id}"
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'current_link' => 'Show',
        ];

        $this->_view->load('goals/show', compact('options','goal','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Goal::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $goal = Goal::with('course')->find(Filter::filterInt($id));

        $options = [
            'title' => 'Metas',
            'subject' => 'Editar Meta',
            'button' => 'Editar',
            'back' => [
                'name' => 'Volver',
                'link' => "goals/show/{$id}"
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'current_link' => 'Edit',
            'action' => 'edit',
            'process' => "goals/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $this->_view->load('goals/edit', compact('options','goal','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Goal::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("goals/edit/{$id}", [
            'name' => Filter::getText('name'),
        ]);

        $goal = Goal::find(Filter::filterInt($id));
        $goal->name = Filter::getText('name');
        $goal->save();

        Session::destroy('data');
        Session::set('msg_success', 'La meta se ha modificado correctamente');
        $this->redirect('goals/show/' . $id);
    }

    public function delete($id = null)
    {
        Validate::validateModel(Goal::class, $id, 'error/error');

        $goal = Goal::find(Filter::filterInt($id));

        $goal->delete();

        Session::set('msg_success','La meta se ha eliminado correctamente');
        $this->redirect('courses/show/' . $goal->course_id);
    }
}
