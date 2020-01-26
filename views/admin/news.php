<script src="<?php echo URL;?>libs/external/ckeditor_full/ckeditor.js"></script>
<h2>Настройки новостей</h2>
<form id="formContainter" name="servicesEditor" class="landing-form" action="<?=URL?>admin/editService" method="POST" data-toggle="validator" role="form" novalidate="true">
	<h3>Редактировать новости</h3>
	<div class="block-group">
		<div class="form-group">
			<h4 class="element-descr">Название услуги</h4>
			<input value="<?=$this->news[0]['title']?>" class="input-field" name="service_title" placeholder="Например, 'Съёмка свадеб' " type="text" required>
		</div>

		<div class="form-group">
			<h4 class="element-descr">Ссылка на обложку услуги</h4>
			<input value="<?=$this->news[0]['image']?>" class="input-field" name="service_image" placeholder="Загрузить через 'Менеджер изображений' или указать ссылку со стороннего ресурса" type="text" required>
		</div>

		<div class="form-group">
			<h4 class="element-descr">Ссылка на страницу услуги</h4>
			<input value="<?=$this->news[0]['link']?>" class="input-field" name="service_url" placeholder="Например, 'wedding-photography' " type="text" required>
			<p class="reg-term">Допускаются только символы латинского алфавита, цифры, дефис и подчёркивание!</p>
		</div>

		<div class="form-group" style="width: 100%;">
			<h4 class="element-descr">Страница</h4>
			<textarea name="article" class="input-field" placeholder="Например, 'Лучшая фотостудия Казани'" cols="50" rows="4" required><?=$this->news[0]['article']?></textarea>
			<script>
				CKEDITOR.replace( 'article', {
					language: 'ru',
					uiColor: '#F6F6F6'
				} );				
			</script>	
		</div>
		<div class="form-group" id="submitButton" style="position: static !important;">
			<button class="btn btn-primary" id="bidsSender">Изменить</button>
		</div>
		<div class="form-group" style="width: 100%;">			
			<div class="btn btn-danger" style="float: right;" onclick="askOfDelete();">Удалить</div>
		</div>
	</div>

</form>

<script>
	new formSender(document.forms.servicesEditor, url + 'admin/editService', editLangingStatus, articleCollector);
	function askOfDelete() {
		var ch = confirm("Уверены, что хотите удалить?");
		if (ch == true) {
			deleteService(<?=$this->news[0]['id']?>, deleteStatus);
		}
	}
	function deleteService(id) {
		var link = url + "admin/deleteService";

		var data = {
			"id": id
		}

		sendData(JSON.stringify(data), link, deleteStatus);
		return false;
	}
	function deleteStatus(answer) {
		//alert(answer);
		answer = JSON.parse(answer);
	    if (answer.status == 'completed') {
	        //getById(buttonContId).innerHTML = 'Успешно!';
	        //getById(buttonContId).style.backgroundColor = "green";
	        notify("Успешно!", 'success');
	        window.location.href = url + "admin/news";
	    } else if (answer.status == 'notFounded') {
	    	notify("Запись уже удалена", 'error');
	        window.location.href = url + "admin/news";
	        //getById(buttonContId).innerHTML = 'Ошибка получения данных!';
	        //getById(buttonContId).style.backgroundColor = "red";
	    } else {
	        //error
	        //getById(buttonContId).innerHTML = 'Ошибка, обратитесь в поддержку!';
	        //getById(buttonContId).style.backgroundColor = "red";
	        notify("Ошибка!" + answer, 'error');
	    }
	}
	function articleCollector() {
		var ckeddate = {};
		ckeddate['article'] = CKEDITOR.instances.article.getData();
		ckeddate['id'] = <?=$this->news[0]['id']?>;
		return ckeddate;
	}
	function editLangingStatus(answer) {
		//alert(answer);
	    //var buttonContId = 'bidsSender';
	    //var style = 'style=""';
	    //alert(answer);
	    //return false;
	    answer = JSON.parse(answer);
	    if (answer.status == 'completed') {
	        //getById(buttonContId).innerHTML = 'Успешно!';
	        //getById(buttonContId).style.backgroundColor = "green";
	        notify("Успешно!", 'success');
	        var newUrl = removeUrlLevel();
	        window.history.pushState(null, null, newUrl);
	        newUrl = addUrlLevel(answer.link);
	        window.history.pushState(null, null, newUrl);
	    } else if (answer.status == 'notFounded') {
	    	notify("Запись не найдена", 'error');
	        //getById(buttonContId).innerHTML = 'Ошибка получения данных!';
	        //getById(buttonContId).style.backgroundColor = "red";
	    } else {
	        //error
	        //getById(buttonContId).innerHTML = 'Ошибка, обратитесь в поддержку!';
	        //getById(buttonContId).style.backgroundColor = "red";
	        notify("Ошибка!" + answer, 'error');
	    }
	}
</script>