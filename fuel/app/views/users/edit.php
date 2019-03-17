<div class="container">
	<p>必要事項を入力してください。アイコンの登録は<?= Html::anchor('users/icon', 'こちら'); ?>からどうぞ。</p>
	<?= Form::open(); ?>
	<div class="row">
		<div class="col s12 l7 input-field">
			<?= Form::input('screen_name', Input::post('screen_name', Arr::get($data, 'screen_name')), ['required' => true, 'class' => Arr::get($classes, 'screen_name', 'validate')]); ?>
			<?= Form::label('表示名', 'screen_name'); ?>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::input('twitter', Input::post('twitter', Arr::get($data, 'twitter')), ['class' => Arr::get($classes, 'twitter', 'validate')]); ?>
			<?= Form::label('Twitterアカウント', 'twitter'); ?>
			<p>
				@は不要です
			</p>
		</div>
		<div class="col s12 l7 input-field">
			<?= Form::textarea('comment', Input::post('comment', Arr::get($data, 'comment')), ['class' => Arr::get($classes, 'comment', 'validate').' materialize-textarea']); ?>
			<?= Form::label('ひとこと', 'comment'); ?>
		</div>
	</div>
	<div class="row" id="occupations_box">
		<?php $occupations = $occupations ?? Arr::get($data, 'occupations', []); ?>
		<?php foreach ($occupations as $key => $occupation): ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('occupations[]', $occupation, ['class' => Arr::get($classes, 'occupations.'.$key, 'validate'), 'id' => 'form_occupations_'.$key]); ?>
			<?= Form::label('好きな職業', 'occupations_'.$key); ?>
		</div>
		<?php endforeach; ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('occupations[]', null, ['class' => 'validate', 'id' => 'form_occupations_'.(count($occupations))]); ?>
			<?= Form::label('好きな職業', 'occupations_'.(count($occupations))); ?>
		</div>
		<div class="col s4 m3 l2 input-field" id="occupations_btn_box">
			<button type="button" class="btn occupation-bg" id="occupations_button">+</button>
		</div>
	</div>
	<div class="row" id="minor_improvements_box">
		<?php $minor_improvements = $minor_improvements ?? Arr::get($data, 'minor_improvements', []); ?>
		<?php foreach ($minor_improvements as $key => $minor_improvement): ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('minor_improvements[]', $minor_improvement, ['class' => Arr::get($classes, 'minor_improvements.'.$key, 'validate'), 'id' => 'form_minor_improvements_'.$key]); ?>
			<?= Form::label('好きな小進歩', 'minor_improvements_'.$key); ?>
		</div>
		<?php endforeach; ?>
		<div class="col s4 m3 l2 input-field">
			<?= Form::input('minor_improvements[]', null, ['class' => 'validate', 'id' => 'form_minor_improvements_'.(count($minor_improvements))]); ?>
			<?= Form::label('好きな小進歩', 'minor_improvements_'.(count($minor_improvements))); ?>
		</div>
		<div class="col s4 m3 l2 input-field" id="minor_improvements_btn_box">
			<button type="button" class="btn minor_improvement-bg" id="minor_improvements_button">+</button>
		</div>
	</div>
	<div class="row">
		<div class="col s12 input-field">
			<?= Form::submit('submit', '更新', ['class' => 'btn teal']); ?>
		</div>
	</div>
	<?= Form::csrf(); ?>
	<?= Form::close(); ?>
</div>