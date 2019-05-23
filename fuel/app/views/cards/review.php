<div class="container">
	<h1 class="<?= Model_CardsMaster::get_type($card); ?>-text">
		<?= $card['japanese_name']; ?>
		<span class="new <?= Model_CardsMaster::get_type($card); ?>-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
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
	<h2 class="teal-text">評価入力</h2>
	<?= Form::open(); ?>
	<div class="row">
		<div class="col s12 l7 input-field">
			<?= Form::input('review_points', Input::post('review_points', Arr::get($review_data, 'review_points')), ['required' => true, 'max' => 10, 'min' => 0, 'type' => 'number', 'step' => 0.1, 'class' => Helper::validate_class($error_fields, 'review_points')]); ?>
			<?= Form::label('評価点', 'review_points'); ?>
		</div>
		<div class="col s12 l5">
			<p>10点満点で入力してください。</p>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::textarea('review_comment', Input::post('review_comment', Arr::get($review_data, 'review_comment')), ['class' => Helper::validate_class($error_fields, 'review_comment').' materialize-textarea']); ?>
			<?= Form::label('ひとこと', 'review_comment'); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::submit('submit', '更新', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>