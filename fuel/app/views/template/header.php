<ul class="sidenav" id="slide-out">
	<?php if (Auth::check()): ?>
	<li>
		<div class="user-view">
			<div class="background">
				<?= Asset::img('menubg.png', ['alt' => 'background', 'style' => 'max-width: 100%;']); ?>
			</div>
			<?= Html::anchor('/users/' . Auth::get_screen_name(), Asset::img('https://secure.gravatar.com/avatar/baef5cefc09865a4f2e89bee37832559', ['class' => 'circle'])); ?>
			<span class="white-text name"><?= Auth::get_profile_fields('screen_name'); ?> (<?= Auth::get_screen_name(); ?>)</span>
			<span class="white-text email"><?= Auth::get_email(); ?></span>
		</div>
	</li>
	<?php endif; ?>
	<li><?= Html::anchor('/', '<i class="material-icons">home</i>TOP'); ?></li>
	<li><?= Html::anchor('/cards', '<i class="material-icons">find_in_page</i>カード'); ?></li>
	<li><?= Html::anchor('/users', '<i class="material-icons">people</i>メンバー'); ?></li>
	<li><div class="divider"></div></li>
	<?php if (Auth::check()): ?>
	<li><?= Html::anchor('/home', 'マイページ'); ?></li>
	<?php else: ?>
	<li><?= Html::anchor('/login', 'ログイン'); ?></li>
	<?php endif; ?>
</ul>
<header class="navbar-fixed">
	<nav>
		<div class="nav-wrapper">
			<div class="hide-on-med-and-down left">
				<?= Html::anchor('/', 'ぶらつき学生ポータル', ['class' => 'breadcrumb']); ?>
				<?php foreach ($breadcrumbs ?? [] as $uri => $name): ?>
				<?= Html::anchor($uri, $name, ['class' => 'breadcrumb']); ?>
				<?php endforeach; ?>
			</div>
			<?= Html::anchor(Input::uri(), $title ?? '', ['class' => 'hide-on-large-only', 'style' => 'font-size: 1.3em;']); ?>
			<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
			<ul class="right hide-on-med-and-down">
				<li><?= Html::anchor('/cards', 'カード'); ?></li>
				<li><?= Html::anchor('/users', 'メンバー'); ?></li>
				<?php if (Auth::check()): ?>
				<li><?= Html::anchor('/home', 'マイページ'); ?></li>
				<?php else: ?>
				<li><?= Html::anchor('/login', 'ログイン'); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</nav>
</header>