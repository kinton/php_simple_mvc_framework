<?php
  class Index extends Controller {

    public function __construct() {
      	parent::__construct();
      	Session::init();
      	$logged = Session::get('loggedIn');
        /*if($logged == true) {
            header('Location: ../feed');
            exit();
        }*/
        /* header('Location: /feed', TRUE, 301);
        exit(); */
    }
   
    public function index() {
        $this->view->render('index/index', true);
  	}
  }
?>