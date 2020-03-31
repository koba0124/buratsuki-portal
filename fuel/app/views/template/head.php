<head>
	<meta charset="utf-8">
	<title><?= $title ?? ''; ?> | ほら吹き社会人ポータル</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="msapplication-config" content="/browserconfig.xml">
	<meta name="robots" content="noindex">
	<meta name="theme-color" content="#ff9800">
	<!-- OGP [ -->
	<meta property="og:title" content="<?= $title ?? ''; ?> | ほら吹き社会人ポータル">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?= Uri::current(); ?>">
	<meta property="og:site_name" content="ほら吹き社会人ポータル">
	<meta property="og:description" content="<?= $description ?? '戦績管理サイトです。拡張入りアグリコラのプレイ結果を記録しています。'; ?>">
	<?php if (isset($ogp_image_large)): ?>
	<meta property="twitter:card" content="summary_large_image">
	<meta property="og:image" content="<?= Asset::get_file($ogp_image_large, 'img'); ?>">
	<?php elseif (isset($ogp_image)): ?>
	<meta property="twitter:card" content="summary">
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
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Noto+Sans+JP">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<?= Asset::css('app.css', [], null, true); ?>
	<?= Asset::render('add_css', true); ?>
</head>