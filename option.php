<?
	
	// Подключаемся к БД
	@require_once ($_SERVER['DOCUMENT_ROOT'] . '/php/mysqli.php');
	$db = DataBase::getDB();

	// Выбор опции
	$where = isset($_GET['id']) ? 'WHERE o.option_id = ' . intval($_GET['id']) : '';
	$query = "SELECT o.option_name, o.text, o.option_id
				FROM options o
				$where
				LIMIT 1";

	$info = $db->select($query);
	$caption = $info[0]['option_name'];
	$text = $info[0]['text'];

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
						<li><a class="active" href="/options.php">Опции</a></li>
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
							<li><a href="/options.php" class="tr" title="Опции">Опции</a></li>
							<li><span><?= $caption ?></span></li>
						</ul>
					</div>
				</div>

				<div class="inner">
					<div class="textblock">
						<?= $text ?>
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
		
		<script src="/js/script.min.js?<?= filemtime($_SERVER['DOCUMENT_ROOT'].'/js/script.min.js') ?>"></script>
	</body>
</html>

