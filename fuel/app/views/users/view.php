<div class="container">
	<h1 class="orange-text">
		<?= $user_data['screen_name'] ?>
	</h1>
	<div class="row">
		<div class="col s12 m4">
			プロフィール画像
		</div>
		<div class="col s12 m8">
			<dl>
				<dt class="teal-text">ユーザID</dt>
				<dd><?= $user_data['username']; ?></dd>
				<?php if (isset($user_data['twitter'])): ?>
				<dt class="teal-text">Twitter</dt>
				<dd><?= Html::anchor('https://twitter.com/'.$user_data['twitter'], '@'.$user_data['twitter'], ['target' => '_blank', 'rel' => 'noopener']); ?></dd>
				<?php endif; ?>
				<?php if (isset($user_data['comment'])): ?>
				<dt class="teal-text">ひとこと</dt>
				<dd><?= nl2br($user_data['comment']); ?></dd>
				<?php endif; ?>
			</dl>
		</div>
	</div>
	<div class="row">
		<div class="col s12 l6">
			<h3 class="occupation-text">好きな職業</h3>
			<div class="collection">
				<?php foreach ($occupations as $card): ?>
				<a href="<?= Uri::create('cards/view/:id', ['id' => $card['card_id']]); ?>" class="collection-item">
					<?= $card['japanese_name']; ?>
					<span class="new occupation-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col s12 l6">
			<h3 class="minor_improvement-text">好きな小進歩</h3>
			<div class="collection">
				<?php foreach ($minor_improvements as $card): ?>
				<a href="<?= Uri::create('cards/view/:id', ['id' => $card['card_id']]); ?>" class="collection-item">
					<?= $card['japanese_name']; ?>
					<span class="new minor_improvement-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php if ($user_data['username'] === Auth::get_screen_name()): ?>
	<p class="right-align"><?= Html::anchor('/users/edit', '編集する'); ?></p>
	<?php endif; ?>
</div>