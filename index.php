<?
	
	// Подключаемся к БД
	@require_once ($_SERVER['DOCUMENT_ROOT'] . '/php/mysqli.php');
	$db = DataBase::getDB();

	// Пагинация
	@require_once ($_SERVER['DOCUMENT_ROOT'] . '/php/pagination.class.php');
    $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;
    $pagination = (new Pagination());
    $pagination->setCurrent($page);

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
						<li><a href="/catalog.php">Авто</a></li>
						<li><a href="/options.php">Опции</a></li>
						<li><a href="/clients.php">Клиенты</a></li>
						<li><a href="/contacts.php">Контакты</a></li>
					</ul>
				</nav>
			</header>

			<section id="content">
				<div class="fotorama" data-width="100%" data-max-height="500px" data-fit="cover" data-loop="true" data-autoplay="true">
					<img src="/images/slider1.jpg" alt="car1" />
					<img src="/images/slider2.jpg" alt="car2" />
					<img src="/images/slider3.jpg" alt="car3" />
				</div>

				<div class="inner">
					<div class="cars">
					<?
						// Выбор авто
						$query = "SELECT a.auto_name, a.auto_id, b.brand_name, m.model_name
									FROM auto a 
										LEFT JOIN models m ON (a.model_id = m.model_id)
										LEFT JOIN brands b ON (m.brand_id = b.brand_id)
									ORDER BY b.brand_name, m.model_name, a.auto_name
									LIMIT ".( $page>1 ? ($page*12).', 12' : 12);

						$table = $db->select($query);

						foreach ($table as $value) {
							$name = $value['brand_name'] . ' ' . $value['model_name'] . ' ' . $value['auto_name'];
							echo '	<a href="/car.php?id='. $value['auto_id'] .'" class="item">
										<span class="container">
											<span class="image"></span>
											<span class="name">'. $name .'</span>
										</span>
									</a>';
						}

					?>
						<div class="clear"></div>
					</div>
				</div>
			
				<nav class="navi">
					<?
						// Вывод пагинации
						$total = $db->selectCell('SELECT COUNT(*) FROM auto');
						$pagination->setTotal($total);
						echo $pagination->parse();
					?>
				</nav>
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
		
		<script src="/js/script.min.js?<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/js/script.min.js') ?>"></script>
	</body>
</html>

