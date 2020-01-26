<?php
//echo $this->status;
if (isset($this->status)) {
    //echo "<script>alert(".$this->addProductStatus[2].")</script>";
    $answer = json_decode($this->status);
    //echo $answer->id;
    if ($answer->status == 'completed') {
        echo '<script>notify("Успешно", "success");</script>';
    } elseif ($answer->status == 'empty') {
        //echo '<script>notify("Ошибка", "error");</script>';
    } elseif ($answer->status == 'error') {
        echo '<script>notify("Ошибка", "error");</script>';
    } else {
        echo '<script>notify(\'Ошибка: '.$this->status.'\', "error");</script>';
    }
}
?>
<script>
    function handleFileSelect(evt, id, inp_id, clean = false) {

        var files = evt.target.files; // FileList object
        if (clean) document.getElementById(id).innerHTML = '';
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
                    var span = document.createElement('div');
                    span.innerHTML = ['<img style="height: 100%;" class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('');
                    span.classList.add("preview-photo");
                    document.getElementById(id).insertBefore(span, null);
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);

            //ТОЛЬКО В ДАННОМ СЛУЧАЕ!
            if (i > 30) break;

        }

        document.getElementById(inp_id).previousSibling.innerHTML = 'Выбрано файлов: ' + i;
      }
</script>
<h2>Менеджер изображений</h2>
                <h3>Загрузите новые изображения</h3>
                <form action="<?php echo URL?>admin/imagesManager" method="post" name="imagesLoader"  enctype="multipart/form-data">
                    <div class="block-group">
                        <div class="form-group">
                            <output id="list"></output>
                            
                            <div class="photoInput">
                                <br>Загрузите изображение (нажмите на поле или просто перетащите в него)<br><br>
                                <label class="uploadbutton" style="cursor: pointer;"><div class="button">Выбрать</div><div class="input">Выберите фото</div><input id="photos" type="file" multiple onchange="this.previousSibling.innerHTML = this.value;addMoreInput(this);" name="siteImages[]" accept="image/jpeg,image/png"></label><br>
                            </div>
                        </div>
                    </div>
                    <div class="block-group" style="display: block;">
                        <div class="form-group">
                            <h4 class="element-descr">Небольшое описание</h4>
                            <textarea value="" class="input-field" style="width: 100%;" name="images-description" placeholder="Необязательно, применится ко всем изображениям" type="text"></textarea>
                        </div>
                        <div class="form-group">
                            <input value="Добавить" class="btn btn-primary" type="submit">
                        </div>
                    </div>
                </form>
                <script>
                    document.getElementById('photos').addEventListener('change', function(evt){handleFileSelect(evt,'list','photos', true)}, false);
                    //}
                </script>
        <script>
            function changeResult(answer) {
                //alert(answer);
                answer = JSON.parse(answer);
                if (answer.status == 'completed') {
                    notify("Успешно!", 'success');
                    var imageId = "image_" + answer.id;
                    document.getElementById(imageId).style.display = 'none';
                    return false;
                } else {
                    notify("Ошибка!", 'error');
                    return false;
                }
                notify("Ошибка!", 'error');
            }
            function imageController(id, action) {
                var dataToSend = {};
                dataToSend['id'] = id;
                dataToSend['action'] = action;
                var formId = 'changeImgDescr_' + id;
                if (action == 'updateDescr')
                    dataToSend['comment'] = document.getElementById(formId).imagesDescription.value;
                sendData(JSON.stringify(dataToSend), url + 'admin/imageController', changeResult);
            }
        </script>
        <h3>Загруженные изображения</h3>
        <div class="images-cont" id="imagesCont">
        
        <?php
        for ($i = 0; $i < count($this->images); $i++) {
            ?>
            <div class="image-cont" id="image_<?=$this->images[$i]['id']?>">
                <a title="Открыть" href="<?=$this->images[$i]['url']?>"><div class="image" style="background: url(<?=$this->images[$i]['url']?>);"></div></a>
                <br>
                <div class="img-url-block">
                    Ссылки на изображение: <br>
                    <ul>
                        <li><b>Для вставки на этот сайт:</b> <?=$this->images[$i]['url']?></li>
                        <li><b>Для вставки на сторонние ресурсы:</b> <?=URL_NO_SLASHES.$this->images[$i]['url']?></li>
                    </ul>
                    
                </div>
                <form action="<?php echo URL?>admin/changeImgDescr" method="post" name="changeImgDescr_<?=$this->images[$i]['id']?>"  enctype="multipart/form-data" id="changeImgDescr_<?=$this->images[$i]['id']?>">
                    <b class="element-descr">Описание</b>
                    <textarea class="form-control" name="imagesDescription" placeholder="Необязательно" type="text"><?=$this->images[$i]['description']?></textarea>
                </form>
                <div class="control-panel">
                    <div class="btn btn-primary" onclick="imageController(<?=$this->images[$i]['id']?>, 'updateDescr'); return false;">Изменить</div>
                    <btn class="btn btn-danger" onclick="imageController(<?=$this->images[$i]['id']?>, 'delete'); return false;">Удалить</btn>
                </div>
            </div>
            <?php
            //echo $this->images[$i]['url'];

        }
        $i = 0;
        ?>
        </div>