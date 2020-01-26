
	<div class="about-block">
		<header class="about-block-title"><h2>Добавление нового товара в каталог</h2></header>
		<div class="center">
			<?php
				if (isset($this->addProductStatus)) {
					//print_r($this->addProductStatus);
					//echo "<script>alert(".$this->addProductStatus[2].")</script>";
					$answer = json_decode($this->addProductStatus);
					//echo $answer->id;
					if ($answer->status == 'empty') {
						//echo "<script>notify('Некоторые поля не заполнены', 'error');</script>";
					} elseif ($answer->status == 'alreadyRegistered') {
						echo "<script>notify('Данный продукт уже зарегистрирован', 'error');</script>";
					} elseif ($answer->status == 'no_manufacturer') {
						echo "<script>notify('Производитель не найден', 'error');</script>";
					} elseif ($answer->status == 'no_category') {
						echo "<script>notify('Категория не найдена', 'error');</script>";
					} elseif ($answer->status == 'isnt_numeric') {
						echo "<script>notify('Цена и количество должны быть числом!', 'error');</script>";
					} elseif ($answer->status == 'format') {
						echo "<script>notify('Неверный формат изображений', 'error');</script>";
					} elseif ($answer->status == 'error') {
						echo "<script>notify('Неизвестная ошибка', 'error');</script>";
					} elseif ($answer->status == 'good') {
						echo "<script>notify('<a class=\'notify-href\' href=\'".URL."admin/products/".$answer->id."\'>Успешно, перейти к просмотру и редакированию?</a>', 'success', 10000);</script>";
					} elseif ($answer->status == 'no_photos') {
						echo "<script>notify('Фото не загружены', 'error');</script>";
					} else {
						echo "<script>notify('",$answer,"', 'error');</script>";
					}
				}
			?>
			<form action="<?php echo URL;?>admin/add" method="post" name="productAdd" class="form admin-form" id="productAdd" enctype="multipart/form-data">
				<div class="row">
					<div class="form-element col-6">
						<strong>Введите название продукта</strong><br>
						<input type="text" name="name" placeholder="Наименование" class="text-input text-input-main" style="width: 312px;" autofocus required>
					</div>
				</div>
				<!-- <div class="row">
					<div class="form-element col-6">
						<strong>Введите название производителя</strong><br>
						<input type="text" name="manufacturer" placeholder="Производитель" class="text-input text-input-main" style="width: 312px;" required>
					</div>
					<div class="col-6 answer-place" id="answerPlaceManufacturer"></div>
				</div> -->
				<div class="row">
					<div class="form-element col-6">
						<strong>Выберите категорию</strong><br>
						<input type="text" name="category" placeholder="Категория" class="text-input text-input-main" style="width: 312px;" required>
					</div>
					<div class="col-6 answer-place" id="answerPlaceCategory"></div>
				</div>
				<div class="form-element col-12">
					<strong>Укажите минимальную стоимость</strong><br>
					<div class="div-shell"><input type="text" name="price" placeholder="Стоимость" class="text-input text-input-main" style="width: 250px;">рублей</div>
				</div>
				<div class="form-element col-12">
					<strong>Укажите комбинации стоимостей и параметров</strong><br>
					<textarea name="prices" placeholder="500 ! 25см  ! 300г&#10;1000 ! 40см  ! 600г" class="text-input text-input-main" style="width: 312px;"></textarea>
				</div>
				<!-- <div class="form-element col-12">
						<strong>Введите доступное количество</strong><br>
						<input type="text" name="quantity" placeholder="100" class="text-input text-input-main" style="width: 312px;">
				</div> -->
				<div class="form-element col-12">
						<strong>Укажите вес</strong><br>
						<input type="text" name="weight" placeholder="500г" class="text-input text-input-main" style="width: 312px;">
				</div>
				<div class="form-element col-12">
					<strong>Загрузите фотографии продукта (нажмите на поле или просто перетащите в него)</strong><br>
					<label class="uploadbutton" style="cursor: pointer;"><div class="button" >Выбрать</div><div class="input">Выберите фото, не более 20</div><input id="photos" type="file" multiple onchange="this.previousSibling.innerHTML = this.value;addMoreInput(this);" name="productPhoto[]" accept="image/jpeg,image/png" required></label><br>
					<!--<input accept="image/jpeg,image/png" id="manufacturerPhoto" type="file" name="productPhoto[]" value="" required>
					<input accept="image/jpeg,image/png" id="manufacturerPhoto" type="file" name="productPhoto[]" value="">
					<input accept="image/jpeg,image/png" id="manufacturerPhoto" type="file" name="productPhoto[]" value="">
					<p class="help-block">Рекомендуется формат .jpeg</p>-->
					<output id="list"></output>
				</div>
				<div class="form-element col-12">
					<strong>Введите состав продукта и информацию о нём (срок годности, аллегргическая информация)</strong><br>
					<textarea name="composition" form="productAdd" maxlength="2000" class="text-input text-input-main" rows="8"></textarea>
				</div>
				<div class="form-element col-12">
					<strong>Заполните описание продукта</strong>
					<textarea name="description" form="productAdd" maxlength='2000' class="text-input text-input-main" rows="8" wrap="hard"></textarea>
				</div>
				<input type="submit" name="" value="Готово" class="button real-button primary">
				<br>
			</form>
		</div>
	</div>
	<script>
		new InputShell(document.forms.productAdd.price);

		/*var z = function controller(answer) {
		    //getById('answerPlaceManufacturer').innerHTML = answer;
		    var ans = JSON.parse(answer);
		    var answer = 'Выберите из предложенных:<br>';
		    var i = 0;
		    for (i = 0; i < ans.i; i++) {
		    	answer += '<div class="variant-buble buble" onclick="deliverInInput(document.forms.productAdd.manufacturer, \'manufacturerVariant_'+ ans.id[i] +'\')" id="manufacturerVariant_'+ ans.id[i] +'">'+ans.name[i]+'</div>';
		    }
		    if (i == 0) {
		    	answer = 'Ничего не найдено, попробуйте ввести другой запрос!';
		    }
		    getById('answerPlaceManufacturer').innerHTML = answer;
		    getById('answerPlaceManufacturer').style.marginBottom = '30px';
		}
		new GetDataOnKeyUp(document.forms.productAdd.manufacturer, '<?php echo URL;?>admin/manufacturerChooser', z);*/

		var y = function controller(answer) {
		    //getById('answerPlaceManufacturer').innerHTML = answer;
		    var ans = JSON.parse(answer);
		    var answer = 'Выберите из предложенных:<br>';
		    var i = 0;
		    for (i = 0; i < ans.i; i++) {
		    	answer += '<div class="variant-buble buble" onclick="deliverInInput(document.forms.productAdd.category, \'categoryVariant_'+ ans.id[i] +'\')" id="categoryVariant_'+ ans.id[i] +'">'+ans.name[i]+'</div>';
		    }
		    if (i == 0) {
		    	answer = 'Ничего не найдено, попробуйте ввести другой запрос!';
		    }
		    getById('answerPlaceCategory').innerHTML = answer;
		    getById('answerPlaceCategory').style.marginBottom = '30px';
		}
		new GetDataOnKeyUp(document.forms.productAdd.category, '<?php echo URL;?>admin/categoryChooser', y);

		new NumbersInput(document.forms.productAdd.price);
		new NumbersInput(document.forms.productAdd.quantity);

		/*var photosController = 1;
		function addMoreInput(elem) {
			if (photosController < 5) {
				var label = document.createElement('label');
				label.className = 'uploadbutton';
				label.style.cursor = 'pointer';
				label.style.display = 'block';
				label.style.marginBottom = '5px';
				label.innerHTML = '<div class="button" >Выбрать</div><div class="input">Вы можете выбрать ещё фото</div><input type="file" onchange="this.previousSibling.innerHTML = this.value;addMoreInput(this);" name="productPhoto[]" accept="image/jpeg,image/png">';
				elem.parentNode.parentNode.insertBefore(label, elem.parentNode.nextSibling);
				photosController += 1;
			} else {
				notify('Предел фотографий - 5');
			}
		}*/

function handleFileSelect(evt) {
	document.getElementById('list').innerHTML = '';

    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }

    getById('photos').previousSibling.innerHTML = 'Выбрано ' + i + ' файлов';
  }

  document.getElementById('photos').addEventListener('change', handleFileSelect, false);
		
	</script>