<ul class="sidenav" id="slide-out">
	<li><?= Html::anchor('/test', 'テスト'); ?></li>
</ul>
<header class="navbar-fixed">
	<nav>
		<div class="nav-wrapper">
			<div class="hide-on-med-and-down left">
				<?= Html::anchor('/', 'ぶらつき学生ポータル', ['class' => 'breadcrumb']); ?>
				<?php if (Input::uri() !== '') :?>
					<?php if (isset($section) and isset(Uri::segments()[0])): ?>
						<?= Html::anchor(Uri::segments()[0], $section, ['class' => 'breadcrumb']); ?>
					<?php endif; ?>
					<?= Html::anchor(Input::uri(), $title ?? '', ['class' => 'breadcrumb']); ?>
				<?php endif; ?>
			</div>
			<?= Html::anchor(Input::uri(), $title ?? '', ['class' => 'hide-on-large-only', 'style' => 'font-size: 1.3em;']); ?>
			<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
			<ul class="right hide-on-med-and-down">
				<li><?= Html::anchor('test', 'テスト'); ?></li>
			</ul>
		</div>
	</nav>
</header>