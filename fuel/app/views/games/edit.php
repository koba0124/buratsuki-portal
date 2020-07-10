<div class="container">
	<h2 class="orange-text">ゲーム情報</h2>
	<div class="collection">
		<div class="collection-item">
		    プレイヤー：<?= $data['username']; ?><br>
			<?= $data['players_number']; ?>人ゲーム / <?= $data['regulation_name']; ?><?php if ($data['is_moor']) echo '(泥沼)'; ?> / <?= $data['player_order']; ?>番手<br>
			<?= $data['owner']; ?>さんが作成 [<?= $data['created_at']; ?>]
		</div>
	</div>
	<?= Form::open(['enctype' => 'multipart/form-data']); ?>
	<h2 class="teal-text">得点</h2>
	<div class="row">
		<?php foreach ($basic_points_list as $field => $label): ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input($field, Input::post($field, $data[$field]), ['class' => Helper::validate_class($error_fields, $field, null, 'form_points'), 'type' => 'number', 'min' => '-1', 'max' => '4', 'required']); ?>
			<?= Form::label($label, $field); ?>
		</div>
		<?php endforeach; ?>
		<?php if ($data['is_moor']): ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('horses', Input::post('horses', $data['horses']), ['class' => Helper::validate_class($error_fields, 'horses', null, 'form_points'), 'type' => 'number', 'min' => '-1', 'required']); ?>
			<?= Form::label('馬', 'horses'); ?>
		</div>
		<?php endif; ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('unused_spaces', Input::post('unused_spaces', $data['unused_spaces']), ['class' => Helper::validate_class($error_fields, 'unused_spaces', null, 'form_points'), 'type' => 'number', 'max' => '0', 'required']); ?>
			<?= Form::label('未使用スペース', 'unused_spaces'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('stable', Input::post('stable', $data['stable']), ['class' => Helper::validate_class($error_fields, 'stable', null, 'form_points'), 'type' => 'number', 'min' => '0', 'max' => '4', 'required']); ?>
			<?= Form::label('柵に囲まれた厩', 'stable'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('rooms', Input::post('rooms', $data['rooms']), ['class' => Helper::validate_class($error_fields, 'rooms', null, 'form_points'), 'type' => 'number', 'min' => '0', 'required']); ?>
			<?= Form::label('部屋', 'rooms'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('family', Input::post('family', $data['family']), ['class' => Helper::validate_class($error_fields, 'family', null, 'form_points'), 'type' => 'number', 'min' => '0', 'max' => '15', 'required']); ?>
			<?= Form::label('家族', 'family'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('begging', Input::post('begging', $data['begging']), ['class' => Helper::validate_class($error_fields, 'begging', null, 'form_points'), 'type' => 'number', 'max' => '0', 'required']); ?>
			<?= Form::label('物乞い', 'begging'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('card_points', Input::post('card_points', $data['card_points']), ['class' => Helper::validate_class($error_fields, 'card_points', null, 'form_points'), 'type' => 'number', 'required']); ?>
			<?= Form::label('カード点', 'card_points'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('bonus_points', Input::post('bonus_points', $data['bonus_points']), ['class' => Helper::validate_class($error_fields, 'bonus_points', null, 'form_points'), 'type' => 'number', 'required']); ?>
			<?= Form::label('ボーナス点', 'bonus_points'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('total_points', Input::post('total_points', $data['total_points']), ['class' => Helper::validate_class($error_fields, 'total_points'), 'type' => 'number', 'required']); ?>
			<?= Form::label('合計点', 'total_points'); ?>
		</div>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('rank', Input::post('rank', $data['rank']), ['class' => Helper::validate_class($error_fields, 'rank'), 'type' => 'number', 'min' => '1', 'max' => $data['players_number'], 'required']); ?>
			<?= Form::label('順位', 'rank'); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::textarea('comment', Input::post('comment', $data['comment']), ['class' => Helper::validate_class($error_fields, 'comment', null, 'materialize-textarea')]); ?>
			<?= Form::label('コメント', 'comment'); ?>
		</div>
	</div>
	<h2 class="teal-text">画像</h2>
	<div class="row">
		<div class="col s12 m9 file-field input-field">
			<p>jpg、png、gifファイル(10MB)が選択可能です。</p>
			<div class="btn teal">
				<span>画像</span>
				<?= Form::file('image'); ?>
			</div>
			<div class="file-path-wrapper">
				<input class="file-path validate" type="text">
			</div>
		</div>
		<?php if ($data['image']): ?>
		<div class="col s12 m3">
			<?= Asset::img($data['image'], ['class' => 'responsive-img']); ?>
		</div>
		<?php endif; ?>
	</div>
	<h2 class="teal-text">カード</h2>
	<p>
		カード番号を半角英数字で入力してください。
	</p>
	<?php foreach ($cards_type_list as $field => $label): ?>
	<div class="row" id="<?= $field; ?>s_box">
		<?php $cards = $cards_list[$field . 's'] ?? $cards_data[$field . 's'] ?? []; ?>
		<?php foreach ($cards as $key => $card): ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input($field . 's[]', $card, ['class' => Helper::validate_class($error_fields, $field . 's', $key), 'id' => 'form_' . $field . 's_'.$key]); ?>
			<?= Form::label($label, $field . 's_' . $key); ?>
		</div>
		<?php endforeach; ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input($field . 's[]', null, ['class' => 'validate', 'id' => 'form_' . $field . 's_'.(count($cards))]); ?>
			<?= Form::label($label, $field . 's_' . (count($cards))); ?>
		</div>
		<div class="col s4 m3 l2 input-field" id="<?= $field; ?>s_btn_box">
			<button type="button" class="btn <?= $field; ?>-bg" id="<?= $field; ?>s_button">+</button>
		</div>
	</div>
	<?php endforeach; ?>
	<h2 class="teal-text">ドラフト</h2>
	<p>
		ドラフトで取得したカード番号を半角英数字で入力してください。8～10枚目は引かれずに残ったカードです。
	</p>
	<?php foreach ($cards_type_list as $field => $label): 
		if($field == 'major_improvement'){continue;}?>
	<div class="row" id="<?= $field; ?>s_box">
		<?php $cards = $draft_data[$field . 's'] ?>
		<?php foreach ($cards as $key => $card): ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('draft' . $field . 's[]', $card, ['class' => Helper::validate_class($error_fields,'draft' . $field . 's', $key), 'id' => 'form_' .'draft' . $field . 's_'.$key]); ?>
			<?= Form::label('ドラフト' . $label . $key + 1 , $field . 's_' . $key); ?>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
	<div class="row">
		<div class="col s12 input-field">
			<?= Form::submit('submit', '編集', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>