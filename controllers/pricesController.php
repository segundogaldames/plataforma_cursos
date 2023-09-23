<?php
use models\Price;

final class pricesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();
        
        $options = [
            'title' => 'Precios',
            'subject' => 'Lista de Precios',
            'button' => [
                'name' => 'Nuevo Precio',
                'link' => 'prices/create'
            ],
            'root_link' => [
                'name' => 'Cursos',
                'link' => 'courses/index'
            ],
            'model_button' => 'prices',
            'current_link' => 'Precio', 
            'message' => 'No hay precios registrados'
        ];

        $prices = Price::select('id','name','value')->orderBy('id','desc')->get();

        $this->_view->load('prices/index', compact('options','prices','msg_success','msg_error'));      
    }

    public function create()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Precios',
            'subject' => 'Nuevo Precio',
            'button' => 'Guardar',
            'back' => [
                'name' => 'Volver',
                'link' => 'prices/index'
            ],
            'root_link' => [
                'name' => 'Precios',
                'link' => 'prices/index'
            ],
            'current_link' => 'Create',
            'action' => 'create',
            'process' => 'prices/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $price = Session::get('data');

        $this->_view->load('prices/create', compact('options','price','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm("prices/create", [
            'name' => Filter::getText('name'),
            'value' => Filter::getText('value')
        ]);

        $price = Price::select('id')->where('name', Filter::getText('name'))->where('value', Filter::getInt('value'))->first();

        if ($price) {
            Session::set('msg_error','El precio ya está registrado... intente con otro');
            $this->redirect('prices/create');
        }

        $price = new Price;
        $price->name = Filter::getText('name');
        $price->value = Filter::getInt('value');
        $price->save();

        Session::destroy('data');
        Session::set('msg_success','El precio se ha registrado correctamente');
        $this->redirect('prices/index');
    }

    public function show($id = null)
    {
        Validate::validateModel(Price::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Precios',
            'subject' => 'Detalle Precio',
            'back' => [
                'name' => 'Volver',
                'link' => 'prices/index'
            ],
            'root_link' => [
                'name' => 'Precios',
                'link' => 'prices/index'
            ],
            'current_link' => 'Show',
        ];

        $price = Price::find(Filter::filterInt($id));

        $this->_view->load('prices/show', compact('options','price','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Price::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Precios',
            'subject' => 'Editar Precio',
            'button' => 'Editar',
            'back' => [
                'name' => 'Volver',
                'link' => 'prices/index'
            ],
            'root_link' => [
                'name' => 'Precios',
                'link' => 'prices/index'
            ],
            'current_link' => 'Edit',
            'action' => 'edit',
            'process' => "prices/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $price = Price::find(Filter::filterInt($id));

        $this->_view->load('prices/edit', compact('options','price','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Price::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("prices/edit/{$id}", [
            'name' => Filter::getText('name'),
            'value' => Filter::getText('value')
        ]);

        $price = Price::find(Filter::filterInt($id));
        $price->name = Filter::getText('name');
        $price->value = Filter::getInt('value');
        $price->save();

        Session::destroy('data');
        Session::set('msg_success', 'El precio se ha modificado correctamente');
        $this->redirect('prices/show/' . $id);
    }
}
