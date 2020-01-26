<?php
  class Error extends Controller {
   public function __construct() {
    parent::__construct();
   }

   public function index() {
   	header('Location: /');
	exit();
    $this->view->render('error/index');
   }
  }
?>