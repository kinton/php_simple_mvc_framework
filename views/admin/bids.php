          <script>
            function bidEditor(form, url, controller) {
              form.onsubmit = function() {
                  var status = form.status.value;
                  var id = form.id.value;
                  var name = form.name.value;
                  var phone = form.phone.value;
                  var email = form.email.value;
                  //var birthday = form.birthday.value;
                  //var partner = form.partner.value;
                  var note = form.note.value;

                  var data = {
                      "status": status,
                      "id": id,
                      "name": name,
                      "phone": phone,
                      "email": email,
                      //"birthday": birthday,
                      //"partner": partner,
                      "note": note
                  }
                  sendData(JSON.stringify(data), url, controller);
                  return false;
              }
          }

          function bidEditorStatus (answer) {
              answer = JSON.parse(answer);
              if (answer.status == 'completed') {
                  notify('Успешно!', 'success');
              } else if (answer.status == 'empty') {
                notify('Одно из полей пустое', 'error');
              } else if (answer.status == 'error_compl') {
                notify('Ошибка сравнения идентификатор', 'error');
              } else if (answer.status == 'error') {
                notify('Ошибка при добавлении данных в базу', 'error');
              } else {
                notify('Неизвестная ошибка', 'error');
              }
          }
          </script>

          <h2 class="sub-header">Заявки <?php //echo $this->a;?></h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Имя</th>
                  <th>Телефон</th>
                  <th>Почта</th>
                  <!-- <th>Дата рождения</th>
                  <th>Номер партнёра</th> -->
                  <th>Заметка</th>
                  <th>Статус</th>
                  <th>Дата добавления</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
              $statuses = explode(",", $this->statusVariants->fetch()['value']);
              while ($row = $this->bids->fetch()) {?>
                <tr>
                <form name="editBid_<?php echo $row['id'];?>" action="<?php echo URL;?>adminpanel/editBid/<?php echo $row['id'];?>" method="post" enctype="multipart/form-data">
                  <th><?php echo $row['id'];?></th>
                  <th><input class="input-field" name="name" placeholder="Полное имя" type="text" value="<?php echo $row['name'];?>"></th>
                  <th><input class="input-field" name="phone" placeholder="Телефон" type="text" value="<?php echo $row['phone'];?>"></th>
                  <th><input class="input-field" name="email" placeholder="Почта" type="email" value="<?php echo $row['email'];?>"></th>
                  <!-- <th><input class="input-field" name="birthday" placeholder="ГГГГ-ММ-ДД" type="date" value="<?php if ($row['birthday'] != 0) echo $row['birthday'];?>"></th>
                  <th><input class="input-field" name="partner" placeholder="Id партнёра" type="text" value="<?php echo $row['partner'];?>"></th> -->
                  <th><textarea class="input-field" name="note" placeholder="Заметка"><?php echo $row['note'];?></textarea></th>
                  <!--<th><?php echo $row['status'];?></th>-->
                  <th>
                  <?php
                  echo '<select name="status">';
                  for ($i = 0; $i < count($statuses); $i++) {
                    if ($statuses[$i] == $row['status'])
                      echo '<option selected value="',$statuses[$i],'">',$statuses[$i],'</option>';
                    else
                      echo '<option value="',$statuses[$i],'">',$statuses[$i],'</option>';
                  }
                  echo '</select>';
                  ?>
                  </th>
                  <th><?php echo $row['datetime'];?></th>
                    <input style="display: none;" type="text" value="<?php echo $row['id'];?>" name="id">

                    <th><input name="" value="Применить" class="button primary" type="submit"></th>
                    </form>
                </tr>
                <script>
                  var link = url + "admin/editBid/<?php echo $row['id'];?>";
                  new bidEditor(document.forms.editBid_<?php echo $row['id'];?>, link, bidEditorStatus);
                </script>
                <?php
              }

              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="container pages-navigation">
      Страницы
        <?php
          if ($this->currPage > 6) 
          {
            echo '<a href="',URL,'adminpanel/clients/1">1</a> ... ';
          } else {  
            if ($this->currPage == 1) {
              echo '1 ';
            } else {
              echo '<a href="',URL,'adminpanel/clients/1">1</a> ';
            }
          }

          for ($i = $this->currPage - 5; $i < $this->currPage + 6; $i++) {
            if ($i > 1 and $i < $this->pagesQuant) {
              if ($this->currPage == $i) {
                echo ' ',$i,' ';
              } else {
                echo ' <a href="',URL,'adminpanel/clients/',$i,'">',$i,'</a> ';
              }
            }
          }

          if ($this->currPage < $this->pagesQuant - 6) 
          {
            echo ' ... <a href="',URL,'adminpanel/clients/',$this->pagesQuant,'">',$this->pagesQuant,'</a>';
          } else {
            if ($this->currPage == $this->pagesQuant) {
              if ($this->currPage != 1) echo ' ',$this->pagesQuant,' ';
            } elseif ($this->pagesQuant > 0) {
              echo ' <a href="',URL,'adminpanel/clients/',$this->pagesQuant,'">',$this->pagesQuant,'</a>';
            }

          }

        ?>
    </div>

    <style>
      .table {
          width: 100%;
          max-width: 100%;
          margin-bottom: 20px;
          margin-left: 0;
          border-spacing: 0;
      }
      /* .table-striped tbody tr:nth-of-type(2n+1) {
          background-color: #dbdbdb;
      } */
      input.button {
        margin-left: 0;
        line-height: 0;
      }
      th {
        vertical-align: top;
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
      }
      .input-field {
          padding: 6px 12px;
          border-radius: 2px;
          box-shadow: none;
          border: 1px solid grey;
      }
      .pages-navigation {
          text-align: center;
          font-size: 24px;
          margin: 20px auto;
      }
    </style>