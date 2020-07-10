<div class="container">
	<h2 class="orange-text">ゲーム情報</h2>
	<div class="collection">
		<div class="collection-item">
			<?= $data['players_number']; ?>人ゲーム / <?= $data['regulation_name']; ?><?php if ($data['is_moor']) echo '(泥沼)'; ?><br>
			[<?= $data['created_at']; ?>]
		</div>
	</div>
	<div>
		<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
		<?= Html::anchor(Uri::create('https://twitter.com/share', [], ['url' => Uri::current(), 'hashtags' => 'ほら吹き社会人ポータル']), 'Tweet', ['class' => 'twitter-share-button', 'data-show-count' => 'false']); ?>
	</div>
	<h2 class="orange-text">スコア</h2>
	<table class="striped">
		<thead>
			<tr>
				<th>プレイヤー</th>
				<?php foreach ($score_data as $order => $record): ?>
				<th>
					<?= Html::anchor('#player' . $record['player_order'], Asset::img($record['profile_fields']['icon'] ?? 'noimage.png', ['class' => 'circle tooltipped', 'style' => 'width: 30px;', 'data-position' => 'top', 'data-tooltip' => $record['profile_fields']['screen_name'] ?? 'unknown']));?>
				</th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($basic_points_list as $field => $label): ?>
			<tr>
				<th><?= $label; ?></th>
				<?php foreach ($score_data as $order => $record): ?>
				<td><?= $record[$field] ?? '-'; ?></td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
			<?php if ($data['is_moor']): ?>
			<tr>
				<th>馬</th>
				<?php foreach ($score_data as $order => $record): ?>
				<td><?= $record['horses'] ?? '-'; ?></td>
				<?php endforeach; ?>
			</tr>
			<?php endif; ?>
			<?php foreach ($advanced_points_list as $field => $label): ?>
			<tr>
				<th><?= $label; ?></th>
				<?php foreach ($score_data as $order => $record): ?>
				<td><?= $record[$field] ?? '-'; ?></td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr class="yellow lighten-5">
				<th>合計点</th>
				<?php foreach ($score_data as $order => $record): ?>
				<td><?= $record['total_points'] ?? '-'; ?></td>
				<?php endforeach; ?>
			</tr>
			<tr class="yellow lighten-3">
				<th>順位</th>
				<?php foreach ($score_data as $order => $record): ?>
				<td><?= $record['rank'] ?? '-'; ?></td>
				<?php endforeach; ?>
			</tr>
		</tfoot>
	</table>
	<?php foreach ($score_data as $order => $record): ?>
	<?php if ($record['fields'] === null) continue; ?>
	<h3 class="orange-text" id="player<?= $order; ?>"><?= $record['profile_fields']['screen_name'] ?? 'unknown'; ?>(<?= $order; ?>番手)の詳細</h3>
	<p>
		<?= $record['total_points']; ?>点 / <?= $record['rank']; ?>位
	</p>
	<?php if (Auth::get_screen_name() === $record['username'] or 'Guest' === $record['username'] or Auth::get_screen_name() === $data['owner']): ?>
	<?= Html::anchor('/games/edit/'.$record['game_id'].'/'.$order, '編集する'); ?>
	<?php endif; ?>
	<div class="row">
		<?php foreach ($cards_type_list as $type => $label): ?>
		<div class="col s12 m6 l4">
			<h4 class="<?= $type; ?>-text"><?= $label; ?></h4>
			<div class="collection">
				<?php foreach ($cards_data[$order][$type . 's'] ?? [] as $card): ?>
				<a href="#modal_card_<?= $order; ?>_<?= $card['card_id']; ?>" class="modal-trigger collection-item">
					<?= $card['japanese_name']; ?>
					<span class="new <?= Model_CardsMaster::get_type($card); ?>-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="row">
		<?php foreach ($cards_type_list as $type => $label): 
			if ($field == 'major_improvement') { continue; }?>
		<div class="col s12 m6 l4">
			<h4 class="<?= $type; ?>-text"><?= $label; ?></h4>
			<div class="collection">
				<?php foreach ($draft_data[$order][$type . 's'] ?? [] as $card): ?>
					<?php if (!empty($card)) : ?>
						<a href="#modal_draftcard_<?= $order; ?>_<?= $card['card_id']; ?>" class="modal-trigger collection-item">
							<?= $card['japanese_name']; ?>
							<span class="new <?= Model_CardsMaster::get_type($card); ?>-bg darken-2 badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
						</a>
					<?php else : ?>
						<span>？？？</span>
					<?php endif ; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="row">
		<?php if ($record['comment']): ?>
		<div class="col s12 m8">
			<h4 class="teal-text">ひとこと</h4>
			<p><?= nl2br($record['comment']); ?></p>
		</div>
		<?php endif; ?>
		<?php if ($record['image']): ?>
		<div class="col s12 m4">
			<?= Asset::img($record['image'], ['class' => 'responsive-img materialboxed']); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php if (isset($record['profile_fields']['screen_name'])): ?>
	<div class="collection">
		<div class="collection-item avatar">
			<?= Asset::img($record['profile_fields']['icon'], ['alt' => 'icon', 'class' => 'circle']); ?>
			<span><?= $record['profile_fields']['screen_name'] ?> (<?= $record['username']; ?>)</span><br>
			<span class="grey-text"><?= $record['profile_fields']['comment'] ?? ''; ?></span>
			<div class="right-align"><?= Html::anchor('users/view/' . $record['username'], '詳細はこちら'); ?></div>
		</div>
	</div>
	<?php endif; ?>
	<?php endforeach; ?>
	<?php if (Auth::get_screen_name() === $data['owner']): ?>
	<h3 class="red-text">ゲーム作成者メニュー</h3>
	<div class="collection">
		<?= Html::anchor('#modal_calc_rank', '順位自動計算', ['class' => 'modal-trigger collection-item']); ?>
		<?= Html::anchor('#modal_set_date', '日時設定', ['class' => 'modal-trigger collection-item']); ?>
		<?= Html::anchor('#modal_delete', 'ゲーム削除', ['class' => 'modal-trigger collection-item']); ?>
	</div>
	<?php endif; ?>
</div>
<?= Form::open(); ?>
<div id="modal_calc_rank" class="modal">
	<div class="modal-content">
		<h4 class="teal-text">順位自動計算</h4>
		<p>全員の得点が入力済みならば、順位を自動計算します。</p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-close waves-effect waves-grey btn-flat">戻る</a>
		<?= Form::button('submit', '計算する', ['class' => 'waves-effect waves-teal btn-flat teal-text', 'value' => '計算する']); ?>
	</div>
</div>
<div id="modal_set_date" class="modal">
	<div class="modal-content">
		<h4 class="teal-text">日時設定</h4>
		<p>ゲーム開催日時を変更します。</p>
		<div class="input-field">
			<?= Form::input('created_at_new', Input::post('created_at_new', $data['created_at']), ['type' => 'datetime-local']); ?>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-close waves-effect waves-grey btn-flat">戻る</a>
		<?= Form::button('submit', '日時を更新する', ['class' => 'waves-effect waves-teal btn-flat teal-text', 'value' => '日時を更新する']); ?>
	</div>
</div>

<div id="modal_delete" class="modal">
	<div class="modal-content">
		<h4 class="red-text">ゲーム削除</h4>
		<p>一度削除すると、データを復元することはできません。本当によろしいですか。</p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-close waves-effect waves-grey btn-flat">戻る</a>
		<?= Form::button('submit', '削除する', ['class' => 'waves-effect waves-red btn-flat red-text', 'value' => '削除する']); ?>
	</div>
</div>
<?= Form::csrf(); ?>
<?= Form::close(); ?>
<?php foreach ($cards_data as $order => $order_cards): ?>
<?php foreach ($order_cards as $type_cards ): ?>
<?php foreach ($type_cards as $card): ?>
<div id="modal_card_<?= $order; ?>_<?= $card['card_id'] ?>" class="modal">
	<div class="modal-content">
		<h4 class="<?= Model_CardsMaster::get_type($card); ?>-text">
			<?= $card['japanese_name']; ?>
			<span class="new <?= Model_CardsMaster::get_type($card); ?>-bg badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
		</h4>
		<dl>
			<dt class="teal-text">デッキ</dt>
			<dd><?= $card['deck_name']; ?></dd>
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
			<?php if (! empty($card['category'])): ?>
			<dt class="teal-text">カテゴリー</dt>
			<dd><?= $card['category']; ?>+</dd>
			<?php endif; ?>
		</dl>
		<div>
			<?= nl2br($card['description']); ?>
		</div>
		<?= Html::anchor('cards/view/' . $card['card_id'], 'カードの詳細ページを見る'); ?>
	</div>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endforeach; ?>
<?php foreach ($draft_data as $order => $order_cards): ?>
<?php foreach ($order_cards as $type_cards ): ?>
<?php foreach ($type_cards as $card): ?>
<?php if ( empty($card)) { continue; } ?>
<div id="modal_draftcard_<?= $order; ?>_<?= $card['card_id'] ?>" class="modal">
	<div class="modal-content">
		<h4 class="<?= Model_CardsMaster::get_type($card); ?>-text">
			<?= $card['japanese_name']; ?>
			<span class="new <?= Model_CardsMaster::get_type($card); ?>-bg badge" data-badge-caption=""><?= $card['card_id_display']; ?></span>
		</h4>
		<dl>
			<dt class="teal-text">デッキ</dt>
			<dd><?= $card['deck_name']; ?></dd>
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
			<?php if (! empty($card['category'])): ?>
			<dt class="teal-text">カテゴリー</dt>
			<dd><?= $card['category']; ?>+</dd>
			<?php endif; ?>
		</dl>
		<div>
			<?= nl2br($card['description']); ?>
		</div>
		<?= Html::anchor('cards/view/' . $card['card_id'], 'カードの詳細ページを見る'); ?>
	</div>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endforeach; ?>