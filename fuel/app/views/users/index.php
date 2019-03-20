<div class="container">
	<div class="collection">
		<?php foreach ($data as $record): ?>
		<?= Html::anchor('users/view/'.$record['username'], Asset::img($record['icon'], ['alt' => 'icon', 'class' => 'circle']).$record['screen_name'].' ['.$record['username'].']', ['class' => 'collection-item avatar']); ?>
		<?php endforeach; ?>
	</div>
</div>