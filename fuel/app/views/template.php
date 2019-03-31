<!DOCTYPE html>
<html lang="ja">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-137369865-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-137369865-1');
	</script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-config" content="/browserconfig.xml">
	<meta name="theme-color" content="#ff9800">
	<title><?= $title ?? ''; ?> | ぶらつき学生ポータル</title>
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#f59b35">
	<link rel="shortcut icon" href="/favicon.ico">
	<?= Asset::css('app.css'); ?>
	<?= Asset::render('add_css'); ?>
</head>
<body class="grey lighten-5">
<?= View::render('template/header'); ?>
<main>
<?= $content ?? ''; ?>
</main>
<?= View::render('template/footer'); ?>
<?= Asset::js('materialize.js'); ?>
<?= Asset::js('template.js'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
	<?php $errors = array_merge((array) ($errors ?? []), (array) Session::get_flash('errors', [])); ?>
	<?php foreach ($errors as $error): ?>
		M.toast({html: '<?= $error ?>', classes: 'red'});
	<?php endforeach; ?>
	<?php $messages = array_merge((array) ($messages ?? []), (array) Session::get_flash('messages', [])); ?>
	<?php foreach ($messages as $message): ?>
		M.toast({html: '<?= $message ?>', classes: 'teal'});
	<?php endforeach; ?>
	});
</script>
<?= Asset::render('add_js'); ?>
</body>
</html>