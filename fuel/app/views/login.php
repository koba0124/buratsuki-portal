<div class="container">
	<p>登録した内容を入力してください。パスワードをお忘れの際には、管理者までお問い合わせください。</p>
	<?= Form::open(); ?>
	<div class="row">
		<div class="col s12 l6 input-field">
			<?= Form::input('username', Input::post('username'), ['required' => true, 'class' => 'validate']); ?>
			<?= Form::label('ID / メールアドレス', 'username'); ?>
		</div>
		<div class="col s12 l6 input-field">
			<?= Form::password('password', null, ['required' => true, 'class' => 'validate']); ?>
			<?= Form::label('パスワード', 'password'); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::submit('submit', 'ログイン', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>