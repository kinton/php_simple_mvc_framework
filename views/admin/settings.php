<script>
	new HideAdder();
</script>
<?php if ($this->param == 'menu') { ?>
<div class="row settings-inn">
	<!--<a href="<?php echo URL;?>admin/settings/manufacturers"><div class="btn btn-primary">Производители</div></a><br><br>-->
	<a href="<?php echo URL;?>admin/settings/categories" class="settings-inn-btn"><div class="btn btn-primary">Категории</div></a>
	<a href="<?php echo URL;?>admin/settings/delivery" class="settings-inn-btn"><div class="btn btn-primary">Страница "Доставка"</div></a>
	<a href="<?php echo URL;?>admin/settings/about" class="settings-inn-btn"><div class="btn btn-primary">Страница "О нас"</div></a>
	<a href="<?php echo URL;?>admin/settings/contacts" class="settings-inn-btn"><div class="btn btn-primary">Страница "Контакты"</div></a>
	<a href="<?php echo URL;?>admin/settings/site-constants" class="settings-inn-btn"><div class="btn btn-primary">Константы</div></a>
</div>
<?php } ?>
<?php if ($this->param == 'manufacturers') { ?>
<div class="row settings-inn">
	<div class="col-6" id="formAdder">
		<header class="about-block-title"><h2>Редактирование списка производителей</h2></header>
		<div class="center">
			<form method="post" name="manufacturerAdd" class="form admin-form" id="manufacturerAdd">
				<h1>Добавить нового производителя</h1>
				<div id="name-form-element" class="form-element col-12">
					<strong>Введите название производителя</strong><br>
					<input type="text" name="name" placeholder="Производитель" class="text-input text-input-main" style="width: 312px; max-width: 95%;" autofocus required>
				</div>
				<div class="form-element col-12">
					<strong>Загрузите логотип производителя</strong>
					<input id="manufacturerPhoto" type="file" name="manufacturerPhotos" value="" required>
					<p class="help-block">Рекомендуется формат .jpeg</p>
				</div>
				<div class="form-element col-12">
					<strong>Заполните описание производителя</strong><br>
					<textarea name="description" required form="manufacturerAdd" maxlength='1000' class="text-input text-input-main" rows="10" style="height: auto;" wrap="hard"></textarea>
				</div>
				<input type="submit" class="button real-button primary" value="Добавить">
				<div id="answer">
				
				</div>
			</form>

		</div>
	</div>
	<div class="col-6">
		<header class="about-block-title"><h2>Список производителей</h2></header>
		<!--<table class='settings-table'>
			<thead>
				<tr>
					<th>Id</th>
					<th>Название</th>
					<th>Логотип</th>
					<th>Описание</th>
					<th>Создано</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
		<?php
			/*while ($row = $this->manufacturers->fetch())
			{
				echo '<tr id="manufacturer_',$row['id'],'"><td>',$row['id'],'</td><td>',$row['name'],'</td><td>';
				if ($row['logo'] != '') {
					echo '<img style="width: 20px;" src="',$row['logo'],'">';
				} else {
					echo "Логотип не загружен";
				}
				echo '</td><td>',$row['description'],'</td><td>',$row['datetime'],'</td><td><div onclick="manufacturer.edit(',$row['id'],');" class="control-form">Редактировать</div><div onclick="manufacturer.delete(',$row['id'],');" class="control-form">Удалить</div></td>';
			}*/
		?>
			</tbody>
		</table> -->

		<div class="table" id="manufacturers-table">
			<div class="table-row theader">
				<div class="table-id">ID</div>
				<div class="table-name">Название</div>
				<div class="table-logo">Логотип</div>
				<div class="table-text">Описание</div>
				<div class="table-date">Изменено</div>
				<div class="table-action">Действие</div>
			</div>
			<?php
			while ($row = $this->manufacturers->fetch())
			{
				echo '<div class="table-row" id="manufacturer_',$row['id'],'"><div class="table-id">',$row['id'],'</div><div class="table-name">',$row['name'],'</div><div class="table-logo">';
				if ($row['logo'] != '') {
					echo '<img style="width: 20px;" src="/',$row['logo'],'">';
				} else {
					echo "Логотип не загружен";
				}
				echo '</div><div class="table-text">',$row['description'],'</div><div class="table-date">',$row['datetime'],'</div><div class="table-action"><div onclick="manufacturer.edit(',$row['id'],');" class="control-form">Редактировать</div><div onclick="manufacturer.delete(',$row['id'],');" class="control-form">Удалить</div></div></div>';
			}
			?>
		</div>
	</div>
</div>
<script>
	new ManufAdd (document.forms.manufacturerAdd, '<?php echo URL;?>admin/addManufacturer');
	var manufacturer = new Manufacturer ('<?php echo URL;?>admin/deleteManufacturer', '<?php echo URL;?>admin/editManufacturer');

</script>
<?php } ?>


<?php if ($this->param == 'categories') { ?>
<div class="row settings-inn">
	<div class="col-5" id="formAdder">
		<header class="about-block-title"><h2>Редактирование списка категорий</h2></header>
		<div class="center">
			<form method="post" name="categoryAdd" class="form admin-form" id="categoryAdd">
				<h1>Добавить новую категорию</h1>
				<div id="name-form-element" class="form-element col-12">
					<strong>Введите название категории</strong><br>
					<input type="text" name="name" placeholder="Категория" class="text-input text-input-main" style="width: 312px; max-width: 95%;" autofocus required>
				</div>
				<div class="form-element col-12">
					<strong>Загрузите картинку для страницы описания</strong>
					<input id="categoryPhoto" type="file" name="categoryPhoto" value="" required>
					<p class="help-block">Рекомендуется формат .jpeg</p>
				</div>
				<div class="form-element col-12">
					<strong>Заполните описание категории</strong><br>
					<textarea name="description" required form="categoryAdd" maxlength='1000' class="text-input text-input-main" rows="10" style="height: auto;" wrap="hard"></textarea>
				</div>
				<input type="submit" class="button real-button primary" value="Добавить">
				<div id="answer">
				
				</div>
			</form>

		</div>
	</div>
	<div class="col-7">
		<header class="about-block-title"><h2>Список категорий</h2></header>

		<div class="table" id="categories-table">
			<div class="table-row theader">
				<div class="table-id">ID</div>
				<div class="table-name">Название</div>
				<div class="table-logo">Картинка</div>
				<div class="table-text">Описание</div>
				<div class="table-int">Порядок</div>
				<div class="table-date">Создано</div>
				<div class="table-action">Действие</div>
			</div>
			<?php
			while ($row = $this->categories->fetch())
			{
				echo '<div class="table-row" id="category_',$row['id'],'"><div class="table-id">',$row['id'],'</div><div class="table-name">',$row['name'],'</div><div class="table-logo">';
				if ($row['logo'] != '') {
					echo '<img style="width: 20px;" src="/',$row['logo'],'">';
				} else {
					echo "Логотип не загружен";
				}
				echo '</div><div class="table-text">',$row['description'],'</div><div class="table-int">',$row['itemsorder'],'</div><div class="table-date">',$row['datetime'],'</div><div class="table-action"><div onclick="category.edit(',$row['id'],');" class="control-form">Редактировать</div><div onclick="category.delete(',$row['id'],');" class="control-form">Удалить</div></div></div>';
			}
		?>
		</div>
	</div>
</div>
<script>
	new CatAdd (document.forms.categoryAdd, '<?php echo URL;?>admin/addCategory');
	var category = new Category ('<?php echo URL;?>admin/deleteCategory', '<?php echo URL;?>admin/editCategory');

</script>
<?php } ?>

<?php if ($this->param == 'delivery' or $this->param == 'about' or $this->param == 'contacts') { ?>
	
<script src="<?php echo URL;?>libs/external/ckeditor_full/ckeditor.js"></script>
<?php
$pagesNames = [
	'delivery' => 'Доставка',
	'about' => 'О нас',
	'contacts' => 'Контакты'
];
?>
<h2>Редактирование страницы <?=$pagesNames[$this->param]?></h2>
<form id="formContainter" name="pageEditor" class="landing-form" action="<?=URL?>admin/editDeliveryPage" method="POST" data-toggle="validator" role="form" novalidate="true">
	<div class="block-group">
		<div class="form-group" style="width: 100%;">
			<textarea name="pageText" class="input-field" placeholder="" cols="50" rows="4" required><?=$this->pageText['paramValue']?></textarea>
			<script>
				CKEDITOR.replace( 'pageText', {
					language: 'ru',
					uiColor: '#F6F6F6'
				} );				
			</script>	
		</div>
		<input type="" name="pageName" value="<?=$this->param?>Page" style="display: none;">
		<div class="form-group" id="submitButton" style="position: static !important;">
			<button class="btn btn-primary" id="bidsSender">Изменить</button>
		</div>
	</div>

</form>

<script>
	new formSender(document.forms.pageEditor, url + 'admin/editSitePage', editLangingStatus, pageCollector);
	function pageCollector() {
		var ckeddate = {};
		ckeddate['pageText'] = CKEDITOR.instances.pageText.getData();
		return ckeddate;
	}
	function editLangingStatus(answer) {
		//alert(answer);
	    answer = JSON.parse(answer);
	    if (answer.status == 'completed') {
	        notify("Успешно!", 'success');
	    } else {
	        notify("Ошибка!" + answer, 'error');
	    }
	}
</script>

<?php } ?>

<?php if ($this->param == 'site-constants') { ?>
<script src="<?php echo URL;?>libs/external/ckeditor_full/ckeditor.js"></script>
<h2>Настройки сайта</h2>
<form id="formContainter" name="servicesEditor" class="landing-form" action="<?=URL?>admin/editService" method="POST" data-toggle="validator" role="form" novalidate="true">
	<div class="block-group">

		<?php foreach ($this->siteConstants as $key => $value): ?>
			<div class="form-group">
				<h4 class="element-descr"><?=$this->fieldsParams[$key][0]?></h4>
				<?php if ($this->fieldsParams[$key][1] == 'text'): ?>
					<input value="<?=$value?>" class="input-field" name="<?=$key?>" placeholder="<?=$this->fieldsParams[$key][2]?>" type="text" required>
				<?php elseif ($this->fieldsParams[$key][1] == 'textarea'): ?>
					<textarea name="<?=$key?>" cols="50" rows="4" placeholder="<?=$this->fieldsParams[$key][2]?>"><?=$value?></textarea>
				<?php endif; ?>
				<p><?=$this->fieldsParams[$key][3]?></p>
			</div>
		<?php endforeach; ?>
		<div class="form-group" id="submitButton" style="width: 100%; position: static !important;">
			<button class="btn btn-primary" id="bidsSender">Изменить</button>
		</div>
	</div>

</form>

<script>
	new formSender(document.forms.servicesEditor, url + 'admin/editSiteConstants', editLangingStatus);
	function editLangingStatus(answer) {
		//alert(answer);
	    answer = JSON.parse(answer);
	    if (answer.status == 'completed') {
	        notify("Успешно!", 'success');
	    } else if (answer.status == 'notFounded') {
	    	notify("Запись не найдена", 'error');
	    } else {
	        notify("Ошибка! Некоторые или все поля не обновлены: " + answer, 'error');
	    }
	}
</script>
<?php } ?>