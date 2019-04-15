<!DOCTYPE html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<?= View::render('template/head'); ?>
<body class="grey lighten-5">
<?= View::render('template/header'); ?>
<main>
<?= $content ?? ''; ?>
</main>
<?= View::render('template/footer'); ?>
<script>
	requestAnimationFrame(function(e) {
		e = document.createElement('link');
		e.rel = 'stylesheet';
		e.href = '<?= Asset::get_file('app_async.css', 'css') ?>';
		document.head.appendChild(e);
	});
</script>
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