<div class="container">
	<p>必要事項を入力してください。合言葉は管理者までお問い合わせください。</p>
	<?= Form::open(); ?>
	<div class="row">
		<div class="col s12 l7 input-field">
			<?= Form::input('username', Input::post('username'), ['required' => true, 'class' => $classes['username']]); ?>
			<?= Form::label('ユーザID', 'form_username'); ?><br>
			半角英数字・アンダースコア4文字以上で入力してください。
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::input('screen_name', Input::post('screen_name'), ['required' => true, 'class' => $classes['screen_name']]); ?>
			<?= Form::label('表示名', 'form_screen_name'); ?>
		</div>
		<div class="col s12 l7 input-field">

			<?= Form::input('email', Input::post('email'), ['type' => 'email', 'required' => true, 'class' => $classes['email']]); ?>
			<?= Form::label('メールアドレス', 'form_email'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::password('password', Input::post('password'), ['required' => true, 'class' => $classes['password']]); ?>
			<?= Form::label('パスワード', 'form_password'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::password('password_check', Input::post('password_check'), ['required' => true, 'class' => $classes['password_check']]); ?>
			<?= Form::label('パスワード(確認)', 'form_password_check'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::input('watchword', Input::post('watchword'), ['required' => true, 'class' => $classes['watchword']]); ?>
			<?= Form::label('合言葉', 'form_watchword'); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::submit('submit', '登録', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>