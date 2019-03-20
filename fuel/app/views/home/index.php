<div class="container">
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