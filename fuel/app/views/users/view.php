<div class="container">
	<h1 class="orange-text">
		<?= $user_data['screen_name'] ?>
	</h1>
	<div class="row">
		<div class="col s4 m3">
			<?= Asset::img($user_data['icon'], ['alt' => 'icon', 'class' => 'responsive-img circle']); ?>
		</div>
		<div class="col s12 m9">
			<dl>
				<dt class="teal-text">ユーザID</dt>
				<dd><?= $user_data['username']; ?></dd>
				<?php if (! empty($user_data['twitter'] ?? null)): ?>
				<dt class="teal-text">Twitter</dt>
				<dd><?= Html::anchor('https://twitter.com/'.$user_data['twitter'], '@'.$user_data['twitter'], ['target' => '_blank', 'rel' => 'noopener']); ?></dd>
				<?php endif; ?>
				<?php if (! empty($user_data['comment'] ?? null)): ?>
				<dt class="teal-text">ひとこと</dt>
				<dd><?= nl2br($user_data['comment']); ?></dd>
				<?php endif; ?>
				<dt class="teal-text">平均点</dt>
				<dd>
					<?php if (isset($score_average_data[0])): ?>
					<span style="margin-right: 1em;">通常: <?= sprintf('%.2f', $score_average_data[0]); ?>点</span>
					<?php endif; ?>
					<?php if (isset($score_average_data[1])): ?>
					<span>泥沼: <?= sprintf('%.2f', $score_average_data[1]); ?>点</span>
					<?php endif; ?>
				</dd>
				<dt class="teal-text">平均順位</dt>
				<dd>
					<?php foreach ($rank_average_data as $key => $value): ?>
					<span style="margin-right: 1em;"><?= $key; ?>グリ: <?= sprintf('%.2f', $value); ?>位</span>
					<?php endforeach; ?>
				</dd>
			</dl>
			<?= Html::anchor('/statistics/user/' . $user_data['username'], '統計詳細を見る >>'); ?>
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
					<span class="new <?= Model_CardsMaster::get_type($card); ?>-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php if ($user_data['username'] === Auth::get_screen_name()): ?>
	<p class="right-align"><?= Html::anchor('/home/edit_profile', '編集する'); ?></p>
	<?php endif; ?>
	<h2 class="teal-text" id="games">参加したゲーム</h2>
	<div class="collection">
		<?php foreach ($games_list as $record): ?>
		<a class="collection-item" href="<?= Uri::create('games/view/:game_id', ['game_id' => $record['game_id']]); ?>">
			<?= $record['players_number']; ?>人ゲーム / <?= $record['regulation_name']; ?><?php if ($record['is_moor']) echo '(泥沼)'; ?><br>
			<?= $record['player_order']; ?>番手 / <?= $record['total_points']; ?>点 / <?= $record['rank']; ?>位<br>
			[<?= $record['created_at']; ?>]
		</a>
		<?php endforeach; ?>
	</div>
	<?= Pagination::instance('games')->render(); ?>
</div>