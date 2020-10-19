<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController {

    protected $modelName = 'App\Models\UsersModel';
    protected $format = 'json';

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    public function index() 
    {
        return $this->respond($this->model->findAll());
    }

    public function create()
    {
        $data = $this->request->getPost();
        $validate = $this->validation->run($data, 'register');
        $errors = $this->validation->getErrors();

        if($errors){
            return $this->fail($errors);
        }

        $user = new \App\Entities\Users();
        $user->fill($data);
        $user->created_by = 0;
        $user->created_date = date("Y-m-d H:i:s");

        if($this->model->save($user)) 
        {
            return $this->respondCreated($user, 'user created');
        }
    }

    public function update($id = null) 
    {
        $data = $this->request->getRawInput();
        $data['id'] = $id;
        $validate = $this->validation->fun($data, 'update_user');
        $errors = $this->validation->getErros();

        if($errors) {
            return $this->fail($errors);
        }

        $user = new \App\Entities\Users();
        $user->fill($data);
        $user->updated_by = 0;
        $user->updated_date = date("Y-m-d H:i:s");

        if($this->model->save($user)) 
        {
            return $this->respondUpdated($user, 'user updated');
        }
    }
}