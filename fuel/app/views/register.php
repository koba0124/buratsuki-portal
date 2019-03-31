<div class="container">
	<p>必要事項を入力してください。合言葉は管理者までお問い合わせください。</p>
	<?= Form::open(); ?>
	<div class="row">
		<div class="col s12 l7 input-field">
			<?= Form::input('username', Input::post('username'), ['required' => true, 'class' => Helper::validate_class($error_fields, 'user_name')]); ?>
			<?= Form::label('ユーザID', 'username'); ?><br>
			半角英数字・アンダースコア4文字以上で入力してください。
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::input('screen_name', Input::post('screen_name'), ['required' => true, 'class' => Helper::validate_class($error_fields, 'screen_name')]); ?>
			<?= Form::label('表示名', 'screen_name'); ?>
		</div>
		<div class="col s12 l7 input-field">

			<?= Form::input('email', Input::post('email'), ['type' => 'email', 'required' => true, 'class' => Helper::validate_class($error_fields, 'email')]); ?>
			<?= Form::label('メールアドレス', 'email'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::password('password', null, ['required' => true, 'class' => Helper::validate_class($error_fields, 'password')]); ?>
			<?= Form::label('パスワード', 'password'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::password('password_check', null, ['required' => true, 'class' => Helper::validate_class($error_fields, 'password_check')]); ?>
			<?= Form::label('パスワード(確認)', 'password_check'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::input('watchword', Input::post('watchword'), ['required' => true, 'class' => Helper::validate_class($error_fields, 'watchword')]); ?>
			<?= Form::label('合言葉', 'watchword'); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::submit('submit', '登録', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>