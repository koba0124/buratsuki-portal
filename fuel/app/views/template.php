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
<div style="display: none;"></div>
<?= Asset::js('materialize.js'); ?>
<?= Asset::js('template.js'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
	<?php $errors = $errors ?? []; ?>
	<?php foreach ($errors as $error): ?>
		M.toast({html: '<?= $error ?>', classes: 'red'});
	<?php endforeach; ?>
	<?php $messages = $messages ?? []; ?>
	<?php foreach ($messages as $message): ?>
		M.toast({html: '<?= $message ?>', classes: 'teal'});
	<?php endforeach; ?>
	});
</script>
<?= Asset::render('add_js'); ?>
</body>
</html>