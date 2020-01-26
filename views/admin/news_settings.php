<script src="<?php echo URL;?>libs/external/ckeditor_full/ckeditor.js"></script>
<h2>Настройки услуг</h2>
<form id="formContainter" name="servicesAdder" class="landing-form" action="<?=URL?>admin/addService" method="POST" data-toggle="validator" role="form" novalidate="true">
	<h3>Добавить услугу</h3>
	<div class="block-group">
		<div class="form-group">
			<h4 class="element-descr">Название услуги</h4>
			<input value="" class="input-field" name="service_title" placeholder="Например, 'У нас новые скидки!' " type="text" required>
		</div>

		<div class="form-group">
			<h4 class="element-descr">Ссылка на обложку услуги</h4>
			<input value="" class="input-field" name="service_image" placeholder="Загрузить через 'Менеджер изображений' или указать ссылку со стороннего ресурса" type="text" required>
		</div>

		<div class="form-group">
			<h4 class="element-descr">Ссылка на страницу услуги</h4>
			<input value="" class="input-field" name="service_url" placeholder="Например, 'new_discounts' " type="text" required>
			<p class="reg-term">Допускаются только символы латинского алфавита, цифры, дефис и подчёркивание!</p>
		</div>

		<div class="form-group" style="width: 100%;">
			<h4 class="element-descr">Страница</h4>
			<textarea name="article" class="input-field" placeholder="Например, 'Лучшая фотостудия Казани'" cols="50" rows="4" required></textarea>
			<script>
				CKEDITOR.replace( 'article', {
					language: 'ru',
					uiColor: '#F6F6F6'
				} );				
			</script>	
		</div>
		<div class="form-group" id="submitButton" style="position: static !important;">
			<button class="btn btn-primary" id="bidsSender">Применить</button>
		</div>
	</div>

</form>

<script>
	new formSender(document.forms.servicesAdder, url + 'admin/addService', editLangingStatus, articleCollector);
	function articleCollector() {
		var ckeddate = {};
		ckeddate['article'] = CKEDITOR.instances.article.getData();
		return ckeddate;

	}
	function editLangingStatus(answer) {
		//alert(answer);
	    //var buttonContId = 'bidsSender';
	    //var style = 'style=""';
	    //return false;
	    answer = JSON.parse(answer);
	    if (answer.status == 'completed') {
	        //getById(buttonContId).innerHTML = 'Успешно!';
	        //getById(buttonContId).style.backgroundColor = "green";
	        notify("Успешно!", 'success');

	        var newService = document.createElement('div');
	        newService.classList.add('service-block');

	        var ttn = '';
	        ttn += '<a href="'+url+'admin/news/'+answer.link+'" onclick="/*moreInfoModalActivator(this); return false;*/"><div class="service">';
            ttn += '<div class="service-image" style="background: url(\''+answer.image+'\');"></div>';
                
            ttn += '<header>';
                ttn += '<b>'+answer.title+'</b>';
            ttn += '</header>';
            ttn += '<article>';
                ttn += '<div class="row" style="margin:5px;text-align:right;">';
                    ttn += '<div class="btn btn-primary">Редактировать</div>';                   
                ttn += '</div>';
            ttn += '</article>';
        	ttn += '</div></a>';

        	newService.innerHTML = ttn;

	        document.getElementById('servicesCont').insertBefore(newService, document.getElementById('servicesCont').firstChild);
	    } else if (answer.status == 'alreadyRegistered') {
	    	notify("Данная ссылка уже зарегистрирована в системе", 'error');
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


<h3>Изменить услуги</h3>
<div class="services-cont" id="servicesCont">
<?php
//print_r($this->landingData);
for ($i = count($this->landingData) - 1; $i > -1; $i--) {
	?>
    <div class="service-block">
        <a href="<?php echo URL;?>admin/news/<?=$this->landingData[$i]['link']?>" onclick="/*moreInfoModalActivator(this); return false;*/"><div class="service">
            <!--<img src="<?php echo URL;?>public/images/services/1494360180-0.jpg" alt="">-->
            <div class="service-image" style="background: url('<?=$this->landingData[$i]["image"]?>');">
                
            </div>
            <header>
                <b><?=$this->landingData[$i]['title']?></b>
            </header>
            <article>
                <div class="row" style="margin:5px;text-align:right;">
                	<div class="btn btn-primary">Редактировать</div>                  
                </div>
            </article>
        </div></a>
    </div>
	<?php
}
?>
</div>