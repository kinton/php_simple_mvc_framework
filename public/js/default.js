function getById (id) {
	return document.getElementById(id);
}
function offsetPosition(element) {
   	var offsetLeft = 0, offsetTop = 0;
    do {
        offsetLeft += element.offsetLeft;
        offsetTop  += element.offsetTop;
   	} while (element = element.offsetParent);
	return [offsetLeft, offsetTop];
}
function ChangeColorOnScroll(element, colors, covers) {
	var elem = getById(element);
	currentColor = elem.style.background;

	this.change = function() {
		var scrolled = window.pageYOffset || document.documentElement.scrollTop;
		for (var i = covers.length - 1; i >= 0; i--) {
			if (scrolled > offsetPosition(getById(covers[i]))[1]) {
				elem.style.background = colors[i];
				break;
			} else {
				elem.style.background = currentColor;
			}
		}
	}
}
function getXmlHttp() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}
function RollNavOnScroll(navHeight) {
    var save = 0;
    var current = 0;
    window.onscroll = function() {
        navHeight = document.getElementById('header').offsetHeight - document.getElementById('header').getElementsByClassName('nav-menu')[0].offsetHeight;
        var scrolled = window.pageYOffset || document.documentElement.scrollTop;
        if (save - scrolled < 0) {
            if (current > -navHeight) {
                if (save - scrolled + current > -navHeight) {current = save - scrolled + current;} else {current = -navHeight;}
                getById('header').style.top = current + 'px';
            }
        } else {
            if (current < 0) {
                if (save - scrolled + current < 0) {current = save - scrolled + current;} else {current = 0;}
                getById('header').style.top = current + 'px';
            }
        }
        save = scrolled;
    }
}
function showWindow(callerId, windowId, offcloser, textToIN) {
    if (typeof offcloser == 'undefined') var offcloser = false;
    if (typeof textToIN == 'undefined') var textToIN = '';
    //offcloser - если false, то можно закрыть окно по нажатию на caller
    if (offcloser) {
        if (getById(windowId).classList.contains('hidden')) {
            document.getElementById(windowId).classList.toggle('hidden');
            setTimeout(function() {new WindowHider(callerId, windowId);}, 100);
        }
    } else {
        if (getById(windowId).classList.contains('hidden')) {
            document.getElementById(windowId).classList.toggle('hidden');
            setTimeout(function() {new WindowHider(callerId, windowId);}, 100);
        } else {
            document.getElementById(windowId).classList.toggle('hidden');
        }
    }
    if (textToIN != '') {
        getById('starterPlace').innerHTML = textToIN;
    }
}

function WindowHider(cid, wid) {
    document.addEventListener('click', captureForWindow); 
    function captureForWindow(e) {
        e = e || event;
        var checker = 0;
        var a = e.target;
        while (a != null) {
            if (a.id == cid) {
                checker = 1;
                break;
            }
            a = a.parentNode;
        }
        if (checker != 1) {
            a = e.target;
            while (a != null) {
                if (a.id == wid) {
                    checker = 1;
                    break;
                }
                a = a.parentNode;
            }
            if (checker == 0) {
                if (!getById(wid).classList.contains('hidden')) {
                    getById(wid).classList.toggle('hidden');
                    document.removeEventListener('click', captureForWindow);
                }
            }
        }
    }
}

function InputShell(input, form) {
    var focus = 0;
    input.parentNode.onmouseover = function() {
        if (focus == 0) input.parentNode.style.boxShadow = 'inset 0 0 2px grey';
    }
    input.parentNode.onmouseout = function() {
        if (focus == 0) input.parentNode.style.boxShadow = 'none';
    }
    input.onfocus = function() {
        input.parentNode.style.boxShadow = 'inset 0 0 2px #4594EE';
        focus = 1;
    }
    input.onblur = function() {
        input.parentNode.style.boxShadow = 'none';
        focus = 0;
    }
    input.parentNode.onclick = function() {
        input.focus();
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

function GetDataOnKeyUp(input, url, controller) {
    input.onkeyup = function() {
        var xhr = getXmlHttp();

        xhr.onload = xhr.onerror = function() {

            if (this.readyState == 4 && this.status == 200) {
                var answer = xhr.responseText;
                controller(answer);
            } else {
                error('Ошибка соединения с сервером');
            }
                      
        }
                        
        var formData = new FormData();
        formData.append('inputText', input.value);

        xhr.open("POST", url, true);
        xhr.send(formData);
    }
}

function getData(data, url, controller) {
    var xhr = getXmlHttp();

    xhr.onload = xhr.onerror = function() {

        if (this.readyState == 4 && this.status == 200) {
            var answer = xhr.responseText;
            controller(answer);
        } else {
            error('Ошибка соединения с сервером');
        }
                      
    }
                        
    var formData = new FormData();
    formData.append('input', data);

    xhr.open("POST", url, true);
    xhr.send(formData);
}

function sendData(data, url, controller) {
    var xhr = getXmlHttp();

    xhr.onload = xhr.onerror = function() {

        if (this.readyState == 4 && this.status == 200) {
            var answer = xhr.responseText;
            controller(answer);
        } else {
            error('Ошибка соединения с сервером');
        }
                      
    }

    var formData = new FormData();
    formData.append('data', data);

    xhr.open("POST", url, true);
    xhr.send(formData);
}

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

function deliverInInput(input, blockId) {
    input.value = getById(blockId).innerHTML;
}

function notify(message, type, time) {
    if (typeof type == 'undefined') var type = 'usual';
    if (typeof time == 'undefined') var time = 4000;
    var div = document.createElement('div');
    div.className = 'turn-account-button turn-account-button-left';
    if (type == 'success') {
        div.innerHTML = '<div class="button success div-button" style="height: auto !important;">'+message+'</a>';
    } else if (type == 'error') {
        div.innerHTML = '<div class="button error div-button" style="height: auto !important;">'+message+'</a>';
    } else {
        div.innerHTML = '<div class="button usual div-button" style="height: auto !important;">'+message+'</a>';
    }

    div.id = 'notificator';

    document.body.appendChild(div);

    setTimeout(hideMessage, time);
}

function hideMessage() {
    var id = 'notificator';
    getById(id).parentNode.removeChild(getById(id));
}

var cart = {
    add: function(id) {
        //getData
    }
}

var productUI = {

    show: function(productId, shiftPercents) {
        productId = 'movingCarousel_' + productId;
        var right = 100 - shiftPercents + '%';
        getById(productId).style.right = right;
    },

    showInActive: function(productId, shiftPercents) {
        productId = 'movingCarousel_' + productId;
        if (getById(productId).style.right != '100%') {
            var right = 100 - shiftPercents + '%';
            getById(productId).style.right = right;
        }
    },

    hide: function(productId) {
        productId = 'movingCarousel_' + productId;
        if (getById(productId).style.right != '0%') {
            getById(productId).style.right = '100%';
        }
    }

}

function getScrollLineWidth() {
    var div = document.createElement('div');

    div.style.overflowY = 'scroll';
    div.style.width = '50px';
    div.style.height = '50px';

    // при display:none размеры нельзя узнать
    // нужно, чтобы элемент был видим,
    // visibility:hidden - можно, т.к. сохраняет геометрию
    div.style.visibility = 'hidden';

    document.body.appendChild(div);
    var scrollWidth = div.offsetWidth - div.clientWidth;
    document.body.removeChild(div);

    //alert( scrollWidth );
    return scrollWidth;
}

var modalWindow = {

    set: function(valueUrl, change, changeClose) {
        if (typeof valueUrl == 'undefined') var valueUrl = '';
        if (typeof change == 'undefined') if (valueUrl == '') var change = 'notchange'; else var change = 'change';
        if (typeof changeClose == 'undefined') var changeClose = 'change';
        var fone = getById('modalFone');

        if (!fone) {

            var fone = document.createElement('div');
            fone.id = 'modalFone';
            fone.className = 'modal-fone';
            fone.onclick = function(event) {
                if (event.target.id == 'modalFone' || event.target.id == 'close_blockscreen') {
                    if (changeClose == 'change') {
                        modalWindow.unset();
                    } else {
                        modalWindow.unset('notchange');
                    }
                }
            }

            if (change == 'change') {
                var url = addUrlLevel(valueUrl);
                if (url != window.location.href) {
                    window.history.pushState(null, null, url);
                }
            }

            document.body.appendChild(fone);

            var cont = document.createElement('div');
            cont.id = 'modalCont';
            cont.className = 'modal-cont';

            fone.appendChild(cont);

            document.body.style.overflow = 'hidden';

            let slw = getScrollLineWidth();
            slw += 'px';
            document.body.style.paddingRight = slw;
            document.getElementById('siteNavbarMenu').style.paddingRight = slw;
            document.getElementById('right-header').style.paddingRight = slw;
            if (document.getElementById('phonePulseB') !== null) document.getElementById('phonePulseB').style.paddingRight = slw;
        } else {
            modalWindow.unset();
            modalWindow.set();
        }
    },
    unset: function(change) {
        if (typeof change == 'undefined') var change = 'change';
        if (change == 'change') {
            url = removeUrlLevel();
            if(url != window.location.href){
                window.history.pushState(null, null, url);
            }
        }

        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '';
        document.getElementById('siteNavbarMenu').style.paddingRight = '';
        document.getElementById('right-header').style.paddingRight = '';
        if (document.getElementById('phonePulseB') !== null)  document.getElementById('phonePulseB').style.paddingRight = '';
        getById('modalFone').parentNode.removeChild(getById('modalFone'));
    },
    content: function(answer) {
        getById('modalCont').innerHTML = answer;
    },
    imageSet: function() {
        var fone = getById('modalFone');
        if (!fone) {
            modalWindow.set('', 'notchange', 'notchange');
        } else {
            var imgfone = document.createElement('div');
            imgfone.id = 'imgModalFone';
            imgfone.className = 'img-modal-fone';
            imgfone.onclick = function(event) {
                if (event.target.id == 'imgModalFone' || event.target.id == 'close_blockscreen') {
                    modalWindow.imageUnset();
                }
            }

            document.body.appendChild(imgfone);

            var imgcont = document.createElement('div');
            imgcont.id = 'imgModalCont';
            imgcont.className = 'modal-cont';

            imgfone.appendChild(imgcont);

            document.body.style.overflow = 'hidden';
            fone.style.overflow = 'hidden';
        }
    },
    imageUnset: function() {
        var fone = getById('modalFone');
        var modalFone = getById('imgModalFone');
        if (modalFone) {
            if (fone) {
                fone.style.overflow = 'auto';
            }
            getById('imgModalFone').parentNode.removeChild(getById('imgModalFone'));
        } else {
            getById('modalFone').parentNode.removeChild(getById('modalFone'));
            document.body.style.overflow = 'auto';
        }

        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = '';
        document.getElementById('siteNavbarMenu').style.paddingRight = '';
        document.getElementById('right-header').style.paddingRight = '';
        if (document.getElementById('phonePulseB') !== null)  document.getElementById('phonePulseB').style.paddingRight = '';
        //getById('modalFone').parentNode.removeChild(getById('modalFone'));
        //event.stopImmediatePropagation();
    },
    imageContent: function(answer) {
        var fone = getById('imgModalFone');
        if (!fone) {
            getById('modalCont').innerHTML = answer;
        } else {
            getById('imgModalCont').innerHTML = answer;
        }
    }

}

function addUrlLevel(val) {
    if (typeof val == 'undefined' || val == '') return location.href;
    var d = location.href.split("#")[0].split("?");  
    var base = d[0];
    if (base[base.length - 1] == '/') {
        return base + val;
    } else {
        return base + '/' + val;
    }
}

function removeUrlLevel() {
    var d = location.href.split("#")[0].split("?");  
    var base = d[0];
    var res = '';
    base = base.split("/");
    for (var i = 0; i < base.length - 1; i++) {
        res += base[i] + '/';
    }
    return res.substr(0, res.length - 1);
}

//ИЗМЕНЕНИЕ АТРИБУТОВ В ССЫЛКЕ
function setAttr(prmName,val){
    var res = '';
    var d = location.href.split("#")[0].split("?");  
    var base = d[0];
    var query = d[1];
    if(query) {
        var params = query.split("&");  
        for(var i = 0; i < params.length; i++) {  
            var keyval = params[i].split("=");  
            if(keyval[0] != prmName) {  
                res += params[i] + '&';
            }
        }
    }
    res += prmName + '=' + val;
    //window.location.href = base + '?' + res;
    //return false;
    return base + '?' + res;
}


//удалить атрибут
function delAttr(prmName){
    var res = '';
    var d = location.href.split("#")[0].split("?"); 
    var base = d[0];
    var query = d[1];
    if(query) {
        var params = query.split("&"); 
        for(var i = 0; i < params.length; i++) { 
            var keyval = params[i].split("="); 
            if(keyval[0] !== prmName) { 
                if (i > 0) {
                    res += '&'+params[i];
                } else {
                    res += params[i];
                }
            }
        }
    }
    if (res != '') {
        return base + '?' + res;
    } else {
        return base;
    }
}

function delAttr_mod (link, prmName) {
    var res = '';
    var d = link; 
    var base = d[0];
    var query = d[1];
    if(query) {
        var params = query.split("&"); 
        for(var i = 0; i < params.length; i++) { 
            var keyval = params[i].split("="); 
            if(keyval[0] !== prmName) { 
                if (i > 0) {
                    res += '&'+params[i];
                } else {
                    res += params[i];
                }
            }
        }
    }
    if (res != '') {
        return base + '?' + res;
    } else {
        return base;
    }
}

function imageZoom(img) {
    modalWindow.imageSet();
    var answer = '<div class="modal-inner"><div class="row" style="margin-bottom: 0;"><div class="col-2" style="float: right; text-align: right; padding: 5px 0 0 0;" onclick="modalWindow.imageUnset();"><a id="closeZoom" onclick=modalWindow.imageUnset();" class="button primary">' + 'Закрыть' + '</a></div><div class="col-8"><!--Ссылка на файл: <input style="padding-bottom: 0; margin-bottom: 0;" class="text-input text-input-main short-input" value="' + img.src +'" id="imgAddress">--></div></div></div>';
    answer += '<img class="product-image-view" src="' + img.src + '">';
    modalWindow.imageContent(answer);
    //var imgAddress = getById('imgAddress');
    //если нужно скопировать адресс
    //imgAddress.focus();
    //imgAddress.select();
}

function deliverInBigPhoto(img, bigPhotoId) {
    getById(bigPhotoId).src = img.src;
}

var mainSearcher = function(answer) {
    var answ = JSON.parse(answer);
    answer = '';

    ans = answ.products;
    if (ans.i > 0) {
        answer += 'Продукты<br>';
        if (typeof pageType !== 'undefined') {
            if (pageType == 'admin') {
                for (var i = 0; i < ans.i; i++) {
                    answer += '<a class="no-effect-a black-url menu-url-style" href="'+url+'admin/products/'+ans.id[i]+'"><div class="search-block"><div style="background: url(\'/'+ans.photos[i][0]+'\') center center / cover;" class="search_logos"></div><div style="margin: 15.5px 0 0 10px; display: inline-block;">'+ans.name[i]+'</div></div></a>';
                }
            }
        } else {
            for (var i = 0; i < ans.i; i++) {
                answer += '<a class="no-effect-a black-url menu-url-style" href="'+url+'catalog/products/'+ans.id[i]+'"><div class="search-block"><div style="background: url(\'/'+ans.photos[i][0]+'\') center center / cover;" class="search_logos"></div><div style="margin: 15.5px 0 0 10px; display: inline-block;">'+ans.name[i]+'</div></div></a>';
            }
        }
        answer += '<br><br>';
    }

    /*ans = answ.manufacturers;
    if (ans.i > 0) {
        answer += 'Производители<br>';
        for (var i = 0; i < ans.i; i++) {
            answer += '<div class="search-block"><div style="background: url('+ans.logo[i]+') center center / cover;" class="search_logos"></div>'+ans.name[i]+' '+ans.id[i]+'</div>';
        }
        answer += '<br><br>';
    }*/

    ans = answ.categories;
    if (ans.i > 0) {
        answer += 'Категории<br>';
        for (var i = 0; i < ans.i; i++) {
            answer += '<div class="search-block"><div style="background: url(\'/'+ans.logo[i]+'\') center center / cover;" class="search_logos"></div>'+ans.name[i]+'</div>';
        }
        answer += '<br><br>';
    }

    if (answer == '') {
        answer = 'Ничего не найдено, попробуйте ввести другой запрос!';
    }
    getById('answerPlaceSearch').innerHTML = answer;
    getById('productSearcherWindow').style.maxHeight = document.documentElement.clientHeight - 100 + 'px';
}

/*
function product() {
    this.getDataOfProduct = function(answer) {
        //getById('modalCont').innerHTML = answer;
        ans = JSON.parse(answer);
        var photos = ans.photos;
        answer = '';
        answer += '<div class="modal-inner">'
        answer += '<div class="row"><div class="col-2" style="float: right; text-align: right;" onclick="modalWindow.unset();"><a class="button primary">' + 'Закрыть' + '</a></div><div class="col-10"><div class="circle small-circle"><img src="' + ans['manufacturerLogo'] + '"></div><h2 style="margin:0; margin: 14px 0 0 10px; float: left;">' + ans['manufacturerName'] + ' - ' + ans.name + '</h2></div></div>';
        answer += '<div class="row"><div class="variant-buble buble">'+ans.categoryName+'</div></div>';

        answer += '<div class="row">';
        for (var i = 0; i < ans.photos.length; i++) {
            answer += '<img class="product-image" src="' + ans.photos[i] + '">';
        }
        answer += '</div>';

        answer += '<div class="row"><div>' + ans.description + '</div></div>';

        answer += '<div class="row"><div><h3>Состав и информация:</h3> ' + ans.composition + '</div></div>';

        answer += '<div class="row"><div><h3>Стоимость</h3> ' + ans.price + ' р.</div></div>';

        if (typeof ans.creatorName !== 'undefined') {
            answer += '<div class="row"><div><h3>Создал</h3> ' + ans.creatorName + ' ' + ans.creatorSurname + '</div></div>';
        }

        answer += '<div class="row"><div><h3>Изменено</h3> ' + ans.datetime + '</div></div>';

        answer += '</div>';

        modalWindow.content(answer);
        
    }
}*/

function showPhones(phones) {
    if (typeof phones == 'undefined') var phones = [];
    modalWindow.set();
    var answer = '<div class="modal-inner"><div class="row" style="margin-bottom: 0;"><div class="col-4" style="float: right; text-align: right; padding: 5px 0 0 0;" onclick="modalWindow.imageUnset();"><a id="closeZoom" onclick=modalWindow.imageUnset();" class="button primary">' + 'Закрыть' + '</a></div><div class="col-8"></div></div>';
    answer += '<div class="big-center-header"><h3>Наши телефоны</h3><br><br>';
    for (var i = phones.length - 1; i >= 0; i--) {
        answer += '<a href="tel:' + phones[i] + '">' + phones[i] + '</a><br><br>';
    }
    //answer += '<a href="tel:+79999999999">8 999 999-99-99</a>';
    answer += '</div></div>';
    modalWindow.content(answer);
    document.getElementById('modalCont').style.width = '40%';
}


//модальные формы
var modalCounter = 0;
function clientAdderModal(form, url, controller) {
    form.onsubmit = function() {
        var name = form.name.value;
        var phone = form.phone.value;
        var email = form.email.value;
        var partner = form.partner.value;
        if (name.length == 0 || phone.length == 0) {
            var buttonContId = 'bidsSenderModal_' + modalCounter;
            document.getElementById(buttonContId).innerHTML = 'Заполните имя и телефон';
            document.getElementById(buttonContId).style.backgroundColor = "#f70";
            return false;
        }
        var data = {
            "name": name,
            "phone": phone,
            "email": email,
            "partner": partner
        }

        form.sendB.innerHTML = 'Обработка, ожидайте!';
        form.sendB.classList.add('yellow-background');

        sendData(JSON.stringify(data), url, controller);
        return false;
    }
    //getData([document.forms.clientAdd.name.value, document.forms.clientAdd.phone.value], '<?php echo URL;?>client/add', getAddStatus, ['name', 'phone'])
}
function addBidStatusModal(answer) {
    //alert(answer);
    var buttonContId = 'bidsSenderModal_' + modalCounter;
    var button = document.getElementById(buttonContId);
    answer = JSON.parse(answer);
    if (answer.status == 'completed') {
        button.innerHTML = 'Успешно! Ожидайте звонка.';
        button.classList.add('green-background');
        setTimeout(function() {
            button.classList.remove('green-background');
            button.innerHTML = 'Отправить новую заявку';
        }, 5000);
    } else if (answer.status == 'alreadyRegistered') {
        button.innerHTML = 'Заявка уже на рассмотрении!';
        button.classList.add('red-background');
        setTimeout(function() {
            button.classList.remove('red-background');
            button.innerHTML = 'Отправить новую заявку';
        }, 5000);
    } else {
        //empty или error
        button.innerHTML = 'Ошибка, попробуйте позже!';
        button.classList.add('red-background');
        setTimeout(function() {
            button.classList.remove('red-background');
            button.innerHTML = 'Отправить новую заявку';
        }, 5000);
    }
    button.classList.remove('yellow-background');
}
function formModalActivator() {
    modalCounter++;
    modalWindow.set();
    var inner = '';
    inner += '<div class="modal-inner">';
    inner += '<div class="col-2" style="float: right; text-align: right;" onclick="modalWindow.unset();"><a class="button error">' + 'Закрыть' + '</a></div>';
    inner += '<form id="bidsAcceptorModal_'+modalCounter+'" name="bidsAcceptorModal" class="bids-form text-center" action="" method="POST" data-toggle="validator" role="form" novalidate="true">';
    inner += document.getElementById('formContainterSafe').innerHTML.replace( /id="bidsSender"/g, "id=\"bidsSenderModal_"+ modalCounter +"\"" );
    inner += '</form>';
    inner += '</div>';
    modalWindow.content(inner);
    document.getElementById('modalCont').style.width = '40%';
    new clientAdderModal(document.getElementById('bidsAcceptorModal_' + modalCounter), url + 'dashboard/addBid', addBidStatusModal);
    return false;
}

function formSender(form, url, controller, moreData) {
    //moreData - функция, возвращающая массив или объект; массив; объект
    form.onsubmit = function() {
        if (typeof moreData == 'undefined')
            var addData = {};
        else
            if (typeof moreData == 'function') 
                var addData = moreData();
            else
                var addData = moreData;

        //alert(addData.article);

        if (typeof dataToSend == 'undefined') var dataToSend = {};

        var empt_ch = 0;
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].required && form.elements[i].value == '') {
                if (!empt_ch) var notify_text = '<b>Не заполнены некоторые обязательные поля:</b><br>';
                if (typeof form.elements[i].parentNode.getElementsByClassName('element-descr')[0] != 'undefined') {                   
                    notify_text += form.elements[i].parentNode.getElementsByClassName('element-descr')[0].innerHTML;
                } else {
                    notify_text += form.elements[i].name;
                }
                if (i < form.elements.length - 1) notify_text += '<br>';
                empt_ch = 1;
            }
        }
        if (empt_ch) {
            notify(notify_text, 'error');
        } else {
            for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type != 'submit') {
                    dataToSend[form.elements[i].name] = form.elements[i].value;
                }
            }

            if (typeof addData == 'object') {
                if (Object.prototype.toString.call(addData) == '[object Array]') {

                    for (var j = 0; j < addData.length; j++) {
                        dataToSend['addData_' + j] = addData[j];
                    }

                } else {

                    for (key in addData) {
                        dataToSend[key] = addData[key];
                    }
                }
            } else {
                dataToSend['addData'] = addData;
            }

            sendData(JSON.stringify(dataToSend), url, controller);
        }
        return false;
    }
}

function updateProductCard(cardId, price, weight) {
    let CIV = 'itemCard_' + cardId;
    CIV = document.getElementById(CIV);
    let priceHolder = CIV.getElementsByClassName('priceHolder')[0];
    let itemWeight = CIV.getElementsByClassName('item_weight')[0];
    let paramButtons = CIV.getElementsByClassName('param-button');
    let currButton = 'pbPr=' + price + 'AndId=' + cardId;
    currButton = document.getElementById(currButton);

    priceHolder.innerHTML = price + ' &#8381;';
    itemWeight.innerHTML = weight;
    itemWeight.style.display = 'block';
    for (var i = 0; i < paramButtons.length; i++) {
        paramButtons[i].classList.remove('param-button-active');
    }
    currButton.classList.add('param-button-active');
}


let onloadedFooterHeight;
window.onload = ()=>{
    //обработка высоты footer
    onloadedFooterHeight = document.getElementById('footer').offsetHeight;
    if (document.documentElement.clientHeight - document.getElementById('content').offsetHeight >= onloadedFooterHeight) document.getElementById('footer').style.height = document.documentElement.clientHeight - document.getElementById('content').offsetHeight + 'px'; else document.getElementById('footer').style.height = 'auto';
}
window.onresize = ()=>{
    //обработка высоты footer
    if (document.documentElement.clientHeight - document.getElementById('content').offsetHeight >= onloadedFooterHeight) document.getElementById('footer').style.height = document.documentElement.clientHeight - document.getElementById('content').offsetHeight + 'px'; else document.getElementById('footer').style.height = 'auto';
}