<?php
	Session::init();
	if (Session::get('loggedIn') == true) {
		header('Location: ../dashboard');
	}  	
?>
<!doctype html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <?php if (isset($this->title)) { echo "<title>",$this->title,'</title>'; } else { echo "<title>",$this->siteConstants['siteName'],"</title>";} ?>
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css">
    <!--<link rel="stylesheet" href="<?php// echo URL; ?>public/css/bootstrap.min.css">-->
    <script src="<?php echo URL; ?>public/js/jquery.js"></script>
    <!--<script src="<?php// echo URL; ?>public/js/bootstrap.min.js"></script>-->
    <script src="<?php echo URL; ?>public/js/custom.js"></script>
    <?php
  if(isset($this->js)) {
    foreach($this->js as $js) {
	   echo '<script src="'.URL.'views/'.$js.'"></script>';
    }
  }
  ?>
</head>

<body>
    <div id="header" class="header-container header-on-white">
      <div class="header">
        <div class="left-header">
          <div class="logo"><?=$this->siteConstants['siteName']?></div>
        </div>
        <div class="right-header">
          <!--<a href="<?php echo URL; ?>login" class="button transparent">Войти</a>-->
          <a href="<?php echo URL; ?>signup" class="button transparent">Зарегистрироваться</a>
        </div>
      </div>
    </div>
	<div class="content">
		<div class="about-block">
			<div class="login-form-div" id="loginFormDiv">
				<h1 class="name"><?=$this->siteConstants['siteName']?></h1>

				<form name="loginCheck">
					<input type="text" name="login" class="text-input text-input-main" placeholder="Введите адрес электронной почты" autofocus>
					<input type="submit" name="next" value="Далее" class="button primary real-button">
				</form>

				<form action="<?php echo URL;?>login/run" method="post" name="login" class="login-form" id="loginForm">
				    <input type="text" name="login" placeholder="Введите адрес электронной почты" class="text-input text-input-main" style="display: none;">
				    <input type="password" name="password" class="text-input text-input-main" placeholder="Пароль">
				    <input type="submit" class="button real-button primary" value="Войти">
				</form>
			</div>
		</div><div class="about-block-title"><a href="../signup">Создать аккаунт</a></div>
				<!--<form action="<?php echo URL;?>login/getHashPassword" method="post">
				    <label>Password</label><input type="text" name="password"><br>
				    <label></label><input type="submit">
				</form>
				<?php if ($this->hashPassword != '') {
				    echo $this->hashPassword;
				} ?>-->
	</div>
	<script>
		new CheckRegister(document.forms.loginCheck, '<?php echo URL;?>login/checkPassword' );

		function CheckRegister (form, url) {

			form.onsubmit = function() {
				var login = form.login.value;

				if (login.length > 0) {
					if (login.length <= 50) {

						if (/.*@.*\..*/.test(login)) {
							loginChecker(login);
						} else {
							error('Введите допустимый адрес электронной почты.');
						}
						
					} else {
						error('Слишком длинный адрес электронной почты.');
					}
				} else {
					error('Введите адрес электронной почты.');
				}

				return false;
			}

			function error(message) {
				var element = document.getElementById('errorMessage');
				if (element) {
					element.parentNode.removeChild(element);
				}

				var div = document.createElement('div');
				div.style.color = 'red';
				div.style.marginBottom = '10px';
				div.style.textAlign = 'left';
				div.innerHTML = message;
				div.id = 'errorMessage';

				form.insertBefore(div, form.next);
				form.login.style.border = '1px solid red';
				form.login.classList.remove('text-input-main');
				form.login.classList.add('text-input-additional');
			}

			function answerController(message) {
				if (message == 'finded') {
					var login = form.login.value;
					form.style.display = 'none';
					var login_form = document.forms.login;
					login_form.style.display = 'block';
					login_form.login.value = login;

					var div = document.createElement('div');
					div.style.textAlign = 'center';
					div.style.marginBottom = '10px';
					div.innerHTML = login;

					login_form.insertBefore(div, login_form.password);
					login_form.password.focus();
				}
				if (message == 'dontfinded') {
					error('К сожалению, в <?=$this->siteConstants['siteName']?> не зарегистрирован этот адрес. <a href="<?php echo URL;?>signup">Создать</a> для него аккаунт?');
					return false;
				}
				if(message == 'incorrect') {
					error('Введите адрес электронной почты.');
					return false;
				}
			}

			function loginChecker (email) {

			    var xhr = new XMLHttpRequest();

				xhr.onload = xhr.onerror = function() {

		            if (this.readyState == 4 && this.status == 200) {
		            	var answer = xhr.responseText;
			            answerController(answer);
		            } else {
			            
		            }
		         
		        }
	       		
			    var formData = new FormData();
			    formData.append('email', email);

			   	xhr.open("POST", url, true);
			   	xhr.send(formData);
			}
		}
	</script>
</body>
</html>