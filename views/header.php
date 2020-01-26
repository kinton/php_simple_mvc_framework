<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8"><meta name="yandex-verification" content="e020954fb8af6b13" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <!--<link rel="canonical" href="https://momentopizza.ru/feed" />-->
  <?php if (isset($this->title)) { echo "<title>",$this->title,'</title>'; } else { echo "<title>",$this->siteConstants['siteName'],"</title>";} ?>
  <?php if (isset($this->siteUri[0]) and $this->siteUri[0] == 'feed') echo '<meta name="description" content="'.str_replace(array('\'', '"'), "", $this->siteConstants['siteDescription']).'">';
        elseif (isset($this->pageDescription)) echo '<meta name="description" content="'.str_replace(array('\'', '"'), "", $this->pageDescription).', '.$this->siteConstants['siteDescription'].'">';
        else echo '<meta name="description" content="'.str_replace(array('\'', '"'), "", $this->siteConstants['siteDescription']).'">';?>
  
  <!-- OG MAP HERE -->
  <meta property="og:type" content="website" />
  <?php if (isset($this->title)) { echo '<meta property="og:title" content="',$this->title,'" />'; } else { echo '<meta property="og:title" content="',$this->siteConstants['siteName'],'" />';} ?>
  <meta property="og:site_name" content="<?=$this->siteConstants['siteName']?>"/>
  <meta property="og:url" content="<?php echo URL,$_SERVER['REQUEST_URI']; ?>"/>
  <?php if (isset($this->siteUri[0]) and $this->siteUri[0] == 'feed') echo '<meta property="og:description" content="'.str_replace(array('\'', '"'), "", $this->siteConstants['siteDescription']).'">';
        elseif (isset($this->pageDescription)) echo '<meta property="og:description" content="'.str_replace(array('\'', '"'), "", $this->pageDescription).'">';
        else echo '<meta property="og:description" content="'.str_replace(array('\'', '"'), "", $this->siteConstants['siteDescription']).'">';?>
  <?php if (!isset($this->pagePhotoPreview) or $this->pagePhotoPreview == ''): ?>
    <meta content="<?=URL?>public/images/Momento2TR.png" property="og:image">
    <meta property="og:image:width" content="4096"/>
    <meta property="og:image:height" content="4096"/>
  <?php
    else:
    $pppsize = getimagesize($this->pagePhotoPreview);
  ?>
    <meta content="<?=$this->pagePhotoPreview?>" property="og:image">
    <meta property="og:image:width" content="<?=$pppsize[0]?>"/>
    <meta property="og:image:height" content="<?=$pppsize[1]?>"/>
  <?php endif;?>

  <?php if (isset($this->siteConstants['siteKeywords'])) echo '<meta name="keywords" content="'.$this->siteConstants['siteKeywords'].'" />';?>
  
  <link href="<?=URL?>public/images/Momento32.png" rel="shortcut icon" />
  <link href="<?=URL?>public/images/Momento32.png" rel="icon" type="image/png" />

  <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css">
  <link rel="stylesheet" href="<?php echo URL; ?>public/fontawesome-free-5.1.0-web/css/all.css">
  <!--<link rel="stylesheet" href="<?php echo URL; ?>public/css/bootstrap.min.css">--> 
  <?php
  if(isset($this->css)) {
   foreach($this->css as $css) {
    echo '<link rel="stylesheet" href="',URL,'views/',$css,'">';
   }
  }
  ?>


  <?php
  if(isset($this->js)) {
     foreach($this->js as $js) {
      echo '<script src="'.URL.'views/'.$js.'"></script>';
     }
    }
  ?>
  <script src="<?php echo URL; ?>public/js/default.js"></script>
  <!--<script src="<?php echo URL; ?>public/js/jquery.js"></script>
  <script src="<?php echo URL; ?>public/js/bootstrap.min.js"></script>-->

</head>

<body class="main-body" <?php if (isset($this->whiteBackground)) {if ($this->whiteBackground == 'true') {echo "style='background: white;'";}} ?>>
<script>
new RollNavOnScroll(53);
<?php echo "var url = '",URL,"';";?>
<?php if (isset($this->pageType)) {
  echo "var pageType = '",$this->pageType,"';";
} ?>
</script>
<?php Session::init(); ?>
<div id="header" class="header-container white-header">
  <div class="header main-header names-and-actions-handler">
    <div class="left-header">
      <a href="/" style="color: black;"><div class="logo"><?='<img src="/public/images/MomentoHorizontal.jpg" alt="'.$this->siteConstants['siteName'].'">'?></div></a>
      <?php //if (isset($this->pageName)) {echo '<div class="page-name">',$this->pageName,'</div>';} ?>
      <div class="page-name"><h2 style="color: #ae8a4d; margin: 0; font-size: 21px;">Оренбург</h2></div>
    </div>
    <div class="right-header" id="right-header" style="margin-top: 3px;">
      <div style="float: left; margin-top: 7px;">
        <span class="lupa on-mobile" id="searchLupa" onclick="showWindow(this.id, 'productSearcherWindow', false, '<input id=\'productSearcherInModal\' class=\'search-input\' style=\'width: 94.12%;\' type=\'text\' placeholder=\'Поиск\'>');"></span>
        <input class="search-input on-desctop" type="text" placeholder="Поиск" id="productSearcher" onclick="showWindow(this.id, 'productSearcherWindow', true)">
        <script>
          window.addEventListener("load", function() {new GetDataOnKeyUp(getById('productSearcher'), '<?php echo URL;?>dashboard/mainSearcher', mainSearcher);});
          
          getById('searchLupa').addEventListener('click', activateSearch);
          function activateSearch() {
            new GetDataOnKeyUp(getById('productSearcherInModal'), '<?php echo URL;?>dashboard/mainSearcher', mainSearcher);
          }
        </script>
        <div id="productSearcherWindow" class="profile-window hidden">
          <div id="starterPlace"></div>
          <div id="answerPlaceSearch"><div style="padding: 50px; text-align: center;">Начните вводить запрос</div></div>
        </div>
      </div>
      <?php if (!isset($this->pageType)) {?>
        <!-- <div style="float: left;" class="on-desctop-headb"><a onclick='showPhones(<?=json_encode(explode("\n", $this->siteConstants['phones']))?>); if (typeof yaCounter49676566 != "undefined") yaCounter49676566.reachGoal("callUs"); return false;' href="#" class="button primary"><i class="fa fa-phone fa-2" style="transform: scale(-1, 1);"></i> Позвоните нам</a></div>
        <div style="float: left;" class="on-mobile-headb"><a onclick='showPhones(<?=json_encode(explode("\n", $this->siteConstants['phones']))?>); if (typeof yaCounter49676566 != "undefined") yaCounter49676566.reachGoal("callUs"); return false;' href="#" class="button primary"><i class="fa fa-phone fa-2" style="transform: scale(-1, 1);"></i> нам</a></div>
        <div style="float: left;" class="on-desctop-headb"><a onclick="formModalActivator(); if (typeof yaCounter49676566 != 'undefined') yaCounter49676566.reachGoal('backCall'); return false;" href="#" class="button primary"><i class="fa fa-phone fa-2" style="transform: scale(-1, 1);"></i> Закажите обратный звонок</a></div>
        <div style="float: left;" class="on-mobile-headb"><a onclick="formModalActivator(); if (typeof yaCounter49676566 != 'undefined') yaCounter49676566.reachGoal('backCall'); return false;" href="#" class="button primary">обратный <i class="fa fa-phone fa-2" style="transform: scale(-1, 1) translate(0, -1px);"></i></a></div> -->
        <a style="font-size: 30px !important; margin: 0 0 0 10px; text-decoration: none;" href="tel:<?php echo explode("\n", $this->siteConstants['phones'])[0];?>"><?php echo explode("\n", $this->siteConstants['phones'])[0];?></a>
        <div id="phonePulseB" class="pulsePar"><span class="pulse" onclick='showPhones(<?=json_encode(explode("\n", $this->siteConstants['phones']))?>); if (typeof yaCounter49676566 != "undefined") yaCounter49676566.reachGoal("callUs"); return false;'><i class="fa fa-phone fa-2" style="transform: scale(-1, 1); color: white; font-size: 22px;"></i></span></div>
      <?php } else {?>
        <div style="float: left;"><a href="<?php echo URL; ?>feed" class="button primary">Назад на сайт</a></div>
      <?php } ?>
      <!--<div id="profile-source" class="profile-circle" onclick="showWindow(this.id, 'profileWindow')">
        <div class="human-body"></div>
        <div class="human-head"></div>
      </div>-->
      <div id="profileWindow" class="profile-window hidden">
        <div class="profile-card">
          <div class="col-3">
            <div id="profile-source" class="profile-circle not-in-header">
              <div class="human-body"></div>
              <div class="human-head"></div>
            </div>
          </div>
          <div class="col-9">
            <b><?php echo $this->navData['name'],' ',$this->navData['surname'];?></b><br>
            <?php echo $this->navData['email'];?><br><br>
            <a href="<?php echo URL;?>settings" class="button primary settings">Настройки</a>
          </div>
        </div>
        <div class="profile-footer">
          <a href="<?php echo URL;?>dashboard/logout" class="button usual">Выйти</a>
        </div>
      </div>
    </div>
  </div>
  <div class="nav-menu">
    <div class="menu-header header main-header" >
      <div class="menu" id="siteNavbarMenu">
      <?php if (!isset($this->pageType)) {?>
        <!--<a href="<?php echo URL?>feed">Рекомендации</a>-->
        <a href="<?php echo URL?>catalog">Меню</a>
        <a href="<?php echo URL?>news">Акции</a>
        <a href="<?php echo URL?>pages/delivery">Доставка и оплата</a>
        <!--<a>Ещё</a>-->
      <?php } else {?>
        <a href="<?php echo URL?>admin/add">Добавить</a>
        <a href="<?php echo URL?>admin/products"><?php if (Session::get('role') == 'admin') {echo "Добавленные мной";} else if (Session::get('role') == 'owner') {echo "Список товаров";} ?></a>
        <!--<a href="<?php echo URL?>admin/bids">Заявки (обратный звонок)</a>-->
        <a href="<?php echo URL?>admin/feedbacks">Отзывы</a>
        <?php if (Session::get('role') == 'owner'): ?><a href="<?=URL?>admin/settings/menu">Настройки сайта</a> <?php endif; ?>
        <?php if (Session::get('role') == 'owner'): ?><a href="<?=URL?>admin/news">Редактор новостей</a> <?php endif; ?>
        <a href="<?php echo URL?>admin/imagesManager">Менеджер изображений</a>
      <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php if ((Session::get('role') == 'admin' or Session::get('role') == 'owner') and (!isset($this->pageType))) { ?>
<div class="turn-account-button">
  <a href="<?php echo URL?>admin" class="button primary">Перейти в админ-панель</a>
</div>
<?php } ?>

<!-- Блок ниже - источник для модальных окон -->
<div id="formContainterSafe" style="display: none;">
      <h4>Оставьте заявку</h4>
          <div class="form-group">
            <input class="form-control" name="name" placeholder="Полное имя" type="text" required>
          </div>
          <div class="form-group">
            <input class="form-control" name="phone" placeholder="Телефон" type="tel" required>
          </div>
          <div class="form-group">
            <input class="form-control" name="email" placeholder="Email (необязательно)" type="email">
          </div>
        <div class="form-group" style="display: none;">
            <input class="form-control" name="partner" placeholder="" type="text" value="<?php if (isset($_GET['p'])) echo $_GET['p'];?>">
        </div>
          <div class="form-group">
            <button class="btn btn-block btn-send orderService" id="bidsSender" name="sendB">Отправить заявку</button>
          </div>
          <p class="reg-term">Мы свяжемся с вами.</p>
</div>

<div id="content" class="content">