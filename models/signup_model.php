<?php

class Signup_Model extends Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function run()
    {	
        exit;
        //проверка на получение данных
    	if (!isset($_POST['firstname']) or !isset($_POST['secondname']) or !isset($_POST['login']) or !isset($_POST['password']) or !isset($_POST['byear']) or !isset($_POST['bmonth']) or !isset($_POST['bday']) or !isset($_POST['phone']) or !isset($_POST['city'])) {
    		header('Location: ../signup/error/empty');
    		exit();
    	} 

        //проверка на заполненность данных и формы
    	if ($_POST['firstname'] == '' or $_POST['secondname'] == '' or $_POST['login'] == '' or $_POST['password'] == '' or $_POST['byear'] == '' or $_POST['bmonth'] == '' or $_POST['bday'] == '' or $_POST['phone'] == '' or $_POST['city'] == '') {
    		header('Location: ../signup/error/empty');
    		exit();
    	}

        //проверка на наличие данного адреса в бд
    	$sth = $this->db->prepare("SELECT id FROM users WHERE email=?");
        $sth->execute(array($_POST['login']));
       	$count = $sth->rowCount();

       	if ($count > 0) {
       		header('Location: ../signup/error/alreadyRegistered');
       		exit();
       	}

        if (!is_numeric($_POST['phone'])) {
            header('Location: ../signup/error/phoneMustNumbers');
            exit();
        }

        //регистрация
        $sth = $this->db->prepare("INSERT INTO users (name, surname, email, password, birthdate, phone, city, role) VALUES (:name, :surname, :email, :password, :birthdate, :phone, :city, :role)");
        $sth->execute(array(
        	':name' => $_POST['firstname'],
        	':surname' => $_POST['secondname'],
            ':email' => $_POST['login'],
            ':password' => Hash::create('md5', $_POST['password'], HASH_KEY),
            ':birthdate' => $_POST['byear'].'-'.$_POST['bmonth'].'-'.$_POST['bday'],
            ':phone' => $_POST['phone'],
            ':city' => $_POST['city'],
            ':role' => 'default'
        ));
        /*ещё можно так: $data = array('Cathy', '9 Dark and Twisty Road', 'Cardiff');
              $STH = $DBH->prepare("INSERT INTO folks (name, addr, city) values (?, ?, ?)");
              $STH->execute($data);

              или то же самое, но вместо prepare и execute просто query*/
        //$data = $sth->fetch();
        //$count = $sth->rowCount();

        //проверка на успешность выполнения
        $sth = $this->db->prepare("SELECT id FROM users WHERE email=?");
        $sth->execute(array($_POST['login']));
       	$count = $sth->rowCount();

        if ($count == 1) {
            Session::init();
            Session::set('loggedIn', true);
            Session::set('role', $data['role']);
            Session::set('id', $data['id']);
            header('Location: ../feed');
        } else {
            header('Location: ../signup/error');
        }
    }

	public function loginCheck() {
        if (isset($_POST['email'])) {
            $sth = $this->db->prepare("SELECT id FROM users WHERE email = :email");
            $sth->execute(array(':email' => $_POST['email']));
            $data = $sth->fetch();
            $count = $sth->rowCount();
            if ($count > 0) {
                $check = 'finded';
            } else {
                $check = 'dontfinded';
            }
            echo $check;
        } else {
            echo 'incorrect';
        }
    }
}