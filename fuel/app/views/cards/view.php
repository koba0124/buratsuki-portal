<div class="container">
	<h1 class="<?= $card['type']; ?>-text">
		<?= $card['japanese_name']; ?>
		<span class="new <?= $card['type']; ?>-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
	</h1>
	<dl>
		<dt class="teal-text">デッキ</dt>
		<dd><?= $card['deck_name']; ?></dd>
		<?php if ($card['type'] === 'occupation'): ?>
		<dt class="teal-text">カテゴリー</dt>
		<dd><?= $card['category'], '+'; ?></dd>
		<?php else: ?>
		<?php if (! empty($card['prerequisite'])): ?>
		<dt class="teal-text">前提</dt>
		<dd><?= $card['prerequisite']; ?></dd>
		<?php endif; ?>
		<?php if (! empty($card['costs'])): ?>
		<dt class="teal-text">コスト</dt>
		<dd><?= $card['costs']; ?></dd>
		<?php endif; ?>
		<?php if (! empty($card['card_points'])): ?>
		<dt class="teal-text">カード点</dt>
		<dd><?= $card['card_points']; ?>点</dd>
		<?php endif; ?>
		<?php endif; ?>
		<dt class="teal-text">説明</dt>
		<dd><?= nl2br($card['description']); ?></dd>
	</dl>
	<?php if (Auth::check()): ?>
	<?= Html::anchor('cards/review/' . $card['card_id'], 'カード評価を入力する'); ?>
	<?php endif; ?>
	<h2 class="teal-text">カード評価</h2>
	<div class="collection">
		<div class="collection-item">
			平均評価：<span style="font-size: 1.5rem; line-height: 2rem;"><?= $review_points_avg ?? '--' ?></span>点
		</div>
		<?php foreach ($review_data as $record): ?>
		<div class="collection-item avatar">
			<?= Html::anchor('users/view/' . $record['username'], Asset::img($record['icon'], ['alt' => 'icon', 'class' => 'circle'])); ?>
			<?= Html::anchor('users/view/' . $record['username'], $record['screen_name'] . ' (' . $record['username'] . ')'); ?>
			<div class="row">
				<div class="col s4 m2">
					<span class="grey-text">
						<span style="font-size: 1.5rem; line-height: 2rem;"><?= $record['review_points']; ?></span>点
					</span>
				</div>
				<div class="col s8 m10">
					<?= nl2br($record['review_comment']); ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php if ($card['type'] !== 'major_improvement'): ?>
	<h2 class="teal-text">使用されたゲーム</h2>
	<div class="collection">
		<div class="collection-item">
			1位回数：<span style="font-size: 1.5rem; line-height: 2rem;"><?= $count_rank_first ?? '--' ?></span>回 / <?= $count; ?>回
		</div>
		<?php foreach ($games_data as $record): ?>
		<a href="<?= Uri::create('games/view/:game_id#player:player_order', ['game_id' => $record['game_id'], 'player_order' => $record['player_order']]); ?>" class="collection-item avatar">
			<?= Asset::img($record['icon'] ?? 'noimage.png', ['alt' => 'icon', 'class' => 'circle']); ?>
			<?= $record['screen_name'] ?? 'unknown' ?> (<?= $record['username'] ?? 'unknown'; ?>)<br>
			<?= $record['players_number']; ?>人ゲーム / <?= $record['regulation_name']; ?><?php if ($record['is_moor']) echo '(泥沼)'; ?><br>
			<?= $record['player_order']; ?>番手 / <?= $record['total_points']; ?>点 / <?= $record['rank']; ?>位<br>
			[<?= $record['created_at']; ?>]
		</a>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>