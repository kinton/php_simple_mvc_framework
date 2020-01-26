<?php

class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Session::init();
        $logged = Session::get('loggedIn');
        if($logged == true) {
            header('Location: ../feed');
            exit();
        }
    }

    public function index()
    {

        $this->view->render('login/index', true);
    }

    public function run()
    {
        $this->model->run();
    }

    public function getHashPassword() {
        $this->view->hashPassword = Hash::create('md5', $_POST['password'], HASH_KEY);
        $this->view->render('login/index');
    }

    public function checkPassword() {
        $this->model->loginCheck();
    }
}

?>