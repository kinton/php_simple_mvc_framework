<?php

class Admin_Model extends Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function addProduct() {
		if (!isset($_POST['name'])/* and !isset($_POST['manufacturer'])*/ and !isset($_POST['category']) and !isset($_POST['price'])/* and !isset($_POST['composition'])*/ and !isset($_POST['productPhoto'])/* and !isset($_POST['description']) and !isset($_POST['quantity'])*/) {

			$answer = array(
				'status' => 'empty'
			);
			return json_encode($answer);
	    	exit();

		}

		if ($_POST['name']/* and $_POST['manufacturer']*/ and $_POST['category'] and $_POST['price']/* and $_POST['composition']*/ and $_POST['productPhoto']/* and $_POST['description'] and $_POST['quantity']*/) {

			$answer = array(
				'status' => 'empty'
			);
			return json_encode($answer);
	    	exit();
	    		
		}

		$sth = $this->db->prepare("SELECT id FROM products WHERE name = ?");
		$sth->execute(array($_POST['name']));
		$count = $sth->rowCount();
		if ($count > 0) {
			$answer = array(
				'status' => 'alreadyRegistered'
			);
			return json_encode($answer);
	    	exit();
		}

		/*$sth = $this->db->prepare("SELECT id FROM manufactures WHERE name = ?");
		$sth->execute(array($_POST['manufacturer']));
		$manufacturer = $sth->fetchColumn();
		if (!is_numeric($manufacturer)) {
			$answer = array(
				'status' => 'no_manufacturer'
			);
			return json_encode($answer);
			exit();
		}*/
		$manufacturer = 0;

		$sth = $this->db->prepare("SELECT id FROM categories WHERE name = ?");
		$sth->execute(array($_POST['category']));
		$category = $sth->fetchColumn();
		if (!is_numeric($category)) {
			$answer = array(
				'status' => 'no_category'
			);
			return json_encode($answer);
			exit();
		}

		if (!is_numeric($_POST['price'])) {
			$answer = array(
				'status' => 'isnt_numeric'
			);
			return json_encode($answer);
			exit();
		}

		/*if (!is_numeric($_POST['quantity']) and $_POST['quantity'] != '') {
			$answer = array(
				'quantity' => 'isnt_numeric'
			);
			return json_encode($answer);
			exit();
		}*/

		if (!isset($_POST['composition']) or $_POST['composition'] == '') {
			$composition = '';
		} else $composition = $_POST['composition'];
		if (!isset($_POST['description']) or $_POST['description'] == '') {
			$description = '';
		} else $description = $_POST['description'];
		if (!isset($_POST['weight']) or $_POST['weight'] == '') {
			$weight = '';
		} else $weight = $_POST['weight'];
		if (!isset($_POST['prices']) or $_POST['prices'] == '') {
			$prices = '';
		} else $prices = $_POST['prices'];

		$date = time();
		$datetime = date("Y-m-d H:i:s", time());

		$userId = Session::get('id');

		$photos = [];

		if (isset($_FILES['productPhoto']['tmp_name'][0])) {
			if (count($_FILES['productPhoto']['tmp_name'])) {
				for ($i = 0; $i < count($_FILES['productPhoto']['tmp_name']); $i++) {
					if(is_uploaded_file($_FILES['productPhoto']['tmp_name'][$i])) {
						if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['productPhoto']['name'][0]) or $i!=0) {

							$path_to_90_directory = "public/images/product_photos/";
							$filename =    $_FILES['productPhoto']['name'][$i];
							$source =    $_FILES['productPhoto']['tmp_name'][$i];

							$filename = explode('.', $filename);
                			$photoExtension = array_pop($filename);
							$target =    $path_to_90_directory.$date."-".$i."-".$userId.".".$photoExtension;
							move_uploaded_file($source, $target);

							array_push($photos, "public/images/product_photos/".$date."-".$i."-".$userId.".".$photoExtension);

					        if (count($photos) > 20) {
					        	break;
					        }
					    }
					}
				}
			} else {
				$answer = array(
					'status' => 'error'
				);
				return json_encode($answer);
				exit();
			}
		} else {
			$photos[0] = 'public/images/product_photos/default.jpg';
		}

		if (count($photos) == 0) {
			$photos[0] = 'public/images/product_photos/default.jpg';
		}

		$sth = $this->db->prepare("INSERT INTO products (name, manufacturer, category, price, prices, composition, photos, description, quantity, weight, creator, datetime) VALUES (:name, :manufacturer, :category, :price, :prices, :composition, :photos, :description, :quantity, :weight, :creator, :datetime)");
		$sth->execute(array(
			':name' => $_POST['name'],
			':manufacturer' => $manufacturer,
			':category' => $category,
			':price' => $_POST['price'],
			':prices' => $_POST['prices'],
			':composition' => $composition,
			':photos' => serialize($photos),
			':description' => $description,
			':creator' => Session::get('id'),
			':datetime' => $datetime,
			':quantity' => 0,
			':weight' => $weight//$_POST['quantity']
		));

		//echo $_POST['composition'];

		$sth = $this->db->prepare("SELECT id FROM products WHERE name = ? AND datetime = ?");
		$sth->execute(array($_POST['name'], $datetime));
		$count = $sth->rowCount();
		if ($count > 0) {
			$answer = array(
				'id' => $sth->fetchColumn(),
				'status' => 'good',
				':name' => $_POST['name'],
				':manufacturer' => $manufacturer,
				':category' => $category,
				':price' => $_POST['price'],
				':composition' => $composition,
				':photos' => $photos,
				':description' => $description,
				':datetime' => $datetime,
				':quantity' => $_POST['quantity']
			);
			return json_encode($answer);
	    	exit();
		} else {
			$answer = array(
				'status' => 'error'
			);
			if ($photos[0] != "public/images/manufacturers_logos/default.jpg") {
				//$urlLenght = strlen(URL);
				for ($i=0; $i < count($photos); $i++) {
					//$logo = substr($photos[$i], $urlLenght);
					$logo = $photos[$i];
					unlink($logo);
				}
			}
			return json_encode($answer);
	    	exit();
		}

	}

	public function editProduct($id)
	{

		if (!isset($_POST['name'])/* and !isset($_POST['manufacturer'])*/ and !isset($_POST['category']) and !isset($_POST['price'])/* and !isset($_POST['composition']) and !isset($_POST['description']) */and !isset($_POST['status'])/* and !isset($_POST['quantity'])*/) {

			$answer = array(
				'status' => 'empty'
			);
			return json_encode($answer);
	    	exit();

		}

		if ($_POST['name'] == ''/* or $_POST['manufacturer'] == ''*/ or $_POST['category'] == '' or $_POST['price'] == ''/* or $_POST['composition'] == '' or $_POST['description'] == ''*/ or $_POST['status'] == ''/* or $_POST['quantity'] == ''*/) {

			$answer = array(
				'status' => 'empty'
			);
			return json_encode($answer);
	    	exit();
	    		
		}

		if (Session::get('role') == 'owner') {
			$sth = $this->db->prepare("SELECT id FROM products WHERE id = ?");
			$sth->execute(array($_POST['id']));
		} elseif (Session::get('role') == 'admin') {
			$sth = $this->db->prepare("SELECT id FROM products WHERE id = ? AND creator = ?");
			$sth->execute(array($_POST['id']), Session::get('id'));
		}
		$count = $sth->rowCount();
		if ($count == 0) {
			$answer = array(
				'status' => 'dontfinded'
			);
			return json_encode($answer);
	    	exit();
		}

		/*$sth = $this->db->prepare("SELECT id FROM manufactures WHERE name = ?");
		$sth->execute(array($_POST['manufacturer']));
		$manufacturer = $sth->fetchColumn();
		if (!is_numeric($manufacturer)) {
			$answer = array(
				'status' => 'no_manufacturer'
			);
			return json_encode($answer);
			exit();
		}*/
		$manufacturer = 0;

		$sth = $this->db->prepare("SELECT id FROM categories WHERE name = ?");
		$sth->execute(array($_POST['category']));
		$category = $sth->fetchColumn();
		if (!is_numeric($category)) {
			$answer = array(
				'status' => 'no_category'
			);
			return json_encode($answer);
			exit();
		}

		if (!is_numeric($_POST['price'])/* or !is_numeric($_POST['quantity'])*/) {
			$answer = array(
				'status' => 'isnt_numeric'
			);
			return json_encode($answer);
			exit();
		}

		if (!isset($_POST['composition']) or $_POST['composition'] == '') {
			$composition = '';
		} else $composition = $_POST['composition'];
		if (!isset($_POST['description']) or $_POST['description'] == '') {
			$description = '';
		} else $description = $_POST['description'];
		if (!isset($_POST['weight']) or $_POST['weight'] == '') {
			$weight = '';
		} else $weight = $_POST['weight'];
		if (!isset($_POST['prices']) or $_POST['prices'] == '') {
			$prices = '';
		} else $prices = $_POST['prices'];

		$date = time();
		$datetime = date("Y-m-d H:i:s", time());

		$sth = $this->db->prepare("SELECT photos FROM products WHERE id=?");
		$sth->execute(array($_POST['id']));
		$oldPhotos = $sth->fetchColumn();

		$oldPhotos = unserialize($oldPhotos);

		$userId = Session::get('id');

		$urlLenght = strlen(URL);
		$currentPhotosLength = count($oldPhotos);

		$photos = [];

		for ($i=0; $i < $currentPhotosLength; $i++) {
			if (is_uploaded_file($_FILES['productPhoto']['tmp_name'][$i])) {
				if (preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['productPhoto']['name'][0]) or $i!=0) {
					$path_to_90_directory = "public/images/product_photos/";
					$filename =    $_FILES['productPhoto']['name'][$i];
					$source =    $_FILES['productPhoto']['tmp_name'][$i];

					$filename = explode('.', $filename);
                	$photoExtension = array_pop($filename);
					$target =    $path_to_90_directory.$date."-".$i."-".$userId.".".$photoExtension;
					move_uploaded_file($source, $target);

					$photos[$i] = "public/images/product_photos/".$date."-".$i."-".$userId.".".$photoExtension;

			        if ($oldPhotos[$i] != "public/images/manufacturers_logos/default.jpg") {
				        //$logo = substr($oldPhotos[$i], $urlLenght);
				        $logo = $oldPhotos[$i];
						unlink($logo);
					}
				} else {
					$photos[$i] = $oldPhotos[$i];
				}
			} else {
				$photos[$i] = $oldPhotos[$i];
			}
		}

		$limiter = 20 - $i;

		if (isset($_FILES['productPhotoNew']['tmp_name'][0])) {
			if (count($_FILES['productPhotoNew']['tmp_name'])) {
				for ($i = 0; $i < count($_FILES['productPhotoNew']['tmp_name']); $i++) {
					if(is_uploaded_file($_FILES['productPhotoNew']['tmp_name'][$i])) {
						if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['productPhotoNew']['name'][0]) or $i!=0) {

							$path_to_90_directory = "public/images/product_photos/";
							$filename =    $_FILES['productPhotoNew']['name'][$i];
							$source =    $_FILES['productPhotoNew']['tmp_name'][$i];

							$filename = explode('.', $filename);
                			$photoExtension = array_pop($filename);
							$target =    $path_to_90_directory.$date."-".$i."-".$userId.".".$photoExtension;
							move_uploaded_file($source, $target);

							array_push($photos, "public/images/product_photos/".$date."-".$i."-".$userId.".".$photoExtension);

					        if (count($photos) > 20) {
					        	break;
					        }
					    }
					}
				}
			}
		}

		$photos = serialize($photos);

		$status = $_POST['status'];

		if (Session::get('role') == 'admin') {
			$sth = $this->db->prepare("UPDATE products SET name=:name, manufacturer=:manufacturer, category=:category, price=:price, prices=:prices, composition=:composition, photos=:photos, description=:description, status=:status, quantity = :quantity, weight = :weight, datetime=:datetime WHERE id=:id AND creator=:creator");
			$sth->execute(array(
				':name' => $_POST['name'],
				':manufacturer' => $manufacturer,
				':category' => $category,
				':price' => $_POST['price'],
				':prices' => $prices,
				':composition' => $composition,
				':photos' => $photos,
				':description' => $description,
				':creator' => Session::get('id'),
				':datetime' => $datetime,
				':id' => $_POST['id'],
				':status' => $status,
				':quantity' => 0,
				':weight' => $weight//$_POST['quantity']
			));
		}
		if (Session::get('role') == 'owner') {
			$sth = $this->db->prepare("UPDATE products SET name=:name, manufacturer=:manufacturer, category=:category, price=:price, prices=:prices, composition=:composition, photos=:photos, description=:description, status=:status, quantity=:quantity, weight = :weight, datetime=:datetime WHERE id=:id");
			$sth->execute(array(
				':name' => $_POST['name'],
				':manufacturer' => $manufacturer,
				':category' => $category,
				':price' => $_POST['price'],
				':prices' => $prices,
				':composition' => $composition,
				':photos' => $photos,
				':description' => $description,
				':datetime' => $datetime,
				':id' => $_POST['id'],
				':status' => $status,
				':quantity' => $_POST['quantity'],
				':weight' => $weight
			));
		}

		$sth = $this->db->prepare("SELECT id FROM products WHERE name = ? AND datetime = ?");
		$sth->execute(array($_POST['name'], $datetime));
		$id = $sth->fetchColumn();
		$count = $sth->rowCount();
		if ($count > 0) {
			$answer = array(
				'id' => $id,
				'status' => 'good'
			);
			return json_encode($answer);
			exit();
		} else {
			$answer = array(
				'status' => 'error'
			);
			return json_encode($answer);
	    	exit();
		}
		
	}



	public function getManufacturers()
	{
		$sth = $this->db->prepare("SELECT * FROM manufactures ORDER BY name");
        $sth->execute();
		return $sth;
	}
	public function getCategories()
	{
		$sth = $this->db->prepare("SELECT * FROM categories ORDER BY itemsorder");
        $sth->execute();
		return $sth;
	}

	public function manufacturerChooser()
	{
		$sth = $this->db->prepare("SELECT * FROM manufactures WHERE name LIKE '%".$_POST['inputText']."%' ORDER BY name LIMIT 10");
		$sth->execute();
		$i = 0;
		while ($row = $sth->fetch())
		{
			$searchManufacturers['id'][$i] = $row['id'];
			$searchManufacturers['name'][$i] = $row['name'];
			$searchManufacturers['logo'][$i] = $row['logo'];
			$i+=1;

		}
		$searchManufacturers['i'] = $i;
		echo json_encode($searchManufacturers);
	}

	public function categoryChooser()
	{
		$sth = $this->db->prepare("SELECT * FROM categories WHERE name LIKE '%".$_POST['inputText']."%' ORDER BY name LIMIT 10");
		$sth->execute();
		$i = 0;
		while ($row = $sth->fetch())
		{
			$searchCategories['id'][$i] = $row['id'];
			$searchCategories['name'][$i] = $row['name'];
			$searchCategories['logo'][$i] = $row['logo'];
			$i+=1;

		}
		$searchCategories['i'] = $i;
		echo json_encode($searchCategories);
	}

	public function productChooser()
	{	
		if (Session::get('role') == 'owner') {
			$sth = $this->db->prepare("SELECT * FROM products WHERE name LIKE '%".$_POST['inputText']."%' ORDER BY name LIMIT 10");
		} elseif (Session::get('role') == 'admin') {
			$sth = $this->db->prepare("SELECT * FROM products WHERE name LIKE '%".$_POST['inputText']."%' AND creator = ".Session::get('id')." ORDER BY name LIMIT 10");
		}
		$sth->execute();
		$i = 0;
		while ($row = $sth->fetch())
		{
			$searchCategories['id'][$i] = $row['id'];
			$searchCategories['name'][$i] = $row['name'];
			$searchCategories['logo'][$i] = $row['logo'];
			$i+=1;

		}
		$searchCategories['i'] = $i;
		echo json_encode($searchCategories);
	}

	public function getProduct() {
		if (Session::get('role') == 'owner') {
			$sth = $this->db->prepare("SELECT * FROM products WHERE id = ?");
			$sth->execute(array($_POST['input']));
		} elseif (Session::get('role') == 'admin') {
			$sth = $this->db->prepare("SELECT * FROM products WHERE id = ? AND creator = ?");
			$sth->execute(array($_POST['input'], Session::get('id')));
		}

		$count = $sth->rowCount();
		if ($count == 0) {
			$answer = array(
				'status' => 'error'
			);
			echo json_encode($answer);;
			exit();
		}

		$sth = $sth->fetch();

		foreach ($sth as $k => $v) {
    		$answer[$k] = $v;
    	}

    	$answer['photos'] = unserialize($sth['photos']);

    	$manufacturer = $this->db->prepare("SELECT name, logo FROM manufactures WHERE id = ?");
		$manufacturer->execute(array($sth['manufacturer']));
		$manufacturer = $manufacturer->fetch();

		$category = $this->db->prepare("SELECT name FROM categories WHERE id = ?");
		$category->execute(array($sth['category']));
		$category = $category->fetchColumn();

		if (Session::get('role') == 'owner') {
			$creatorName = $this->db->prepare("SELECT name, surname FROM users WHERE id = ?");
			$creatorName->execute(array($sth['creator']));
			$creatorName = $creatorName->fetch();

			$answer['creatorName'] = $creatorName['name'];
			$answer['creatorSurname'] = $creatorName['surname'];
		}

		$answer['manufacturerName'] = $manufacturer['name'];
		$answer['manufacturerLogo'] = $manufacturer['logo'];
		$answer['categoryName'] = $category;

    	echo json_encode($answer);;


	}

	public function loadProducts($by) {
		if ($by == 'byCat') {
			if (Session::get('role') == 'owner') {
				$sth = $this->db->prepare("SELECT * FROM products WHERE category = ? ORDER BY name");
				$sth->execute(array($_POST['input']));
			} elseif (Session::get('role') == 'admin') {
				$sth = $this->db->prepare("SELECT * FROM products WHERE category = ? AND creator = ? ORDER BY name");
				$sth->execute(array($_POST['input'], Session::get('id')));
			}
		} elseif ($by == 'byManuf') {
			if (Session::get('role') == 'owner') {
				$sth = $this->db->prepare("SELECT * FROM products WHERE manufacturer = ? ORDER BY name");
				$sth->execute(array($_POST['input']));
			} elseif (Session::get('role') == 'admin') {
				$sth = $this->db->prepare("SELECT * FROM products WHERE manufacturer = ? AND creator = ? ORDER BY name");
				$sth->execute(array($_POST['input'], Session::get('id')));
			}
		} elseif ($by == 'byName') {
			if (Session::get('role') == 'owner') {
				$sth = $this->db->prepare("SELECT * FROM products WHERE id = ? ORDER BY name");
				$sth->execute(array($_POST['input']));
			} elseif (Session::get('role') == 'admin') {
				$sth = $this->db->prepare("SELECT * FROM products WHERE id = ? AND creator = ? ORDER BY name");
				$sth->execute(array($_POST['input'], Session::get('id')));
			}
		}
		$i = 0;
		while ($row = $sth->fetch())
		{	
			$manufacturer = $this->db->prepare("SELECT name FROM manufactures WHERE id = ?");
			$manufacturer->execute(array($row['manufacturer']));
			$manufacturer = $manufacturer->fetchColumn();

			$category = $this->db->prepare("SELECT name FROM categories WHERE id = ?");
			$category->execute(array($row['category']));
			$category = $category->fetchColumn();
			
			foreach ($row as $k => $v) {
	    		$products[$k][$i] = $v;
	    	}
			/*$products['id'][$i] = $row['id'];
			$products['name'][$i] = $row['name'];*/
			$products['manufacturer_id'][$i] = $row['manufacturer'];
			$products['manufacturer'][$i] = $manufacturer;
			$products['category_id'][$i] = $row['category'];
			$products['category'][$i] = $category;
			//$products['price'][$i] = $row['price'];
			$products['photos'][$i] = unserialize($row['photos']);
			/*$products['composition'][$i] = $row['composition'];
			$products['description'][$i] = $row['description'];
			$products['creator'][$i] = $row['creator'];*/
			$products['date'][$i] = $row['datetime'];
			$products['weight'][$i] = $row['weight'];
			$i+=1;

		}
		$products['i'] = $i;
		$products['sessionRole'] = Session::get('role');
		$products['byWhat'] = $by;
		echo json_encode($products);
	}

	public function addSetting($type) {
		//проверка на получение переменных
		if ($type == 'manufacturer') {
			if (!isset($_POST['manufacturer']) or !isset($_POST['description'])) {
				$answer = array(
					'answer' => 'empty'
				);
				echo json_encode($answer);
	    		exit();
			}
			if ($_POST['manufacturer'] == '' or $_POST['description'] == '') {
				$answer = array(
					'answer' => 'empty'
				);
				echo json_encode($answer);
	    		exit();
			}
			//проверка на наличие
			$sth = $this->db->prepare("SELECT id FROM manufactures WHERE name=:name");
			$sth->execute(array(
				':name' => $_POST['manufacturer']
			));
		}
		if ($type == 'category') {
			if (!isset($_POST['category']) or !isset($_POST['description'])) {
				$answer = array(
					'answer' => 'empty'
				);
				echo json_encode($answer);
	    		exit();
			}
			if ($_POST['category'] == '' or $_POST['description'] == '') {
				$answer = array(
					'answer' => 'empty'
				);
				echo json_encode($answer);
	    		exit();
			}
			//проверка на наличие
			$sth = $this->db->prepare("SELECT id FROM categories WHERE name=:name");
			$sth->execute(array(
				':name' => $_POST['category']
			));
		}
		$count = $sth->rowCount();
		
		if ($count > 0) {
			$answer = array(
				'answer' => 'repeat'
			);
			echo json_encode($answer);
			exit();
		}

		//добавление

		$date = time();
		$datetime = date("Y-m-d H:i:s", time());

		if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
			if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['logo']['name'])) {

				if ($type == 'manufacturer') {
					$path_to_90_directory = "public/images/manufacturers_logos/";
				}
				if ($type == 'category') {
					$path_to_90_directory = "public/images/categories_backgrounds/";
				}
				$filename =    $_FILES['logo']['name'];
				$source =    $_FILES['logo']['tmp_name'];

				$filename = explode('.', $filename);
                $photoExtension = array_pop($filename);
				$target =    $path_to_90_directory.$date.'.'.$photoExtension;
				move_uploaded_file($source, $target);

				if ($type == 'manufacturer') {
					$logo = "public/images/manufacturers_logos/".$date.".".$photoExtension;
				}
				if ($type == 'category') {
					$logo = "public/images/categories_backgrounds/".$date.".".$photoExtension;
				}
			} else {
				//exit ("<meta http-equiv='Refresh' content='2; URL=page.php?id=".$id."';></head><body>Обложка альбома должна быть только в таких форматах, как JPG, PNG или GIF!<br></body></html>");
				$answer = array(
					'answer' => 'format'
				);
				echo json_encode($answer);
				exit();
			}
		} else {
			if ($type == 'manufacturer') {
				$logo = "public/images/manufacturers_logos/default.jpg";
			}
			if ($type == 'category') {
				$logo = "public/images/categories_backgrounds/default.jpg";
			}
		}

		if ($type == 'manufacturer') {
			$sth = $this->db->prepare("INSERT INTO manufactures (name, logo, description, datetime) VALUES (:name, :logo, :description, :datetime)");

			$sth->execute(array(
				':name' => $_POST['manufacturer'],
				':logo' => $logo,
				':description' => $_POST['description'],
				':datetime' => $datetime
			));

			//проверка: добавилось или нет
			$sth = $this->db->prepare("SELECT * FROM manufactures WHERE name=:name AND datetime=:datetime");
			$sth->execute(array(
				':name' => $_POST['manufacturer'],
				':datetime' => $datetime
			));
			$result = $sth->fetch();
			if ($result['name'] == $_POST['manufacturer'] and $result['description'] == $_POST['description']) {
				$answer = array(
					'answer' => 'good',
					'name' => $result['name'],
					'description' => $result['description'],
					'id' => $result['id'],
					'logo' => $result['logo'],
					'datetime' => $result['datetime']
				);
				echo json_encode($answer);
				exit();
			} else {
				$answer = array(
					'answer' => 'error'
				);
				echo json_encode($answer);

				if ($logo != "public/images/manufacturers_logos/default.jpg") {
					//$urlLenght = strlen(URL);
					//$logo = substr($logo, $urlLenght);
					unlink($logo);
				}

				exit();
			}

		}

		if ($type == 'category') {

			$sth = $this->db->prepare("SELECT id FROM categories WHERE 1");
			$sth->execute();
			$itemsorder = $sth->rowCount();

			$sth = $this->db->prepare("INSERT INTO categories (name, logo, description, itemsorder, datetime) VALUES (:name, :logo, :description, :itemsorder, :datetime)");

			$sth->execute(array(
				':name' => $_POST['category'],
				':logo' => $logo,
				':description' => $_POST['description'],
				':itemsorder' => $itemsorder,
				':datetime' => $datetime
			));

			//проверка: добавилось или нет
			$sth = $this->db->prepare("SELECT * FROM categories WHERE name=:name AND datetime=:datetime");
			$sth->execute(array(
				':name' => $_POST['category'],
				':datetime' => $datetime
			));
			$result = $sth->fetch();
			if ($result['name'] == $_POST['category'] and $result['description'] == $_POST['description']) {
				$answer = array(
					'answer' => 'good',
					'name' => $result['name'],
					'description' => $result['description'],
					'id' => $result['id'],
					'logo' => $result['logo'],
					'datetime' => $result['datetime']
				);
				echo json_encode($answer);
				exit();
			} else {
				$answer = array(
					'answer' => 'error'
				);
				echo json_encode($answer);			
				if ($logo != "public/images/categories_backgrounds/default.jpg") {
					//$urlLenght = strlen(URL);
					//$logo = substr($logo, $urlLenght);
					unlink($logo);
				}
				exit();
			}

		}
	}

	public function deleteManufacturer() {
		$sth = $this->db->prepare("SELECT id, logo FROM manufactures WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$count = $sth->rowCount();
		
		if ($count == 0) {
			echo "alreadyDeleted";
			exit();
		}

		$logo = $sth->fetch();
		$logo = $logo['logo'];

		$sth = $this->db->prepare("DELETE FROM manufactures WHERE id = :id");
		$sth->execute(array(
			':id' => $_POST['id']
		));

		$sth = $this->db->prepare("SELECT id FROM manufactures WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$count = $sth->rowCount();
		
		if ($count > 0) {
			echo "error";
			exit();
		} else {
			if ($logo != "public/images/manufacturers_logos/default.jpg") {
				//$urlLenght = strlen(URL);
				//$logo = substr($logo, $urlLenght);
				unlink($logo);
			}
			echo "success";
			exit();
		}
	}

	public function deleteCategory() {
		$sth = $this->db->prepare("SELECT id, logo FROM categories WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$count = $sth->rowCount();
		
		if ($count == 0) {
			echo "alreadyDeleted";
			exit();
		}

		$logo = $sth->fetch();
		$logo = $logo['logo'];

		$sth = $this->db->prepare("DELETE FROM categories WHERE id = :id");
		$sth->execute(array(
			':id' => $_POST['id']
		));

		$sth = $this->db->prepare("SELECT id FROM categories WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$count = $sth->rowCount();
		
		if ($count > 0) {
			echo "error";
			exit();
		} else {
			if ($logo != "public/images/categories_backgrounds/default.jpg") {
				//$urlLenght = strlen(URL);
				//$logo = substr($logo, $urlLenght);
				unlink($logo);
			}

			$sth = $this->db->prepare("SELECT id FROM products WHERE category=:category");
			$sth->execute(array(
				':category' => $_POST['id']
			));
			while ($row = $sth->fetch())
			{
				$this->deleteProduct($row['id']);

			}

			echo "success";
			exit();
		}
	}

	public function deleteProduct($id = -1) {
		if ($id > 0) $needableId == $id; else $needableId = $_POST['id'];
		$sth = $this->db->prepare("SELECT id, photos FROM products WHERE id=:id");
		$sth->execute(array(
			':id' => $needableId
		));
		$count = $sth->rowCount();
		
		if ($count == 0) {
			echo "alreadyDeleted";
			exit();
		}

		if (Session::get('role') != 'owner') {
			$sth = $this->db->prepare("SELECT id, photos FROM products WHERE id=:id AND creator=:creator");
			$sth->execute(array(
				':id' => $needableId,
				':creator' => Session::get('id')
			));
			$count = $sth->rowCount();
			
			if ($count == 0) {
				echo "accessDenied";
				exit();
			}
		}

		$photos = $sth->fetch();
		$photos = unserialize($photos['photos']);

		$sth = $this->db->prepare("DELETE FROM products WHERE id = :id");
		$sth->execute(array(
			':id' => $needableId
		));

		$sth = $this->db->prepare("SELECT id FROM products WHERE id=:id");
		$sth->execute(array(
			':id' => $needableId
		));
		$count = $sth->rowCount();
		
		if ($count > 0) {
			echo "error";
			exit();
		} else {
			if ($photos[0] != "public/images/product_photos/default.jpg" and isset($photos[0]) and $photos[0] != '' and count($photos) > 0) {
				//$urlLenght = strlen(URL);
				for ($i = 0; $i < count($photos); $i++) {
					//$photo = substr($photos[$i], $urlLenght);
					$photo = $photos[$i];
					if (file_exists($photo)) {
						unlink($photo);
					}
				}
			}
			echo "success";
			exit();
		}
	}

	public function editManufacturer() {
		//проверка на получение переменных
		if (!isset($_POST['manufacturer']) or !isset($_POST['description'])) {
			header('Location: ../settings/manufacturers');
			$answer = array(
				'answer' => 'empty'
			);
			echo json_encode($answer);
    		exit();
		}
		if ($_POST['manufacturer'] == '' or $_POST['description'] == '') {
			header('Location: ../settings/manufacturers');
			$answer = array(
				'answer' => 'empty'
			);
			echo json_encode($answer);
    		exit();
		}
		//проверка на наличие
		$sth = $this->db->prepare("SELECT * FROM manufactures WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$count = $sth->rowCount();
		
		if ($count == 0) {
			$answer = array(
				'answer' => 'none'
			);
			echo json_encode($answer);
			exit();
		}

		//добавление

		$date = time();
		$datetime = date("Y-m-d H:i:s", time());

		if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
			if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['logo']['name'])) {

				$path_to_90_directory = "public/images/manufacturers_logos/";
				$filename =    $_FILES['logo']['name'];
				$source =    $_FILES['logo']['tmp_name']; 

				$filename = explode('.', $filename);
                $photoExtension = array_pop($filename);
				$target =    $path_to_90_directory.$_POST['id'].'_'.$date.".".$photoExtension;
				move_uploaded_file($source, $target);//загрузка оригинала в папку $path_to_90_directory

				$logo = "public/images/manufacturers_logos/".$_POST['id'].'_'.$date.".".$photoExtension;
			} else {
				//exit ("<meta http-equiv='Refresh' content='2; URL=page.php?id=".$id."';></head><body>Обложка альбома должна быть только в таких форматах, как JPG, PNG или GIF!<br></body></html>");
				$answer = array(
					'answer' => 'format'
				);
				echo json_encode($answer);
					exit();
				}
		}

		$sth = $this->db->prepare("SELECT logo FROM manufactures WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$logotype = $sth->fetchColumn();

		if ($logotype != $logo and $logo != '') {
			//$logotype = explode("/", $logotype);
			//if ($logotype[6] != 'default.jpg' and $logotype[6] != '') {
			if ($logotype != 'public/images/manufacturers_logos/default.jpg' and $logotype != '') {
				unlink($logotype);
			}
			$sth = $this->db->prepare("UPDATE manufactures SET name=:name, logo=:logo, description=:description, datetime=:datetime WHERE id=:id");

			$sth->execute(array(
				':name' => $_POST['manufacturer'],
				':logo' => $logo,
				':description' => $_POST['description'],
				':datetime' => $datetime,
				':id' => $_POST['id']
			));
		} else {
			$sth = $this->db->prepare("UPDATE manufactures SET name=:name, description=:description, datetime=:datetime WHERE id=:id");

			$sth->execute(array(
				':name' => $_POST['manufacturer'],
				':description' => $_POST['description'],
				':datetime' => $datetime,
				':id' => $_POST['id']
			));
		}

		$sth = $this->db->prepare("SELECT * FROM manufactures WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$result = $sth->fetch();
		if ($result['name'] == $_POST['manufacturer'] and $result['description'] == $_POST['description']) {
			$answer = array(
				'answer' => 'good',
				'name' => $result['name'],
				'description' => $result['description'],
				'id' => $result['id'],
				'logo' => $result['logo'],
				'datetime' => $result['datetime']
			);
			echo json_encode($answer);
			exit();
		} else {
			$answer = array(
				'answer' => 'error'
			);
			echo json_encode($answer);
			exit();
		}
	}

	public function deleteItemPhoto() {
		if (!isset($_POST['itemId']) or !isset($_POST['photoUrl'])) {
			$answer = array(
				'answer' => 'empty'
			);
			echo json_encode($answer);
    		exit();
		}

		if ($_POST['itemId'] == '' or $_POST['photoUrl'] == '') {
			$answer = array(
				'answer' => 'empty'
			);
			echo json_encode($answer);
    		exit();
		}

		if (Session::get('role') == 'owner') {
			$sth = $this->db->prepare("SELECT photos FROM products WHERE id=?");
			$sth->execute(array($_POST['itemId']));
		} elseif (Session::get('role') == 'admin') {
			$sth = $this->db->prepare("SELECT photos FROM products WHERE id=? AND creator=?");
			$sth->execute(array($_POST['itemId']), Session::get('id'));
		}
 
		$count = $sth->rowCount();

		if ($count == 0) {
			$answer = array(
				'answer' => 'none'
			);
			echo json_encode($answer);
			exit();
		}

		$photos = $sth->fetchColumn();
		$photos = unserialize($photos);

		$key = array_search($_POST['photoUrl'], $photos);
		if ($key or $key == 0) {
			if (isset($photos[$key])) {
				$photo = $photos[$key];

				$arrLength = count($photos);

				unset($photos[$key]);
				for ($i=$key; $i < $arrLength; $i++) { 
					$photos[$i] = $photos[$i+1];
				}

				unset($photos[$arrLength-1]);

				$photos = serialize($photos);
				if (Session::get('role') == 'owner') {
					$sth = $this->db->prepare("UPDATE products SET photos=? WHERE id=?");
					$sth->execute(array($photos, $_POST['itemId']));
				} elseif (Session::get('role') == 'admin') {
					$sth = $this->db->prepare("UPDATE products SET photos=? WHERE id=? AND creator=?");
					$sth->execute(array($photos, $_POST['itemId']), Session::get('id'));
				}

				//$urlLenght = strlen(URL);
				//$photo = substr($photo, $urlLenght);

				if (file_exists($photo)) {
					unlink($photo);
				}

				$answer = array(
					'answer' => 'good',
					'id' => $_POST['itemId'],
					'url' => $_POST['photoUrl'],
				);
				echo json_encode($answer);
				exit();
			}
		} else {
			$answer = array(
				'answer' => 'nonePhoto'
			);
			echo json_encode($answer);
			exit();
		}

	}

	public function editCategory() {
		//проверка на получение переменных
		if (!isset($_POST['category'])) {
			header('Location: ../settings/manufacturers');
			$answer = array(
				'answer' => 'empty'
			);
			echo json_encode($answer);
    		exit();
		}
		if ($_POST['category'] == '') {
			header('Location: ../settings/manufacturers');
			$answer = array(
				'answer' => 'empty'
			);
			echo json_encode($answer);
    		exit();
		}
		if (!isset($_POST['description'])) $description = ''; else $description = $_POST['description'];

		$itemsorder;
		$sth = $this->db->prepare("SELECT id FROM categories WHERE 1");
		$sth->execute();
		$tableItemsCount = $sth->rowCount();
		if (!isset($_POST['itemsorder']) or $_POST['itemsorder'] == '' or $_POST['itemsorder'] > $tableItemsCount or $_POST['itemsorder'] < 1) {
			$itemsorder = $tableItemsCount;
		} else $itemsorder = $_POST['itemsorder'];

		//проверка на наличие
		$sth = $this->db->prepare("SELECT name, itemsorder FROM categories WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$count = $sth->rowCount();
		
		if ($count == 0) {
			$answer = array(
				'answer' => 'none'
			);
			echo json_encode($answer);
			exit();
		}

		$prevOrder = $sth->fetch()['itemsorder'];

		//добавление

		$date = time();
		$datetime = date("Y-m-d H:i:s", time());

		if(is_uploaded_file($_FILES['logo']['tmp_name'])) {
			if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['logo']['name'])) {

				$path_to_90_directory = "public/images/categories_backgrounds/";
				$filename =    $_FILES['logo']['name'];
				$source =    $_FILES['logo']['tmp_name']; 

				$filename = explode('.', $filename);
                $photoExtension = array_pop($filename);
				$target =    $path_to_90_directory.$_POST['id'].'_'.$date.'.'.$photoExtension;
				move_uploaded_file($source, $target);//загрузка оригинала в папку $path_to_90_directory

				
				$logo = "public/images/categories_backgrounds/".$_POST['id'].'_'.$date.'.'.$photoExtension;
			} else {
				//exit ("<meta http-equiv='Refresh' content='2; URL=page.php?id=".$id."';></head><body>Обложка альбома должна быть только в таких форматах, как JPG, PNG или GIF!<br></body></html>");
				$answer = array(
					'answer' => 'format'
				);
				echo json_encode($answer);
					exit();
				}
		}

		$sth = $this->db->prepare("SELECT logo FROM categories WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$logotype = $sth->fetchColumn();

		if ($logotype != $logo and $logo != '') {
			//$logotype = explode("/", $logotype);
			//if ($logotype[6] != 'default.jpg' and $logotype[6] != '') {
			if ($logotype != 'public/images/categories_backgrounds/default.jpg' and $logotype != '') {
				unlink($logotype);
			}
			$sth = $this->db->prepare("UPDATE categories SET name=:name, logo=:logo, description=:description, itemsorder=:itemsorder, datetime=:datetime WHERE id=:id");

			$sth->execute(array(
				':name' => $_POST['category'],
				':logo' => $logo,
				':description' => $description,
				':itemsorder' => $itemsorder,
				':datetime' => $datetime,
				':id' => $_POST['id']
			));
		} else {
			$sth = $this->db->prepare("UPDATE categories SET name=:name, description=:description, itemsorder=:itemsorder, datetime=:datetime WHERE id=:id");

			$sth->execute(array(
				':name' => $_POST['category'],
				':description' => $description,
				':itemsorder' => $itemsorder,
				':datetime' => $datetime,
				':id' => $_POST['id']
			));
		}

		$sth = $this->db->prepare("SELECT * FROM categories WHERE id=:id");
		$sth->execute(array(
			':id' => $_POST['id']
		));
		$result = $sth->fetch();
		if ($result['name'] == $_POST['category'] and $result['description'] == $description) {

			if ($itemsorder <= $tableItemsCount) {

				if ($itemsorder > $prevOrder) {

					$itemsToShift = $this->getFromDB('SELECT * FROM categories WHERE itemsorder <= ? AND itemsorder > ?', [$itemsorder, $prevOrder]);

					for ($i=0; $i < count($itemsToShift); $i++) {
						if ($itemsToShift[$i]['id'] != $_POST['id']) {
							$sth = $this->db->prepare("UPDATE categories SET itemsorder = itemsorder - 1 WHERE id = ?");
							$sth->execute([$itemsToShift[$i]['id']]);
						}
					}
				} else {

					$itemsToShift = $this->getFromDB('SELECT * FROM categories WHERE itemsorder < ? AND itemsorder >= ?', [$prevOrder, $itemsorder]);

					for ($i=0; $i < count($itemsToShift); $i++) {
						if ($itemsToShift[$i]['id'] != $_POST['id']) {
							$sth = $this->db->prepare("UPDATE categories SET itemsorder = itemsorder + 1 WHERE id = ?");
							$sth->execute([$itemsToShift[$i]['id']]);
						}
					}
				}
			}

			$answer = array(
				'answer' => 'good',
				'name' => $result['name'],
				'description' => $result['description'],
				'id' => $result['id'],
				'logo' => $result['logo'],
				'datetime' => $result['datetime']
			);
			echo json_encode($answer);
			exit();
		} else {
			$answer = array(
				'answer' => 'error'
			);
			echo json_encode($answer);
			exit();
		}
	}

	private $quantityOnPage = 10;
	public function getBids($pn = 1, $type) {        
        $start = ($pn - 1) * $this->quantityOnPage;

        if ($type == 'client')
            $sth = $this->db->prepare("SELECT * FROM bids ORDER BY id DESC LIMIT ".$start.", ".$this->quantityOnPage);
        elseif ($type == 'partner')
            $sth = $this->db->prepare("SELECT * FROM partnerBids ORDER BY id DESC LIMIT ".$start.", ".$this->quantityOnPage);
        $sth->execute(array($type));
        return $sth;
    }
    public function getConfig ($name) {
        $sth = $this->db->prepare("SELECT value FROM configs WHERE name = ?");
        $sth->execute(array($name));
        return $sth;
    }
    public function getPagesQuantity($type) {
        if ($type == 'client')
            $sth = $this->db->prepare("SELECT * FROM bids");
        /*elseif ($type == 'partner')
            $sth = $this->db->prepare("SELECT * FROM partnerBids");
        elseif ($type == 'partners')
            $sth = $this->db->prepare("SELECT * FROM partners");*/
        $sth->execute(array($type));
        $count = ceil($sth->rowCount() / $this->quantityOnPage);
        return $count;
    }
    public function editBid($inp_id) {
        if (!isset($_POST['data'])) {
            $answer = array(
                'status' => 'empty'
            );
            echo json_encode($answer);
            exit();
        }

        $data = json_decode($_POST['data']);

        $status = $data->status;
        $id = $data->id;
        $name = $data->name;
        $phone = $data->phone;
        $email = $data->email;
        $bd = $data->birthday;
        $partner = $data->partner;
        $note = $data->note;

        if ($id != $inp_id) {
            $answer = array(
                'status' => 'error_compl'
            );
            echo json_encode($answer);
            exit();
        }

        $sth = $this->db->prepare("UPDATE bids SET name=:name, phone=:phone, email=:email, birthday=:bd, partner=:partner, status=:status, note=:note WHERE id=:id");
        $sth->execute(array(
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':bd' => $bd,
                ':partner' => $partner,
                ':status' => $status,
                ':note' => $note,
                ':id' => $id
            ));

        $sth = $this->db->prepare("SELECT * FROM bids WHERE id = ?");
        $sth->execute(array($id));
            //$count = $sth->rowCount();
        $newValues = $sth->fetch();
        if ($newValues['status'] == $status) {
            $answer = array(
                'status' => 'completed',
                'client_status' => $newValues['status'],
                //'name' => $newValues['name'],
                'id' => $id
            );
            echo json_encode($answer);
            exit();
        } else {
            $answer = array(
                'status' => 'error'
            );
                echo json_encode($answer);
                exit();
            }
    }




    public function loadImages() {

        if (!isset($_FILES['siteImages']['tmp_name'][0])) {
            $answer = array(
                'status' => 'empty'
            );
            return json_encode($answer);
            exit();
        }

        $photos = $this->photoMaster('public/images/siteImages/', 'siteImages', 30);
        //print_r ($photos);
        //exit();

        if (count($photos) == 0 or $photos[0] == '') {
            $answer = array(
                'status' => 'empty'
            );
            return json_encode($answer);
            exit();
        }

        if (!isset($_POST['images-description']) or $_POST['images-description'] == '')
            $descr = '';
        else
            $descr = $_POST['images-description'];

        //print_r($photos);
        //exit();
        $datetime = date("Y-m-d H:i:s", time());
        //$urlLenght = strlen(URL);

        for ($i = 0; $i < count($photos); $i++) {
            $sth = $this->db->prepare("INSERT INTO images (url, datetime, description) VALUES (:url, :datetime, :descr)");
            $sth->execute(array(
                ':url' => "/".$photos[$i],
                ':datetime' => $datetime,
                ':descr' => $descr
            ));
        }

        $sth = $this->db->prepare("SELECT id FROM images WHERE url = ?");
        $sth->execute(array('/'.$photos[0]));
        $count = $sth->rowCount();

        if ($count > 0 and file_exists($photos[0])) {
            $answer = array(
                'status' => 'completed'
            );
            return json_encode($answer);
            exit();
        } else {
            if ($count == 0) {
                $answer = array(
                    'status' => 'error'
                );
                if (1/*$photos[0] != $default_photo*/) {
                    $urlLenght = strlen(URL);
                    for ($i=0; $i < count($photos); $i++) {
                        $logo = $photos[$i];
                        unlink($logo);
                    }
                }
                return json_encode($answer);
                exit();
            } elseif (!file_exists($photos[0])) {
                $answer = array(
                    'status' => 'error'
                );
                for ($i=0; $i < count($photos); $i++) {
                    $sth = $this->db->prepare("DELETE FROM images WHERE url = ?");
                    $sth->execute(array('/'.$photos[$i]));
                    $count = $sth->rowCount();
                }
                return json_encode($answer);
                exit();
            }
        }


        exit();
    }
    public function imageController() {
        if (!isset($_POST['data'])) {
            $data = $_POST;
        } else {
            $data = json_decode($_POST['data']);
        }
        //print_r($data);

        switch ($data->action) {
            case 'updateDescr':
                $sth = $this->db->prepare("SELECT id FROM images WHERE id = ?");
                $sth->execute(array($data->id));
                $count = $sth->rowCount();
                if ($count < 1) {
                    $answer = array(
                        'status' => 'notFounded'
                    );
                    echo json_encode($answer);
                    exit();
                }

                $sth = $this->db->prepare("UPDATE images SET description = ? WHERE id = ?");
                $sth->execute(array($data->comment, $data->id));

                $sth = $this->db->prepare("SELECT id FROM images WHERE id = ? AND description = ?");
                $sth->execute(array($data->id, $data->comment));
                $count = $sth->rowCount();
                if ($count < 1) {
                    $answer = array(
                        'status' => 'notFounded'
                    );
                    echo json_encode($answer);
                    exit();
                } else {
                    $answer = array(
                        'status' => 'completed'
                    );
                    echo json_encode($answer);
                    exit();
                }
                break;

            case 'delete':
                $sth = $this->db->prepare("SELECT * FROM images WHERE id = ?");
                $sth->execute(array($data->id));
                $count = $sth->rowCount();
                if ($count < 1) {
                    $answer = array(
                        'status' => 'notFounded'
                    );
                    echo json_encode($answer);
                    exit();
                }

                $sth = $sth->fetch();
                $url = substr($sth['url'], 1);
                if (file_exists($url)) {
                    unlink($url);
                }

                $sth = $this->db->prepare("DELETE FROM images WHERE id = ?");
                $sth->execute(array($data->id));

                $sth = $this->db->prepare("SELECT * FROM images WHERE id = ?");
                $sth->execute(array($data->id));
                $count = $sth->rowCount();
                if ($count < 1) {
                    $answer = array(
                        'status' => 'completed',
                        'id' => $data->id
                    );
                    echo json_encode($answer);
                    exit();
                } else {
                    $answer = array(
                        'status' => 'error'
                    );
                    echo json_encode($answer);
                    exit();
                }

                break;
            
            default:
                $answer = array(
                    'status' => 'error'
                );
                echo json_encode($answer);
                exit();
                break;
        }

        exit();
    }


    public function addService() {
        if (!isset($_POST['data'])) {
            $data = $_POST;
        } else {
            $data = json_decode($_POST['data']);
        }

        $sth = $this->db->prepare("SELECT link FROM news WHERE link = ?");
        $sth->execute(array($data->service_url));
        $count_ch = $sth->rowCount();
        if ($count_ch > 0) {
            $answer = array(
                'status' => 'alreadyRegistered'
            );
            echo json_encode($answer);
            exit();
        }

        $sth = $this->db->prepare("INSERT INTO news (title, image, link, article) VALUES (:title, :image, :link, :article)");
        $sth->execute(array(
                ':title' => $data->service_title,
                ':image' => $data->service_image,
                ':link' => $data->service_url,
                ':article' => $data->article
            ));

        $sth = $this->db->prepare("SELECT id, title, image, link FROM news WHERE title = ? AND article = ? AND link = ?");
        $sth->execute(array($data->service_title, $data->article, $data->service_url));
            //$count = $sth->rowCount();
        $chIns = $sth->fetch();
        if ($chIns['title'] == $data->service_title) {
            $answer = array(
                'status' => 'completed',
                'id' => $chIns['id'],
                'title' => $data->service_title,
                'image' => $chIns['image'],
                'link' => $chIns['link']
            );
            echo json_encode($answer);
            exit();
        } else {
            $answer = array(
                'status' => 'error'
            );
            echo json_encode($answer);
            exit();
        }
    }

    public function editService() {
        if (!isset($_POST['data'])) {
            $data = $_POST;
        } else {
            $data = json_decode($_POST['data']);
        }

        $sth = $this->db->prepare("SELECT id FROM news WHERE id = ?");
        $sth->execute(array($data->id));
        $count_ch = $sth->rowCount();
        if ($count_ch == 0) {
            $answer = array(
                'status' => 'notFounded'
            );
            echo json_encode($answer);
            exit();
        }

        $sth = $this->db->prepare("UPDATE news SET title = :title, image = :image, link = :link, article = :article WHERE id = :id");
        $sth->execute(array(
                ':title' => $data->service_title,
                ':image' => $data->service_image,
                ':link' => $data->service_url,
                ':article' => $data->article,
                ':id' => $data->id
            ));

        $sth = $this->db->prepare("SELECT id, title, link FROM news WHERE title = ? AND article = ? AND link = ? AND id = ?");
        $sth->execute(array($data->service_title, $data->article, $data->service_url, $data->id));
            //$count = $sth->rowCount();
        $chIns = $sth->fetch();
        if ($chIns['title'] == $data->service_title) {
            $answer = array(
                'status' => 'completed',
                'id' => $chIns['id'],
                'title' => $data->service_title,
                'link' => $chIns['link']
            );
            echo json_encode($answer);
            exit();
        } else {
            $answer = array(
                'status' => 'error'
            );
            echo json_encode($answer);
            exit();
        }
    }

    public function deleteService() {
        if (!isset($_POST['data'])) {
            $data = $_POST;
        } else {
            $data = json_decode($_POST['data']);
        }

        $sth = $this->db->prepare("SELECT id FROM news WHERE id = ?");
        $sth->execute(array($data->id));
        $count_ch = $sth->rowCount();
        if ($count_ch == 0) {
            $answer = array(
                'status' => 'notFounded'
            );
            echo json_encode($answer);
            exit();
        }

        $sth = $this->db->prepare("DELETE FROM news WHERE id = :id");
        $sth->execute(array(
                ':id' => $data->id
            ));

        $sth = $this->db->prepare("SELECT id FROM news WHERE id = ?");
        $sth->execute(array($data->id));
        $count = $sth->rowCount();
        //$chIns = $sth->fetch();
        if ($count == 0) {
            $answer = array(
                'status' => 'completed'
            );
            //Header('Location: '.URL.'adminpanel/settings/news');
            echo json_encode($answer);
            exit();
        } else {
            $answer = array(
                'status' => 'error'
            );
            echo json_encode($answer);
            exit();
        }
    }



    public function editSitePage() {
        if (!isset($_POST['data'])) {
            $data = $_POST;
        } else {
            $data = json_decode($_POST['data']);
        }

        $prod = ["<script", "</script"];
		$edit = ["[script", "[/script"];
		$data->pageText = str_replace($edit, $prod, $data->pageText);
		$data->pageText = htmlspecialchars_decode($data->pageText);

        $sth = $this->db->prepare("UPDATE settings SET paramValue = :pv WHERE paramName = :pn");
        $sth->execute(array(
            ':pv' => $data->pageText,
            ':pn' => $data->pageName
        ));

        $sth = $this->db->prepare("SELECT * FROM settings WHERE paramName = ? AND paramValue = ?");
        $sth->execute([$data->pageName, $data->pageText]);
        $count = $sth->rowCount();
        if ($count > 0) {
            $answer = array(
                'status' => 'completed'
            );
            echo json_encode($answer);
            exit();
        } else {
            $answer = array(
                'status' => 'error'
            );
            echo json_encode($answer);
            exit();
        }
    }

    public function editSiteConstants() {
    	//$needableConstants = ["siteName", "siteLogoLink", "socialNetworkLinks"];
    	if (!isset($_POST['data'])) {
            $data = $_POST;
        } else {
            $data = json_decode($_POST['data']);
        }

        $errstatus = 0;
        $errstring = "";

        //print_r($data);

        foreach ($data as $key => $value) {

        	$sth = $this->db->prepare("UPDATE settings SET paramValue=? WHERE paramName=?");
			$sth->execute(array($value, $key));

			$sth = $this->getFromDBOne("SELECT paramName FROM settings WHERE paramValue = ? AND paramName = ?", [$value, $key]);
			if ($sth['paramName'] != $key) {
				$errstatus = 1;
				if ($errstring == "") $errstring .= $key;
				else $errstring .= ', '.$key;
			}
        }

        if (!$errstatus) {
            $answer = array(
                'status' => 'completed'
            );
            echo json_encode($answer);
            exit();
        } else {
            $answer = array(
                'status' => 'error',
                'comment' => $errstring
            );
            echo json_encode($answer);
            exit();
        }
    }


}