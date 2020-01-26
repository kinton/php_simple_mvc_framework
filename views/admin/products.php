<?php
	if (isset($this->editProductStatus)) {
		//echo $this->editProductStatus;
		$answer = json_decode($this->editProductStatus);
		if ($answer->status == 'empty') {
		//echo "<script>notify('Некоторые поля не заполнены', 'error');</script>";
		} elseif ($answer->status == 'dontfinded') {
			echo "<script>notify('Данный продукт не найден', 'error');</script>";
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
		} else {
			echo "<script>notify('",$this->editProductStatus,"', 'error');</script>";
		}
	}
?>
<script>
	var url = "<?php echo URL;?>";
	var productChangeAction = "<?php echo URL;?>admin/products";
	var product = new ProductEditor('<?php echo URL;?>admin/deleteProduct', '<?php echo URL;?>admin/editProduct', '<?php echo URL;?>admin/getProduct');
</script>
<?php
	if (isset($this->productId)) {
		echo "<script>product.edit(".$this->productId.", 'dontChange');</script>";
	}
?>
<div class="settings-inn row categories-list">
	<header class="about-block-title"><h2>Список продуктов</h2></header>
	<?php
	echo '<div class="row"><h3>Нажмите на нужную категории</h3>';
	while ($row = $this->categories->fetch()) {
		echo '<div class="variant-buble buble" onclick="getData(',$row['id'],', \'',URL,'admin/loadProducts/byCat\', loadProducts)" id="categoryVariant_',$row['id'],'">',$row['name'],'</div>';
	}
	?>
	</div>
	<!-- <div class="row"><h3>Начните писать название производителя</h3>
		<input id="manufacturerChooser" class="text-input text-input-main" style="float: left; width:312px; margin-right: 30px;"><div id="answerPlaceManufacturer"></div>
	</div> -->
	<div class="row"><h3>Или начните писать название продукта</h3>
		<input id="productChooser" class="text-input text-input-main" style="float: left; width:312px; margin-right: 30px;"><div id="answerPlaceProduct"></div>
	</div>
</div>

<script>
/*var z = function controller(answer) {
	var ans = JSON.parse(answer);
	var answer = '';
	var url = <?php echo "'",URL,"'"; ?>;
	var i = 0;
    for (i = 0; i < ans.i; i++) {
	   	answer += '<div class="variant-buble buble" onclick="getData(' + ans.id[i] + ', \'' + url + 'admin/loadProducts/byManuf\', loadProducts)" id="manufacturerVariant_'+ ans.id[i] +'">'+ans.name[i]+'</div>';
    }
	if (i == 0) {
	   	answer = 'Ничего не найдено, попробуйте ввести другой запрос!';
	}
    getById('answerPlaceManufacturer').innerHTML = answer;
    getById('answerPlaceManufacturer').style.marginBottom = '30px';
}
new GetDataOnKeyUp(getById("manufacturerChooser"), '<?php echo URL;?>admin/manufacturerChooser', z);*/
var p = function controller(answer) {
	var ans = JSON.parse(answer);
	var answer = '';
	var url = <?php echo "'",URL,"'"; ?>;
	var i = 0;
    for (i = 0; i < ans.i; i++) {
	   	answer += '<div class="variant-buble buble" onclick="getData(' + ans.id[i] + ', \'' + url + 'admin/loadProducts/byName\', loadProducts)" id="manufacturerVariant_'+ ans.id[i] +'">'+ans.name[i]+'</div>';
    }
	if (i == 0) {
	   	answer = 'Ничего не найдено, попробуйте ввести другой запрос!';
	}
    getById('answerPlaceProduct').innerHTML = answer;
    getById('answerPlaceProduct').style.marginBottom = '30px';
}
window.addEventListener("load", function() {new GetDataOnKeyUp(document.getElementById("productChooser"), '<?php echo URL;?>admin/productChooser', p);});
//new GetDataOnKeyUp(document.getElementById("productChooser"), '<?php echo URL;?>admin/productChooser', p);
</script>

<div class="table products-table" id="products-table">
	
	<div class="table-container" id="table-container">
		Выберите категорию или производителя
	</div>
</div>

<style>
	.settings-inn {
		display: block;
	}
</style>