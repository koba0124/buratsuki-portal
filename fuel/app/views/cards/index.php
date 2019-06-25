<div class="container">
	<div class="row">
		<div class="col s12 l8">
			<?= Pagination::instance('cards')->render(); ?>
			<div class="collection">
				<?php foreach ($cards_list as $card): ?>
				<a href="<?= Uri::create('cards/view/:id', ['id' => $card['card_id']]); ?>" class="collection-item">
					<?= $card['japanese_name']; ?>
					<span class="new <?= Model_CardsMaster::get_type($card).'-bg'; ?> darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
				</a>
				<?php endforeach; ?>
			</div>
			<?= Pagination::instance('cards')->render(); ?>
		</div>
		<div class="col s12 l4">
			<h3 class="teal-text">カード検索</h3>
			<?= Form::open(['method' => 'get']); ?>
			<div class="row">
				<div class="col s12 input-field">
					<?= Form::input('n', Input::get('n')); ?>
					<?= Form::label('カード名', 'n'); ?>
				</div>
				<div class="col s12 input-field">
					<?= Form::select('d', Input::get('d'), $decks); ?>
					<label>デッキ</label>
				</div>
				<div class="col s12 input-field">
					<?= Form::input('q', Input::get('q')); ?>
					<?= Form::label('テキスト(ワード検索)', 'q'); ?>
				</div>
				<div class="col s4 l6 input-field">
					<label>
						<?= Form::checkbox('t[]', '1', in_array('1', $t), ['id' => 'form_t1']); ?>
						<span>職業</span>
					</label>
				</div>
				<div class="col s4 l6 input-field">
					<label>
						<?= Form::checkbox('t[]', '2', in_array('2', $t), ['id' => 'form_t2']); ?>
						<span>小進歩</span>
					</label>
				</div>
				<div class="col s4 l6 input-field">
					<label>
						<?= Form::checkbox('t[]', '3', in_array('3', $t), ['id' => 'form_t3']); ?>
						<span>大進歩</span>
					</label>
				</div>
				</div>
				<div class="col s12 input-field">
					<?= Form::submit('submit', '検索', ['class' => 'btn teal']); ?>
				</div>
			</div>
			<?= Form::close(); ?>
		</div>
	</div>
</div>