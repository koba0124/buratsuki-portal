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
		<dd><?= $card['card_points']; ?></dd>
		<?php endif; ?>
		<?php endif; ?>
		<dt class="teal-text">説明</dt>
		<dd><?= nl2br($card['description']); ?></dd>
	</dl>
</div>