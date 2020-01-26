<?php

	class Admin extends Controller
	{
		
		function __construct()
		{
			parent::__construct();
	      	Session::init();
	      	$logged = Session::get('loggedIn');
	      	if($logged == false) {
		      	Session::destroy();
		      	header('Location: ../login');
		      	exit();
	     	}
	     	if (Session::get('role') != 'owner' and Session::get('role') != 'admin') {
				header('Location: ../');
		      	exit();
			}
		}

		public function index() {
			$this->view->title = 'Панель администратора';
			$this->view->pageName = 'Админ';
			$this->view->pageType = 'admin';
			$this->view->render('admin/index');
		}

		public function add() {
			$this->view->title = 'Панель администратора - добавление товара';
			$this->view->pageName = 'Добавление';
			$this->view->pageType = 'admin';
			$this->view->addProductStatus = $this->model->addProduct();
			$this->view->render('admin/add');
		}

		public function settings($param = 'menu') {
			$this->view->js = array('admin/js/settings.js');
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->view->pageName = 'Настройки';
			$this->view->pageType = 'admin';

			if ($param == 'menu') {
				$this->view->param = 'menu';
				$this->view->title = 'Панель администратора - настройки сайта';
			}

			if ($param == 'manufacturers') {
				$this->view->param = 'manufacturers';
				$this->view->manufacturers = $this->model->getManufacturers();
				$this->view->title = 'Панель администратора - настройки производителей';
			}

			if ($param == 'categories') {
				$this->view->param = 'categories';
				$this->view->categories = $this->model->getCategories();
				$this->view->title = 'Панель администратора - настройки категорий';
			}

			if ($param == 'delivery' or $param == 'about' or $param == 'contacts') {
				$this->view->param = $param;

				$this->view->pageText = $this->model->getFromDBOne("SELECT paramValue FROM settings WHERE paramName = ?", [$param.'Page']);
				$prod = ["<script", "</script"];
				$edit = ["[script", "[/script"];
				$this->view->pageText = str_replace($prod, $edit, $this->view->pageText);
				$this->view->pageText['paramValue'] = htmlspecialchars($this->view->pageText['paramValue']);

				$this->view->title = 'Панель администратора - настройки страниц сайта';
			}

			if ($param == 'site-constants') {
				$this->view->css = ['admin/css/main'];
				$this->view->param = 'site-constants';
				//$this->view->siteConstants = $this->model->getSiteConstants(["siteName", "siteLogoLink", "socialNetworkLinks", "phones", "post"]);
				$this->view->title = 'Панель администратора - настройки сайта';
				$this->view->fieldsParams = SITE_CONSTANT_LIST_DESCR;
			}


			$this->view->render('admin/settings');
		}

		public function bids() {
			$this->view->title = 'Панель администратора - входящие заявки';
			$this->view->pageName = 'Настройки';
			$this->view->pageType = 'admin';

			$this->view->bids = $this->model->getBids(1, 'client');
			$this->view->statusVariants = $this->model->getConfig('bidsStatuses');

			$this->view->pagesQuant = $this->model->getPagesQuantity('client');
			$this->view->currPage = 1;

			$this->view->render('admin/bids');
		}
		public function editBid($id = 0) {
			$this->model->editBid($id);
		}

		public function addManufacturer() {
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->model->addSetting('manufacturer');
		}

		public function addCategory() {
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->model->addSetting('category');
		}

		public function deleteManufacturer() {
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->model->deleteManufacturer();
		}

		public function deleteCategory() {
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->model->deleteCategory();
		}

		public function deleteProduct() {
			$this->model->deleteProduct();
		}

		public function editManufacturer() {
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->model->editManufacturer();
		}

		public function editCategory() {
			if (Session::get('role') != 'owner') {
				header('Location: ../');
		      	exit();
			}
			$this->model->editCategory();
		}

		public function manufacturerChooser() {
			$this->model->manufacturerChooser();
		}

		public function categoryChooser() {
			$this->model->categoryChooser();
		}

		public function productChooser() {
			$this->model->productChooser();
		}

		public function products($id = false) {
			$this->view->js = array('admin/js/products.js');
			if (Session::get('role') == 'admin') {
				$this->view->title = 'Панель администратора - список добавленных мной продуктов';
			} elseif (Session::get('role') == 'owner') {
				$this->view->title = 'Панель администратора - список добавленных продуктов';
			}
			$this->view->editProductStatus = $this->model->editProduct($id);

			$this->view->categories = $this->model->getCategories();
			//$this->view->manufacturers = $this->model->getManufacturers();
			$this->view->pageName = 'Список продуктов';
			$this->view->pageType = 'admin';
			if ($id) {
				$this->view->productId = $id;
			}
			$this->view->render('admin/products');
		}

		public function loadProducts($by) {
			$this->model->loadProducts($by);
		}

		public function getProduct() {
			$this->model->getProduct();
		}

		public function deleteItemPhoto() {
			$this->model->deleteItemPhoto();
		}


		public function imagesManager() {
			$this->view->title = 'Панель администратора - менеджер изображений';
			$this->view->logged = true;
			$this->view->pageName = 'Менеджер изображений';
			$this->view->pageType = 'admin';

			$this->view->status = $this->model->loadImages();
			$this->view->images = $this->model->getTableData('images');

			$this->view->css = ['admin/css/main'];
			$this->view->render('admin/imagesManager');
		}
		public function imageController() {
			$this->model->imageController();
		}

		public function feedbacks() {
			$this->view->title = 'Панель администратора - отзывы';
			$this->view->logged = true;
			$this->view->pageName = 'Отзывы';
			$this->view->pageType = 'admin';

			$quantityOnPage = 10;
			if (isset($_GET['rowOnPage'])) {
				$quantityOnPage = $_GET['rowOnPage'];
				if ($quantityOnPage <= 0) $quantityOnPage = 1;
			}

			$page = 1;
			if (isset($_GET['page'])) {
				$page = $_GET['page'];
				if ($page <= 0) $page = 1;
			}

			$this->view->pagesQuant = ceil($this->model->getNumberOfRows('feedbacks') / $quantityOnPage);
			$this->view->currPage = $page;

			$start = ($page - 1) * $quantityOnPage;
			$this->view->feedbacks = $this->model->getFromDB("SELECT * FROM feedbacks ORDER BY id DESC LIMIT ".$start.", ".$quantityOnPage);

			$this->view->render('admin/feedbacks');
		}


		public function news($news_link = '') {
			//$news_link = $this->model->getUri();
			//if (isset($news_link))
			if (isset($news_link))
				$news = $this->model->getSql("SELECT * FROM news WHERE link = ?", array($news_link));
			
			//print_r($news[0]['link']);
			if (isset($news_link) and $news_link != '' and $news[0]['link'] != '') {
				$this->view->title = 'Панель администратора — новость: '.$news[0]['title'];
				$this->view->logged = true;
				$this->view->pageName = 'Новость: '.$news[0]['title'];
				$this->view->pageType = 'admin';

				$this->view->news = $news;

				$this->view->css = ['admin/css/main'];
				$this->view->render('admin/news');
			} else {
				$this->view->title = 'Панель администратора — настройки новостей';
				$this->view->logged = true;
				$this->view->pageName = 'Настройки новостей';
				$this->view->pageType = 'admin';

				$this->view->landingData = $this->model->getTableData('news');

				$this->view->css = ['admin/css/main'];				
				$this->view->render('admin/news_settings');
			}
			
		}
		public function addService() {
			$this->model->addService();
		}

		public function editService() {
			$this->model->editService();
		}

		public function deleteService() {
			$this->model->deleteService();
		}


		public function editSitePage() {
			$this->model->editSitePage();
		}

		public function editSiteConstants() {
			$this->model->editSiteConstants();
		}

	}