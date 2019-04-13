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
	<title><?= $title ?? ''; ?> | ぶらつき学生ポータル</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-config" content="/browserconfig.xml">
	<meta name="theme-color" content="#ff9800">
	<?= Html::meta('description', $description ?? '東京工業大学アグリコラサークル「ぶらつき学生連盟」の戦績管理ツールです。拡張入りアグリコラのプレイ結果を記録しています。'); ?>
	<meta name="author" content="東京工業大学アグリコラサークル「ぶらつき学生連盟」">
	<!-- OGP [ -->
	<meta property="og:title" content="<?= $title ?? ''; ?> | ぶらつき学生ポータル">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?= Uri::current(); ?>">
	<meta property="og:site_name" content="ぶらつき学生ポータル">
	<meta property="og:description" content="<?= $description ?? '東京工業大学アグリコラサークル「ぶらつき学生連盟」の戦績管理ツールです。拡張入りアグリコラのプレイ結果を記録しています。'; ?>">
	<?php if (isset($ogp_image)): ?>
	<meta property="twitter:card" content="summary_large_image">
	<meta property="og:image" content="<?= Asset::get_file($ogp_image, 'img'); ?>">
	<?php else: ?>
	<meta property="twitter:card" content="summary">
	<meta property="og:image" content="<?= Uri::create('/apple-touch-icon.png'); ?>">
	<?php endif; ?>
	<!-- OGP ] -->
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#f59b35">
	<link rel="shortcut icon" href="/favicon.ico">
	<?= Asset::css('app.css'); ?>
	<?= Asset::render('add_css'); ?>
</head>