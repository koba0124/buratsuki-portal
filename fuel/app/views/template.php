<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?= $title ?? ''; ?></title>
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
<?= Asset::render('add_js'); ?>
</body>
</html>