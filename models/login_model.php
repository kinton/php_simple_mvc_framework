<?php

class Login_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $sth = $this->db->prepare("SELECT id, role FROM users WHERE email = :email AND password = :password");
        $sth->execute(array(
            ':email' => $_POST['login'],
            ':password' => Hash::create('md5', $_POST['password'], HASH_KEY)
        ));
        /*ещё можно так: $data = array('Cathy', '9 Dark and Twisty Road', 'Cardiff');

              $STH = $DBH->prepare("INSERT INTO folks (name, addr, city) values (?, ?, ?)");
              $STH->execute($data);

              или то же самое, но вместо prepare и execute просто query*/
        $data = $sth->fetch();
        $count = $sth->rowCount();
        if ($count > 0) {
            Session::init();
            Session::set('loggedIn', true);
            Session::set('role', $data['role']);
            Session::set('id', $data['id']);
            header('Location: ../feed');
        } else {
            header('Location: ../login');
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

?>