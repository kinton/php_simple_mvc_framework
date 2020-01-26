function ManufAdd (form, url) {

	form.onsubmit = function() {
		var manufacturer = form.name.value;
		var logo = form.manufacturerPhoto.files[0];
		var description = form.description.value

		if (manufacturer.length > 0) {
			if (manufacturer.length <= 50) {
				
				manufacturerAdder(manufacturer, logo, description);
							
			} else {
				error('Слишком длинное название производителя.');
			}
		} else {
				error('Введите название производителя.');
		}

		return false;
	}

	function error(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button error">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function success(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button success">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function answerController(message) {
		if (message.answer == 'good') {
			var toInsert = '<div class="table-id">' + message.id + '<div class="new">NEW</div></div><div class="table-name">' + message.name + '</div><div class="table-logo">';
			if (message.logo != '') {
				toInsert += '<img style="width: 20px;" src="/' + message.logo + '">';
			} else {
				toInsert += "Логотип не загружен";
			}
			toInsert += '</div><div class="table-text">' + message.description + '</div><div class="table-date">' + message.datetime + '</div><div class="table-action"><div onclick="manufacturer.edit(' + message.id + ');" class="control-form">Редактировать</div><div onclick="manufacturer.delete(' + message.id + ');" class="control-form">Удалить</div></div></div>';
			var div = document.createElement('div');
			div.innerHTML = toInsert;
			div.classList = 'table-row';
			div.id = 'manufacturer_' + message.id;
			getById('manufacturers-table').insertBefore(div, getById('manufacturers-table').children[1]);

			success('Успешно!');
		} else if (message.answer == 'empty') {
			error('Некоторые поля не заполены!');
		} else if (message.answer == 'repeat') {
			error('Данный производитель уже зарегистрирован!');
		} else if (message.answer == 'format') {
			error('Допустимы только следующие форматы изображения: JPG, PNG или GIF!')
		} else if (message.answer == 'error') {
			error('Неизвестная ошибка, попробуйте ещё раз!');
		}
	}

	function manufacturerAdder (manufacturer, logo, description) {

			if ((typeof manufacturer != undefined) && (typeof logo != undefined) && (typeof description != undefined)) {

		    var xhr = getXmlHttp();

			xhr.onload = xhr.onerror = function() {

		        if (this.readyState == 4 && this.status == 200) {
		           	var answer = xhr.responseText;
		            answerController(JSON.parse(answer));
		        } else {
					error('Ошибка соединения с сервером');
		        }
			      
	        }
		       		
		    var formData = new FormData();
		    formData.append('manufacturer', manufacturer);
		    formData.append('logo', logo);
		    formData.append('description', description);

		   	xhr.open("POST", url, true);
		   	xhr.send(formData);

		} else {
			error('Некоторые поля не заполены');
		}
	}
}

function HideAdder() {
	window.onscroll = function() {
		var form = getById('formAdder');
        var scrolled = window.pageYOffset || document.documentElement.scrollTop;
        if (scrolled > 850) {
        	form.classList.remove('col-6');
        	form.classList.add('col-3');
        } else {
        	form.classList.remove('col-3');
        	form.classList.add('col-6');
        }
	}
}

function Manufacturer(delete_url, edit_url) {
	this.update = function(id) {
		if (typeof id != undefined) {
			var form = 'manufacturerEdit' + id;
			form = document.getElementById(form);
			
			var manufacturer = form.name.value;
			var logo = form.manufacturerPhoto.files[0];
			var description = form.description.value;

			if (manufacturer.length > 0) {
				if (manufacturer.length <= 50) {
					
					if ((typeof manufacturer != undefined) && (typeof logo != undefined) && (typeof description != undefined)) {

					    var xhr = getXmlHttp();

						xhr.onload = xhr.onerror = function() {

					        if (this.readyState == 4 && this.status == 200) {
					           	var answer = xhr.responseText;
					            //answerController(answer, id);
					            answerJson(JSON.parse(answer), id);
					        } else {
								error('Ошибка соединения с сервером');
					        }
						      
				        }
					       		
					    var formData = new FormData();
					    formData.append('manufacturer', manufacturer);
					    formData.append('logo', logo);
					    formData.append('description', description);
					    formData.append('id', id);

					   	xhr.open("POST", edit_url, true);
					   	xhr.send(formData);

					   } else {
					   		error('Некоторые поля не заполены');
					   }
								
				} else {
					error('Слишком длинное название производителя.');
				}
			} else {
					error('Введите название производителя.');
			}
		} else {
			error('Ошибка, перезагрузите страницу и попробуйте ещё раз!');
		}
	}

	this.edit = function(id) {

		if (typeof id != undefined) {

			var tr_id = 'manufacturer_' + id;
			var updateForm = getById(tr_id);
			//var updateFormInner = '<form><td>' + updateForm.childNodes[0].innerHTML + '</td><td><input type="text" name="name" placeholder="' + updateForm.childNodes[1].innerHTML + '" class="text-input text-input-main" autofocus required></td><td>' + /*updateForm.childNodes[2].innerHTML +*/ '<input id="manufacturerPhoto" type="file" name="manufacturerPhotos" value="" required></td><td><textarea name="description" required form="manufacturerAdd" maxlength="1000" class="text-input text-input-main" rows="1" style="height: auto;" wrap="hard" placeholder="' + updateForm.childNodes[3].innerHTML + '">' + updateForm.childNodes[3].innerHTML + '</textarea></td><td>' + updateForm.childNodes[4].innerHTML + '</td><td>' + 'Готово' + '</td></form>';
			var updateFormInner = '<form id="manufacturerEdit' + id + '" name="manufacturerEdit' + id + '"><div class="table-id">' + updateForm.childNodes[0].innerHTML + '</div><div class="table-name"><input type="text" name="name" value="' + updateForm.childNodes[1].innerHTML + '" class="text-input text-input-main" autofocus required></div><div class="table-logo">' + /*updateForm.childNodes[2].innerHTML +*/ '<input id="manufacturerPhoto" type="file" name="manufacturerPhoto" value="" required></div><div class="table-text"><textarea name="description" required form="manufacturerEdit'+id+'" maxlength="1000" class="text-input text-input-main" rows="10" style="height: auto;" wrap="hard" placeholder="' + updateForm.childNodes[3].innerHTML + '" required>' + updateForm.childNodes[3].innerHTML + '</textarea></div><div class="table-date">' + updateForm.childNodes[4].innerHTML + '</div><div class="table-action"><div class="control-form" onclick="manufacturer.update(' + id + ')">' + 'Готово' + '</div></div></form>';
			updateForm.innerHTML = updateFormInner;
		} else {
			error('Ошибка, перезагрузите страницу и попробуйте ещё раз!');
		}

	}

	this.delete = function(id) {

		if (typeof id != undefined) {

		    var xhr = getXmlHttp();

			xhr.onload = xhr.onerror = function() {

		        if (this.readyState == 4 && this.status == 200) {
		           	var answer = xhr.responseText;
		            answerController(answer, id);
		        } else {
					error('Ошибка соединения с сервером');
		        }
			      
	        }
		       		
		    var formData = new FormData();
		    formData.append('id', id);

		   	xhr.open("POST", delete_url, true);
		   	xhr.send(formData);

		} else {
			error('Ошибка, перезагрузите страницу и попробуйте ещё раз!');
		}
	}

	function notyfi(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button success">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function error(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button error">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function answerController(message, id) {
		if (message == 'success') {
			id = 'manufacturer_' + id;
			getById(id).parentNode.removeChild(getById(id));
			notyfi('Успешно!');
		} else if (message == 'error') {
			error('Ошибка, попробуйте ещё раз!')
		} else if (message == 'alreadyDeleted') {
			error ('Данный производитель уже удалён!');
		} else {
			error(message);
		}
	}

	function answerJson(answer, id) {
		if (answer.answer == 'good') {
			id = 'manufacturer_' + id;
			var toInsert = '<div class="table-id">' + answer.id + '</div><div class="table-name">' + answer.name + '</div><div class="table-logo">';
			if (answer.logo != '') {
				toInsert += '<img style="width: 20px;" src="/' + answer.logo + '">';
			} else {
				toInsert += "Логотип не загружен";
			}
			toInsert += '</div><div class="table-text">' + answer.description + '</div><div class="table-date">' + answer.datetime + '</div><div class="table-action"><div onclick="manufacturer.edit(' + answer.id + ');" class="control-form">Редактировать</div><div onclick="manufacturer.delete(' + answer.id + ');" class="control-form">Удалить</div></div></div>';
			getById(id).innerHTML = toInsert;
			notyfi('Успешно!');
		} else if (answer.answer == 'error') {
			error('Ошибка!');
		} else if (answer.answer == 'format') {
			error('Недопустимый формат изображений!');
		} else if (answer.answer == 'none') {
			error('Данный производитель не найден!');
		} else if (answer.answer == 'empty') {
			error('Некоторые поля не заполены!')
		} else {
			error('Ошибка!');
		}
	}
}

function CatAdd (form, url) {

	form.onsubmit = function() {
		var category = form.name.value;
		var logo = form.categoryPhoto.files[0];
		var description = form.description.value

		if (category.length > 0) {
			if (category.length <= 50) {
				
				categoryAdder(category, logo, description);
							
			} else {
				error('Слишком длинное название производителя.');
			}
		} else {
				error('Введите название производителя.');
		}

		return false;
	}

	function error(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button error">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function success(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button success">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function answerController(message) {
		if (message.answer == 'good') {
			var toInsert = '<div class="table-id">' + message.id + '<div class="new">NEW</div></div><div class="table-name">' + message.name + '</div><div class="table-logo">';
			if (message.logo != '') {
				toInsert += '<img style="width: 20px;" src="/' + message.logo + '">';
			} else {
				toInsert += "Логотип не загружен";
			}
			toInsert += '</div><div class="table-text">' + message.description + '</div><div class="table-date">' + message.datetime + '</div><div class="table-action"><div onclick="manufacturer.edit(' + message.id + ');" class="control-form">Редактировать</div><div onclick="manufacturer.delete(' + message.id + ');" class="control-form">Удалить</div></div></div>';
			var div = document.createElement('div');
			div.innerHTML = toInsert;
			div.classList = 'table-row';
			div.id = 'category_' + message.id;
			getById('categories-table').insertBefore(div, getById('categories-table').children[1]);

			success('Успешно!');
		} else if (message.answer == 'empty') {
			error('Некоторые поля не заполены!');
		} else if (message.answer == 'repeat') {
			error('Данная категория уже существует!');
		} else if (message.answer == 'format') {
			error('Допустимы только следующие форматы изображения: JPG, PNG или GIF!')
		} else if (message.answer == 'error') {
			error('Неизвестная ошибка, попробуйте ещё раз!');
		}
	}

	function categoryAdder (category, logo, description) {

			if ((typeof category != undefined) && (typeof logo != undefined) && (typeof description != undefined)) {

		    var xhr = getXmlHttp();

			xhr.onload = xhr.onerror = function() {

		        if (this.readyState == 4 && this.status == 200) {
		           	var answer = xhr.responseText;
		            answerController(JSON.parse(answer));
		        } else {
					error('Ошибка соединения с сервером');
		        }
			      
	        }
		       		
		    var formData = new FormData();
		    formData.append('category', category);
		    formData.append('logo', logo);
		    formData.append('description', description);

		   	xhr.open("POST", url, true);
		   	xhr.send(formData);

		} else {
			error('Некоторые поля не заполены');
		}
	}
}

function Category(delete_url, edit_url) {
	this.update = function(id) {
		if (typeof id != undefined) {
			var form = 'categoryEdit' + id;
			form = document.getElementById(form);
			
			var category = form.name.value;
			var logo = form.categoryPhoto.files[0];
			var description = form.description.value;
			let order = Number(form.itemsorder.value);

			if (category.length > 0) {
				if (category.length <= 50) {
					
					if ((typeof category != undefined) && (typeof logo != undefined) && (typeof description != undefined) && (typeof order == 'number')) {

					    var xhr = getXmlHttp();

						xhr.onload = xhr.onerror = function() {

					        if (this.readyState == 4 && this.status == 200) {
					           	var answer = xhr.responseText;
					            //answerController(answer, id);
					            //alert(answer);
					            answerJson(JSON.parse(answer), id);
					        } else {
								error('Ошибка соединения с сервером');
					        }
						      
				        }
					       		
					    var formData = new FormData();
					    formData.append('category', category);
					    formData.append('logo', logo);
					    formData.append('description', description);
					    formData.append('id', id);
					    formData.append('itemsorder', order);

					   	xhr.open("POST", edit_url, true);
					   	xhr.send(formData);

					   } else {
					   		error('Некоторые поля не заполены или заполены неверно');
					   }
								
				} else {
					error('Слишком длинное название производителя.');
				}
			} else {
					error('Введите название производителя.');
			}
		} else {
			error('Ошибка, перезагрузите страницу и попробуйте ещё раз!');
		}
	}

	this.edit = function(id) {

		if (typeof id != undefined) {

			var tr_id = 'category_' + id;
			var updateForm = getById(tr_id);
			//var updateFormInner = '<form><td>' + updateForm.childNodes[0].innerHTML + '</td><td><input type="text" name="name" placeholder="' + updateForm.childNodes[1].innerHTML + '" class="text-input text-input-main" autofocus required></td><td>' + /*updateForm.childNodes[2].innerHTML +*/ '<input id="manufacturerPhoto" type="file" name="manufacturerPhotos" value="" required></td><td><textarea name="description" required form="manufacturerAdd" maxlength="1000" class="text-input text-input-main" rows="1" style="height: auto;" wrap="hard" placeholder="' + updateForm.childNodes[3].innerHTML + '">' + updateForm.childNodes[3].innerHTML + '</textarea></td><td>' + updateForm.childNodes[4].innerHTML + '</td><td>' + 'Готово' + '</td></form>';
			var updateFormInner = '<form id="categoryEdit' + id + '" name="categoryEdit' + id + '"><div class="table-id">' + updateForm.childNodes[0].innerHTML + '</div><div class="table-name"><input type="text" name="name" value="' + updateForm.childNodes[1].innerHTML + '" class="text-input text-input-main" autofocus required></div><div class="table-logo">' + /*updateForm.childNodes[2].innerHTML +*/ '<input id="categoryPhoto" type="file" name="categorycturerPhoto" value="" required></div><div class="table-text"><textarea name="description" required form="categoryEdit'+id+'" maxlength="1000" class="text-input text-input-main" rows="10" style="height: auto;" wrap="hard" placeholder="' + updateForm.childNodes[3].innerHTML + '" required>' + updateForm.childNodes[3].innerHTML + '</textarea></div><div class="table-int"><input type="text" name="itemsorder" value="' + updateForm.childNodes[4].innerHTML + '" class="text-input text-input-main" autofocus required></div><div class="table-date">' + updateForm.childNodes[5].innerHTML + '</div><div class="table-action"><div class="control-form" onclick="category.update(' + id + ')">' + 'Готово' + '</div></div></form>';
			updateForm.innerHTML = updateFormInner;
		} else {
			error('Ошибка, перезагрузите страницу и попробуйте ещё раз!');
		}

	}

	this.delete = function(id) {

		var conf = confirm("Вы точно хотите удалить данную категорию?");

		if (!conf) return;

		if (typeof id != undefined) {

		    var xhr = getXmlHttp();

			xhr.onload = xhr.onerror = function() {

		        if (this.readyState == 4 && this.status == 200) {
		           	var answer = xhr.responseText;
		            answerController(answer, id);
		        } else {
					error('Ошибка соединения с сервером');
		        }
			      
	        }
		       		
		    var formData = new FormData();
		    formData.append('id', id);

		   	xhr.open("POST", delete_url, true);
		   	xhr.send(formData);

		} else {
			error('Ошибка, перезагрузите страницу и попробуйте ещё раз!');
		}
	}

}

	function notyfi(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button success">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function error(message) {
		var div = document.createElement('div');
	    div.className = 'turn-account-button turn-account-button-left';
	    div.innerHTML = '<a class="button error">'+message+'</a>';
	    div.id = 'notificator';

	    document.body.appendChild(div);

	    setTimeout(hideMessage, 4000);
	}

	function answerController(message, id) {
		if (message == 'success') {
			id = 'category_' + id;
			getById(id).parentNode.removeChild(getById(id));
			notyfi('Успешно!');
		} else if (message == 'error') {
			error('Ошибка, попробуйте ещё раз!')
		} else if (message == 'alreadyDeleted') {
			error ('Данная категория уже удалена!');
		} else {
			error(message);
		}
	}

	function answerJson(answer, id) {
		if (answer.answer == 'good') {
			/*id = 'category_' + id;
			var toInsert = '<div class="table-id">' + answer.id + '</div><div class="table-name">' + answer.name + '</div><div class="table-logo">';
			if (answer.logo != '') {
				toInsert += '<img style="width: 20px;" src="/' + answer.logo + '">';
			} else {
				toInsert += "Картинка не загружена";
			}
			toInsert += '</div><div class="table-text">' + answer.description + '</div><div class="table-date">' + answer.datetime + '</div><div class="table-action"><div onclick="category.edit(' + answer.id + ');" class="control-form">Редактировать</div><div onclick="category.delete(' + answer.id + ');" class="control-form">Удалить</div></div></div>';
			getById(id).innerHTML = toInsert;*/
			window.location.reload();
			notyfi('Успешно!');
		} else if (answer.answer == 'error') {
			error('Ошибка!');
		} else if (answer.answer == 'format') {
			error('Недопустимый формат изображений!');
		} else if (answer.answer == 'none') {
			error('Данная категория не найдеа!');
		} else if (answer.answer == 'empty') {
			error('Некоторые поля не заполены!')
		} else {
			error('Ошибка!');
		}
	}
