<footer class="page-footer grey darken-1">
	<div class="container">
		<div class="row">
			<div class="col l6 s12">
				<h4 class="white-text">ほら吹き社会人ポータル</h4>
				<p class="grey-text text-lighten-4">身内のアグリコラ戦績管理サイトです。</p>
			</div>
			<div class="col l4 offset-l2 s12">
				<h5>
					<?= Html::anchor('about', '本サイトについて', ['class' => 'white-text']); ?>
				</h5>
				<ul>
					<li>
						<?= Html::anchor('about#browser', '推奨環境', ['class' => 'grey-text text-lighten-3']); ?>
					</li>
					<li>
						<?= Html::anchor('about#termsofuse', '利用規約', ['class' => 'grey-text text-lighten-3']); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			&copy; 2020 ほら吹き社会人
			<span class="grey-text text-lighten-4 right">v<?= Config::get('version'); ?></span>
		</div>
	</div>
</footer>