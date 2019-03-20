<div class="container">
	<p>必要事項を入力してください。合言葉は管理者までお問い合わせください。</p>
	<?= Form::open(); ?>
	<div class="row">
		<div class="col s12 l7 input-field">
			<?= Form::password('old_password', null, ['required' => true, 'class' => 'validate']); ?>
			<?= Form::label('旧パスワード', 'old_password'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::password('new_password', null, ['required' => true, 'class' => 'validate']); ?>
			<?= Form::label('新パスワード', 'new_password'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::password('new_password_check', null, ['required' => true, 'class' => 'validate']); ?>
			<?= Form::label('新パスワード(確認)', 'new_password_check'); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::submit('submit', '変更', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>