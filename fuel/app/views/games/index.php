<div class="container">
	<?= Pagination::instance('games')->render(); ?>
	<div class="collection">
		<?php foreach ($games_list as $record): ?>
		<a href="<?= Uri::create('/games/view/:game_id', ['game_id' => $record['game_id']]); ?>" class="collection-item">
			<?= $record['players_number']; ?>人ゲーム / <?= $record['regulation_name']; ?><?php if ($record['is_moor']) echo '(泥沼)'; ?><br>
			[<?= $record['created_at']; ?>]
		</a>
		<?php endforeach; ?>
	</div>
	<?= Pagination::instance('games')->render(); ?>
</div>