<div class="container">
	<?= Form::open(['enctype' => 'multipart/form-data']); ?>
	<div class="row">
		<div class="col s12 m9 file-field input-field">
			<p>jpg、png、gifファイル(5MB)が選択可能です。</p>
			<div class="btn teal">
				<span>アイコン</span>
				<?= Form::file('icon'); ?>
			</div>
			<div class="file-path-wrapper">
				<input class="file-path validate" type="text">
			</div>
		</div>
		<div class="col s4 m3">
			<h3 class="blue-text">現在のアイコン</h3>
			<?= Asset::img(Auth::get_profile_fields('icon'), ['alt' => 'icon', 'class' => 'circle responsive-img']); ?>
		</div>
		<div class="col s12 input-field">
			<?= Form::submit('submit', 'アップロード', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>