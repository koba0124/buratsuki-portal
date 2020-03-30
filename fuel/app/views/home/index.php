<div class="container">
	<div class="row">
		<div class="col s12 input-field">
			<?= Html::anchor('/games/create', '<i class="material-icons left">add</i>ゲーム作成', ['class' => 'btn-large teal']); ?>
		</div>
	</div>
	<h2 class="orange-text">未入力のゲーム</h2>
	<div class="collection">
		<?php foreach ($games_list as $game): ?>
		<a href="<?= Uri::create('/games/edit/:game_id/:player_order', ['game_id' => $game['game_id'], 'player_order' => $game['player_order']]); ?>" class="collection-item">
			<?= $game['players_number']; ?>人ゲーム / <?= $game['player_order']; ?>番手 / <?= $game['username']; ?> / <?= $game['regulation_name']; ?><?php if ($game['is_moor']) echo '(泥沼)'; ?><br>
			<?= $game['owner']; ?>さんが作成 [<?= $game['created_at']; ?>]
		</a>
		<?php endforeach; ?>
	</div>
	<h2 class="orange-text">未入力のゲーム(Owner)</h2>
	<div class="collection">
		<?php foreach ($owner_games_list as $game): ?>
		<a href="<?= Uri::create('/games/edit/:game_id/:player_order', ['game_id' => $game['game_id'], 'player_order' => $game['player_order']]); ?>" class="collection-item">
			<?= $game['players_number']; ?>人ゲーム / <?= $game['player_order']; ?>番手 / <?= $game['regulation_name']; ?><?php if ($game['is_moor']) echo '(泥沼)'; ?><br>
			<?= $game['owner']; ?>さんが作成 [<?= $game['created_at']; ?>]
		</a>
		<?php endforeach; ?>
	</div>
	<h2 class="orange-text">未入力のゲーム(Guest)</h2>
	<div class="collection">
		<?php foreach ($guest_games_list as $game): ?>
		<a href="<?= Uri::create('/games/edit/:game_id/:player_order', ['game_id' => $game['game_id'], 'player_order' => $game['player_order']]); ?>" class="collection-item">
			<?= $game['players_number']; ?>人ゲーム / <?= $game['player_order']; ?>番手 / <?= $game['regulation_name']; ?><?php if ($game['is_moor']) echo '(泥沼)'; ?><br>
			<?= $game['owner']; ?>さんが作成 [<?= $game['created_at']; ?>]
		</a>
		<?php endforeach; ?>
	</div>
	<h2 class="blue-text">個人設定</h2>
	<div class="collection">
		<?= Html::anchor('/home/edit_profile', 'プロフィール編集', ['class' => 'collection-item']); ?>
		<?= Html::anchor('/home/edit_icon', 'アイコン変更', ['class' => 'collection-item']); ?>
		<?= Html::anchor('/home/change_password', 'パスワード変更', ['class' => 'collection-item']); ?>
	</div>
	<h2 class="blue-text">その他</h2>
	<div class="collection">
		<?= Html::anchor('/logout', 'ログアウト', ['class' => 'collection-item']); ?>
		<?= Html::anchor('/register', '新規ユーザ登録', ['class' => 'collection-item']); ?>
	</div>
</div>