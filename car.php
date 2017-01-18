<?
	
	// Подключаемся к БД
	@require_once ($_SERVER['DOCUMENT_ROOT'] . '/php/mysqli.php');
	$db = DataBase::getDB();

	// Данные авто
	$where = isset($_GET['id']) ? 'WHERE a.auto_id = ' . intval($_GET['id']) : '';
	$query = "SELECT a.auto_name, a.auto_id, a.price, a.date_from, a.date_to, b.brand_name, m.model_name
				FROM auto a 
					LEFT JOIN models m ON (a.model_id = m.model_id)
					LEFT JOIN brands b ON (m.brand_id = b.brand_id)
				$where
				LIMIT 1";

	$auto_info = $db->select($query);

	$auto_id = $auto_info[0]['auto_id'];
	$price = round($auto_info[0]['price'],-2);
	$brand = $auto_info[0]['brand_name'];
	$model = $auto_info[0]['model_name'];
	$name = $auto_info[0]['auto_name'];

	// Аренда
	if (isset($_POST['auto_id'])) {
		// Добавляем пользователя
		$query = "INSERT INTO users (user_id, name, phone, email) VALUES (NULL, '". $db->escape($_POST['name']) ."', '". $db->escape($_POST['phone']) ."', '". $db->escape($_POST['email']) ."')";
		$user_id = $db->query($query);

		// Таблица заказов
		$query = "INSERT INTO orders (order_id, user_id, auto_id, price, date) VALUES (NULL, ". intval($user_id) .", ". intval($_POST['auto_id']) .", ". intval($_POST['price']) .", NOW());";
		$db->query($query);

		// Ответ
		header('Content-Type: application/json');
		print(json_encode(array('text' => '<p class="center">Спасибо! Ваша заявка принята.</p>')));
		exit;
	}

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, target-densitydpi=device-dpi" name="viewport" />

		<link href="/favicon.ico" rel="icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link rel="icon" type="image/png" href="/favicon.png" />

		<title>Nomadcar</title>
		<meta name="keywords" content="Nomadcar" />
		<meta name="description" content="Nomadcar" />

		<link href="https://fonts.googleapis.com/css?family=Open+Sans|Russo+One&amp;subset=cyrillic" rel="stylesheet" />
		<link href="/css/style.css?<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/css/style.css') ?>" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="wrapper">

			<div id="header_protector"></div>

			<header id="header">
				<div class="inner clear_after">
					<a href="/" class="logo clear" title="logo"></a>

					<div class="toright">
						<div class="phone">
							<a href="tel:+79001234567" class="tel">+7 (900) 123-45-67</a>
						</div>
						<div class="menu-icon"><span></span></div>
						<nav class="social">
							<ul>
								<li><a target="_blank" href="https://www.fb.com/" class="fb"></a></li>
								<li><a target="_blank" href="https://vk.com/" class="vk"></a></li>
								<li><a target="_blank" href="https://twitter.com/" class="tw"></a></li>
							</ul>
						</nav>
					</div>
				</div>

				<nav class="menu">
					<ul>
						<li><a class="active" href="/catalog.php">Авто</a></li>
						<li><a href="/options.php">Опции</a></li>
						<li><a href="/clients.php">Клиенты</a></li>
						<li><a href="/contacts.php">Контакты</a></li>
					</ul>
				</nav>
			</header>

			<section id="content">

				<div class="breadcrumbs">
					<div class="inner">
						<ul>
							<li><a href="/" class="home tr" title="Главная">&nbsp;</a></li>
							<li><a href="/catalog.php" class="tr" title="Каталог">Каталог</a></li>
							<li><span><?= "$brand $model $name" ?></span></li>
						</ul>
					</div>
				</div>

				<div class="inner">
					<div class="car">
						<div class="image">
							
						</div>
						<div class="textblock">
							<ul>
								<li>Производитель: <?= $brand ?></li>
								<li>Модель: <?= $model ?></li>
								<li>Наименование: <?= $name ?></li>
								<li>Стоимость аренды: <?= number_format($price, 0, '', ' ') ?> Р</li>
								<?
									// Опции
									$options = array();
									$query = "SELECT o.option_name, ao.option_id
												FROM options o
													LEFT JOIN auto_option ao ON (o.option_id = ao.option_id )
												WHERE ao.auto_id = " . intval($_GET['id']);

									$table = $db->select($query);
								
									foreach ($table as $value) {
										$options[] = '<a href="/option.php?id='. $value['option_id'] .'">'. $value['option_name'] .'</a>';
									}

									echo count($options)>0 ? '<li>Статьи: '.join(', ', $options). '</li>' : '';
								?>
							</ul>
						</div>
						<a href="#" class="button js_rent">Аренда</a>
					</div>
				</div>
			</section>

			<div id="footer_protector"></div>

			<footer id="footer">
				<div class="inner">
					<nav class="menu">
						<ul>
							<li><a href="/catalog.php">Авто</a></li>
							<li><a href="/options.php">Опции</a></li>
							<li><a href="/clients.php">Клиенты</a></li>
							<li><a href="/contacts.php">Контакты</a></li>
						</ul>
					</nav>

					<div class="copy"><?= date('Y') ?> &copy; Nomadcar</div>
				</div>
			</footer>

		</div>

		<div id="shadow" class="white"></div>

		<? /* Аренда */ ?>
		<div class="popup rent">
			<a href="#" class="close tr"></a>
			<div class="caption">Аренда</div>

			<div class="info">
				<span>Авто</span><?= "$brand $model $name" ?><br/>
				<span>Цена</span><?= number_format($price, 0, '', ' ') ?> Р
			</div>

			<form action="" id="rent" method="post">
				<input type="hidden" name="auto_id" value="<?= $auto_id ?>" />
				<input type="hidden" name="price" value="<?= $price ?>" />
				<input type="text" class="input tr" placeholder="Ваше имя" required="required" name="name" />
				<input type="tel" class="input tr mask" placeholder="Телефон" required="required" name="phone" />
				<input type="email" class="input tr" placeholder="E-mail" required="required" name="email" />
				<input type="submit" class="input submit tr" value="Отправить" />
			</form>
		</div>
		<? /* Аренда */ ?>
		
		<script src="/js/script.min.js?<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/js/script.min.js') ?>"></script>
	</body>
</html>

