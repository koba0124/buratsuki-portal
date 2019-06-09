<div class="container">
	<p>
		各メンバーの統計詳細は、メンバー詳細からご覧いただけます。
	</p>
	<div class="collection">
		<?= Html::anchor('statistics/cardsuses', 'カード使用・勝利回数', ['class' => 'collection-item']); ?>
		<?= Html::anchor('statistics/score', 'ハイスコア', ['class' => 'collection-item']); ?>
		<?= Html::anchor('statistics/order', '番手別平均点・平均順位', ['class' => 'collection-item']); ?>
	</div>
</div>
