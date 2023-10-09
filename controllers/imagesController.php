<?php
use models\Image;
use models\Course;

class imagesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }    

    public function create($imageable = null, $type = null)
    {
        Validate::validateModel(Course::class, $imageable, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        if ($type == 'Course') {
            //print_r($type);exit;

            $link_back = "courses/show/{$imageable}/{$type}";
            $name_root = 'Courses';
            $link_root = 'courses/index';
            $model = Course::select('id','title')->find(Filter::filterInt($imageable));
        }

        $options = [
            'title' => 'Imagenes',
            'subject' => 'Nueva Imagen',
            'button' => 'Guardar',
            'back' => [
                'name' => 'Volver',
                'link' => $link_back
            ],
            'root_link' => [
                'name' => $name_root,
                'link' => $link_root
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => "images/store/{$imageable}/{$type}",
            'send' => $this->encrypt($this->getForm()),
            'imageable' => $imageable,
            'type' => $type
        ];

        $image = Session::get('data');

        $this->_view->load('images/create', compact('options','image','model','msg_success','msg_error'));
    }

    public function store($imageable = null, $type = null)
    {
        Validate::validateModel(Course::class, $imageable, 'error/error');
        $this->validateForm("images/create/{$imageable}/{$type}",[
            'image' => $_FILES['image']['name'],
        ]);

        $extension = explode('.', $_FILES['image']['name']);
        $extension = end($extension);

        //print_r($extension);exit;

        if ($extension != 'jpeg' && $extension != 'jpg') {
            Session::set('msg_error','Seleccione una imagen');
            $this->redirect('images/create/' . $imageable . '/' . $type );
        }

        $img = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $upload = $_SERVER['DOCUMENT_ROOT'] . '/plataforma_cursos/public/img/';
        $file = $upload . basename($_FILES['image']['name']);

        //print_r($file);exit;

        $image = Image::select('id')
            ->where('image', $img)
            ->first();

        if ($image) {
            Session::set('msg_error','La imagen ingresada ya existe... intente con otra');
            $this->redirect('images/create/' . $imageable . '/' . $type);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $file)) {
            $image = new Image;
            $image->image = $img;
            $image->imageable_id = Filter::filterInt($imageable);
            $image->imageable_type = Helper::strClean($type);
            $image->save();

            Session::set('msg_success','La imagen se ha registrado correctamente');
        }else{

            Session::set('msg_error', 'La imagen no se ha podido registrar... intente nuevamente');
        }

        if($type == 'Course'){
            $route = "images/imagesCourse/{$imageable}";
        }

        Session::destroy('data');
        $this->redirect('courses');
    }
}
