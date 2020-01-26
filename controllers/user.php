<?php

class User extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Session::init();
        $logged = Session::get('loggedIn');
        $role = Session::get('role');
        if ($logged != true || $role != 'owner') {
            Session::destroy();
            header('Location: login');
            exit();
        }
    }

    public function index()
    {
        $this->view->userList = $this->model->userList();
        $this->view->render('user/index');
    }

    public function create()
    {
        $data = array();
        $data['login'] = $_POST['login'];
        $data['password'] = md5($_POST['password']);
        $data['role'] = $_POST['role'];
        $this->model->create($data);
        header('Location: ' . URL . 'user');
    }

    public function edit($id)
    {
        $this->view->user = $this->model->userSingleList($id);
        $this->view->render('user/edit');
    }


    public function editSave($id)
    {
        $data = array();
        $data['login'] = $_POST['login'];
        $data['password'] = md5($_POST['password']);
        $data['role'] = $_POST['role'];
        $data['id'] = $id;
        $this->model->editSave($data);
        header('Location: ' . URL . 'user');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header('Location: ' . URL . 'user');
    }
}

?>