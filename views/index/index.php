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
    <div id="header" class="header-container">
      <div class="header">
        <div class="left-header">
          <div class="logo">Deproh</div>
        </div>
        <div class="right-header">
          <a href="<?php echo URL; ?>login" class="button transparent">Войти</a>
          <a href="<?php echo URL; ?>signup" class="button primary">Зарегистрироваться</a>
        </div>
      </div>
    </div>
    <div class="main-content" id="first_page_block">
      <div class="about-deproh">
        <div class="description">
          <div class="brief-description">
            <h1 class="name">Deproh</h1>
            <h2 class="subheating">Покупайте продукты в интернете<br>точно так же, как и в обычном магазине!</h2>
            <a href="<?php echo URL; ?>login" class="button primary no-margin">Начните пользоваться сейчас!</a>
          </div>
        </div>
      </div>
    </div>
    <div class="content content-grey" id="about">
    	<div class="about-block">
    		<header class="about-block-title"><h2>Deproh - это онлайн-магазин для быстрого и удобного заказа продуктов на дом</h2></header>
    		<div class="about-block-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
    		<div class="statistic">
    			<div class="about-company-stat-block col-3">
    				<div class="about-company-stat-number">12000</div>
    				<div class="about-company-stat-descr">Постоянных клиентов</div>
    			</div>
    			<div class="about-company-stat-block col-3">
    				<div class="about-company-stat-number">90</div>
    				<div class="about-company-stat-descr">Заказов ежедневно</div>
    			</div>
    			<div class="about-company-stat-block col-3">
    				<div class="about-company-stat-number">130</div>
    				<div class="about-company-stat-descr">Используют подписку</div>
    			</div>
    			<div class="about-company-stat-block col-3">
    				<div class="about-company-stat-number">12</div>
    				<div class="about-company-stat-descr">Автомобилей</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="content content-gradient-green-blue" id="description">
    	<div class="about-block">
				<header class="about-block-title"><h2>Что мы предлагаем</h2></header>
    		<div class="statistic">
    			<div class="about-company-stat-block col-3 white-cover">
    				<h3>Всегда свежее</h3>
    				<div class="about-company-stat-number"><img src="<?php echo URL; ?>public/icons/herbal-spa-treatment-leaves.png" alt="" class="about-offer-png"></div>
    				<div class="about-company-stat-descr">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium quia nobis numquam voluptates delectus, consequuntur hic fugit ab sit minima.</div>
    			</div>
    			<div class="about-company-stat-block col-3 white-cover">
    				<h3>Быстрая доставка</h3>
    				<div class="about-company-stat-number"><img src="<?php echo URL; ?>public/icons/fast-delivery.png" alt="" class="about-offer-png"></div>
    				<div class="about-company-stat-descr">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic harum totam enim eos ipsum nobis reiciendis et alias aut deleniti.</div>
    			</div>
    			<div class="about-company-stat-block col-3 white-cover">
    				<h3>Большой ассортимент</h3>
    				<div class="about-company-stat-number"><img src="<?php echo URL; ?>public/icons/photography-landscape-mode.png" alt="" class="about-offer-png"></div>
    				<div class="about-company-stat-descr">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa commodi, quia quidem rerum nobis maiores, minima magni. Consectetur cumque, quam?</div>
    			</div>
    			<div class="about-company-stat-block col-3 white-cover">
    				<h3>Низкие цены</h3>
    				<div class="about-company-stat-number"><img src="<?php echo URL; ?>public/icons/piggy-bank-with-coin.png" alt="" class="about-offer-png"></div>
    				<div class="about-company-stat-descr">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam tenetur ullam, laudantium fuga asperiores laboriosam.</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="content map">
    	<div class="about-block">
    		<header class="about-block-title"><h2>Доставка по всей Казани</h2></header>
    	</div>
    </div>
    <footer class="footer" id="footer">
      <div class="left-footer col-6">
        <a href="<?php echo URL; ?>index">Index</a>
        <a href="<?php echo URL; ?>help">Help</a>
        <a href="<?php echo URL; ?>terms">Правила</a>
        <a href="<?php echo URL; ?>copyright">Авторские права</a>
        <a href="<?php echo URL; ?>cookie_police">Cookies</a>
      </div>
      <div class="right-footer col-6">Deproh, all rights reserved<br>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>

    </footer>
    <script src="<?php echo URL; ?>public/js/default.js"></script>
    <script>
        var changeColor = new ChangeColorOnScroll('header', ['black', 'none'], ['about', 'description']);
        window.onscroll = function() {
            changeColor.change();
        }
        window.onload = function() {
            changeColor.change();
        }
    </script>
</body>

</html>