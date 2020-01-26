<?php 

class Signup extends Controller
{
	
	public function __construct()
    {
        parent::__construct();
        Session::init();
        header('Location: ../login');
        /*$logged = Session::get('loggedIn');
        if($logged == true) {
            header('Location: ../feed');
            exit();
        }*/
    }

    public function index()
    {

        $this->view->render('signup/index', true);
    }

    public function checkPassword() {
        $this->model->loginCheck();
    }

    public function run() {
    	$this->model->run();
    }

    public function error($type) {
    	if (!isset($type)) {
    		$this->view->unexpectedError = 'Извините, произошла неизвестная ошибка, попробуйте ещё раз.';
    	} elseif ($type == 'empty') {
    		$this->view->unexpectedError = 'Некоторые поля не заполены';
    	} elseif ($type == 'alreadyRegistered') {
    		$this->view->unexpectedError = 'К сожалению, в Deproh уже зарегистрирован этот адрес. Придумайте другой.';
    	} elseif ($type == 'phoneMustNumbers') {
            $this->view->unexpectedError = 'Номер телефона должен состоять из цифр!';
        } else {
    		$this->view->unexpectedError = 'Извините, произошла неизвестная ошибка, попробуйте ещё раз.';
    	}
    	$this->view->render('signup/index', true);
    }

}

 ?>