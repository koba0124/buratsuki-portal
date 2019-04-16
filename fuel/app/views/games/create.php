<div class="container">
	<p>ゲームの詳細を入力してください。</p>
	<?= Form::open(['autocomplete' => 'off']); ?>
	<div class="row">
		<div class="col s12 m7 input-field">
			<?= Form::select('players_number', Input::post('players_number'), $players_number_list, ['required' => true, 'class' => Helper::validate_class($error_fields, 'players_number')]); ?>
			<label>プレイ人数</label>
		</div>
		<div class="col s12 m7 input-field">
			<?= Form::select('regulation_type', Input::post('regulation_type'), $regulation_type_list, ['required' => true, 'class' => Helper::validate_class($error_fields, 'regultion_type')]); ?>
			<label>レギュレーション</label>
		</div>
		<div class="col s12 m7">
			<p>
				<label>
				<?= Form::radio('is_moor', 0, Input::post('is_moor'), ['id' => 'form_moor1', 'checked' => 'checked', 'required' => true]); ?>
				<span>通常</span>
			</label>
			</p>
			<p>
				<label>
				<?= Form::radio('is_moor', 1, Input::post('is_moor'), ['id' => 'form_moor2', 'required' => true]); ?>
				<span>泥沼</span>
			</label>
			</p>
		</div>
		<?php foreach (range(0, 5) as $key): ?>
		<div class="col s12 m6 input-field" id="formBox_players<?= $key; ?>">
			<?= Form::input('players[]', Input::post('players.'.$key), ['required' => true, 'class' => Helper::validate_class($error_fields, 'players', $key), 'id' => 'form_players'.$key, 'class' => 'autocomplete']); ?>
			<?= Form::label('ユーザID('.($key+1).'番手)', 'players'.$key); ?>
		</div>
		<?php endforeach; ?>
		<div class="col s12 input-field">
			<?= Form::submit('submit', '作成', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>