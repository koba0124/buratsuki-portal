<div class="container">
    <h2 class="orange-text">得点推移</h2>
    <canvas id="transition_chart"></canvas>
    <h2 class="orange-text">得点分布</h2>
    <canvas id="distribution_chart"></canvas>
    <h2 class="orange-text">使用回数の多いカード</h2>
    <p>
		レギュレーションは旧版拡張、2人以上のゲームを集計対象としています。
	</p>
    <div class="row">
        <?php $types = ['occupation' => '職業', 'minor_improvement' => '小さい進歩']; ?>
        <?php foreach ($types as $type => $label): ?>
        <div class="col s12 l6">
            <h3 class="<?= $type ?>-text"><?= $label; ?></h3>
            <table>
				<thead>
					<tr>
						<th>順位</th>
						<th>カード</th>
						<th></th>
						<th>使用数</th>
						<th>使用率</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($uses_rankings[$type] as $record): ?>
					<tr>
						<td><?= $record['rank']; ?>位</td>
						<td>
							<span class="new badge <?= $type ?>-bg" data-badge-caption="<?= $record['card_id_display']; ?>"></span> 
							<?= $record['japanese_name']; ?>
						</td>
						<td><?= Html::anchor('/cards/view/' . $record['card_id'], '<i class="material-icons">link</i>'); ?></td>
						<td><?= $record['count']; ?></td>
						<td><?= $record['rate']; ?>%</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    var screenName = <?= json_encode($user_data['screen_name']); ?>;
    var transition_data_normal = <?= json_encode(array_column($transition_normal, 'average')); ?>;
    var transition_labels = <?= json_encode(array_column($transition_normal, 'month')); ?>;
    var distribution_data_normal = <?= json_encode(array_values($distribution_normal)); ?>;
    var distribution_labels = <?= json_encode(array_keys($distribution_normal)); ?>;
</script>
