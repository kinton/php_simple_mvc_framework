<? //print_r($this->feedbacks[0]['vk_page'])?>
<?php
$linkBase = $_SERVER['SERVER_NAME'].explode('?', $_SERVER['REQUEST_URI'])[0].'?';
foreach ($_GET as $key => $value) {
  if ($key != 'rowOnPage' and $key != 'url' and $key != 'page') $linkBase .= $key.'='.$value.'&';
}
$linkBase .= 'page=1&';
$rowOptions = array(10, 50, 100);
?>
<b style="margin: 20px;">Выводить по: 
<?php
foreach ($rowOptions as $value) {
  echo '<a href="//',$linkBase,'rowOnPage=',$value,'">',$value,'</a> ';
}
?>
</b>

<div class="cards-cap">
<?php foreach ($this->feedbacks as $key => $value): ?>
  <div class="item-card row" style="width: 600px;">
  VK: <?php
        if (strpos($value['vk_page'], "//") === false) {
          echo '<a href="//',$value['vk_page'],'">',$value['vk_page'],'</a>';
        } else {
          echo '<a href="',$value['vk_page'],'">',$value['vk_page'],'</a>';
        }
      ?> 
  
  <br><br>
  Телефон: <input value="<?=$value['phone']?>">
  <br>
  Телефон (форматированный): <input value="<?=$value['phone_formatted']?>">
  <br><br>
  <textarea style="width: 100%; height: 200px;" name=""><?=$value['feedback']?></textarea>
  <br><br>
  <?=$value['dateAndTime']?>
  </div>
<?php endforeach; ?>
</div>
<div class="container pages-navigation">
  <?php
    $linkBase = $_SERVER['SERVER_NAME'].explode('?', $_SERVER['REQUEST_URI'])[0].'?';
    foreach ($_GET as $key => $value) {
      if ($key != 'page' and $key != 'url') $linkBase .= $key.'='.$value.'&';
    }
    if ($this->pagesQuant > 1) {
      echo "Страницы ";
      if ($this->currPage > 6) 
      {
        echo '<a href="//',$linkBase,'page=1">1</a> ... ';
      } else {  
        if ($this->currPage == 1) {
          echo '1 ';
        } else {
          echo '<a href="//',$linkBase,'page=1">1</a> ';
        }
      }

      for ($i = $this->currPage - 5; $i < $this->currPage + 6; $i++) {
        if ($i > 1 and $i < $this->pagesQuant) {
          if ($this->currPage == $i) {
            echo ' ',$i,' ';
          } else {
            echo ' <a href="//',$linkBase,'page=',$i,'">',$i,'</a> ';
          }
        }
      }

      if ($this->currPage < $this->pagesQuant - 6) 
      {
        echo ' ... <a href="//',$linkBase,'page=',$this->pagesQuant,'">',$this->pagesQuant,'</a>';
      } else {
        if ($this->currPage == $this->pagesQuant) {
          if ($this->currPage != 1) echo ' ',$this->pagesQuant,' ';
        } elseif ($this->pagesQuant > 0) {
          echo ' <a href="//',$linkBase,'page=',$this->pagesQuant,'">',$this->pagesQuant,'</a>';
        }

      }
    }

  ?>
</div>
<style type="text/css" media="screen">
  .content {
    margin-top: 20px;
  }
  .pages-navigation {
    text-align: center;
    font-size: 24px;
    margin: 20px auto;
  }
</style>