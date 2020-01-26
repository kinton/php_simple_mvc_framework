<!doctype html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <?php if (isset($this->title)) { echo "<title>",$this->title,'</title>'; } else { echo "<title>Deproh</title>";} ?>
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
  <?php Session::init(); ?>
    <div id="header" class="header-container header-on-white">
      <div class="header">
        <div class="left-header">
          <div class="logo">Deproh</div>
        </div>
        <div class="right-header">
          <a href="<?php echo URL; ?>login" class="button transparent">Войти</a>
          <!--<a href="<?php echo URL; ?>signup" class="button primary">Зарегистрироваться</a>-->
        </div>
      </div>
    </div>
	<div class="content">
		<div class="about-block">
			<h1 class="name">Deproh</h1>
			<div class="row">
				<div class="col-6 text-center">
					<h2>Весь магазин всегда у вас под рукой</h2>
					<h3>Создайте бесплатный аккаунт, чтобы получит доступ к заказу и доставке продуктов с любого устройства</h3>
				</div>
				<div class="col-6">
					<div class="signup-form-div" id="loginFormDiv">

						<form action="<?php echo URL;?>signup/run" method="post" name="signup" class="signup-form" id="loginForm">
							<div id="name-form-element" class="form-element">
								<strong>Как вас зовут?</strong><br>
								<input type="text" name="firstname" placeholder="Имя" class="text-input text-input-main" style="width: 43.5%; margin-right: 2%;" <?php if(!isset($this->alreadyRegistered)) { echo "autofocus"; } ?> >
								<input type="text" name="secondname" placeholder="Фамилия" class="text-input text-input-main" style="width: 43.5%;">
							</div>
							<div id="mail-form-element" class="form-element">
								<strong>Укажите свою почту</strong><br>

						    <?php 
									if (isset($this->alreadyRegistered)) {
								?> 
									<input type="text" name="login" placeholder="Введите адрес электронной почты" class="text-input text-input-additional" style="border: 1px solid red;" autofocus>
									<div style="color: red; margin-bottom: 10px; text-align: left;" id="errorMessage-mail-form-element">К сожалению, в Deproh уже зарегистрирован этот адрес. Придумайте другой.</div>
								<?php
									} else {
								?>
						    	<input type="text" name="login" placeholder="Введите адрес электронной почты" class="text-input text-input-main">
						    <?php } ?>

						  </div>
						  <div id="password-form-element" class="form-element">
						  	<strong>Придумайте пароль и подтвердите его</strong>
						    <input type="password" name="password" class="text-input text-input-main" placeholder="Пароль">
						    <input type="password" name="repassword" class="text-input text-input-main" placeholder="Введите его ещё раз">
						  </div>
						  <div id="birth-form-element" class="form-element">
						  	<strong>Дата рождения</strong><br>
						  	<input type="text" name="bday" placeholder="день" class="text-input text-input-main" style="width: 15%; margin-right: 2%;">
						  	<select name="bmonth" size="1" class="text-input text-input-main" style="width: 48.5%; height: 33.5px; margin-right: 2%;">
						  		<option disabled selected style="color: rgb(128, 128, 128) !important;">месяц</option>
						  		<option value="01">январь</option>
						  		<option value="02">февраль</option>
						  		<option value="03">март</option>
						  		<option value="04">апрель</option>
						  		<option value="05">май</option>
						  		<option value="06">июнь</option>
						  		<option value="07">июль</option>
						  		<option value="08">август</option>
						  		<option value="09">сентябрь</option>
						  		<option value="10">октябрь</option>
						  		<option value="11">ноябрь</option>
						  		<option value="12">декабрь</option>
						  	</select>
						  	<input type="text" name="byear" placeholder="год" class="text-input text-input-main" style="width: 20%;">
						  </div>
						  <div id="phone-form-element" class="form-element">
						  	<strong>Мобильный телефон</strong><br>
						  	<input type="text" name="phone" value="+7" class="text-input text-input-main">
						  </div>
						  <div id="city-form-element" class="form-element">
						  	<strong>Где вы живёте?</strong>
							  <select name="city" class="text-input text-input-main">
							  	<option value="kazan">Казань</option>
							  </select>
							  Внимание! На данный момент сервис доступен только в Казани!
							</div>
						  <input type="submit" class="button real-button primary" value="Зарегистрироваться"><br>
						  Внимание! Нажимая кнопку "зарегистрироваться" вы соглашаетесь с правилами использованиями сервиса.
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
  	if (isset($this->unexpectedError)) {
  		echo "<script> alert('",$this->unexpectedError,"'); </script>";
  	}
  ?>
	<script>

		//проверка, зарегистрирован ли данная почта в базе
		new CheckRegister(document.forms.signup, '<?php echo URL;?>signup/checkPassword');
		function CheckRegister (form, url) {

			form.login.onblur = function() {
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
				var element = document.getElementById('errorMessage-mail-form-element');
				if (element) {
					element.parentNode.removeChild(element);
				}

				var div = document.createElement('div');
				div.style.color = 'red';
				div.style.marginBottom = '10px';
				div.style.textAlign = 'left';
				div.innerHTML = message;
				div.id = 'errorMessage-mail-form-element';

				document.getElementById('mail-form-element').appendChild(div);
				form.login.style.border = '1px solid red';
				form.login.classList.remove('text-input-main');
				form.login.classList.add('text-input-additional');
			}

			function answerController(message) {
				if (message == 'finded') {
					error('К сожалению, в Deproh уже зарегистрирован этот адрес. Придумайте другой.');
					return false;
				}
				if (message == 'dontfinded') {
					var element = document.getElementById('errorMessage-mail-form-element');
					if (element) {
						element.parentNode.removeChild(element);
					}
					form.login.style.border = '0.8px solid rgb(128, 128, 128)';
					form.login.classList.remove('text-input-additional');
					form.login.classList.add('text-input-main');

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

		//проверка на заполненность остальных полей при отправке
		new CheckPoles;
		function CheckPoles() {
			form = document.forms.signup;

			form.password.onfocus = success(form.password);

			form.onsubmit = function() {

				var c = 0;

				c += check(form.firstname, 1);

				c += check(form.secondname, 1);

				c += check(form.login, 5);

				c += check(form.password, 3, 'Пароль должен быть больше 3 символов');

				c += checkRePassword(form.repassword, form.password);

				c += check(form.bday, 1);

				//c += check(form.bmonth, 3);

				c += check(form.byear, 4);

				c += check(form.phone, 12);

				c += check(form.city, 1);

				if (c != 9) {
					return false;
				}

			}

			function check(block, min, message) {
				if (block.value.length < min) {
					error(block, message);
					return 0;
				} else {
					success(block);
					return 1;
				}
			}

			function checkRePassword(block, original) {
				if (block.value != original.value) {
					error(block, 'Пароли не совпадают!');
					return 0;
				} else {
					success(block);
					return 1;
				}
			}

			function error(el, message) {
				id = 'errorMessage-' + el.parentNode.id;
				var parent = el.parentNode;

				var element = document.getElementById(id);
				if (element) {
					element.parentNode.removeChild(element);
				}

				var div = document.createElement('div');
				div.style.color = 'red';
				div.style.marginBottom = '10px';
				div.style.textAlign = 'left';
				if (message != undefined) {div.innerHTML = message;} else {div.innerHTML = 'Это поле должно быть заполено';}
				div.id = id;

				parent.appendChild(div);
				el.focus;
				el.style.border = '1px solid red';
				el.classList.remove('text-input-main');
				el.classList.add('text-input-additional');
			}

			function success(el) {
				id = 'errorMessage-' + el.parentNode.id;
				var parent = el.parentNode;

				var element = document.getElementById(id);
				if (element) {
					element.parentNode.removeChild(element);
				}

				el.style.border = '0.8px solid rgb(128, 128, 128)';
				el.classList.remove('text-input-additional');
				el.classList.add('text-input-main');
			}


		}

		new NumbersInput(document.forms.signup.phone);

		function NumbersInput(input) {
		    input.onkeypress = function(e) {
		      e = e || event;

		      if (e.ctrlKey || e.altKey || e.metaKey) return;

		      var chr = getChar(e);

		      // с null надо осторожно в неравенствах,
		      // т.к. например null >= '0' => true
		      // на всякий случай лучше вынести проверку chr == null отдельно
		      if (chr == null) return;

		      if (chr < '0' || chr > '9') {
		        return false;
		      }
		    }
		}

		function getChar(event) {
		  if (event.which == null) { // IE
		    if (event.keyCode < 32) return null; // спец. символ
		    return String.fromCharCode(event.keyCode)
		  }

		  if (event.which != 0 && event.charCode != 0) { // все кроме IE
		    if (event.which < 32) return null; // спец. символ
		    return String.fromCharCode(event.which); // остальные
		  }

		  return null; // спец. символ
		}
		
	</script>
</body>
</html>