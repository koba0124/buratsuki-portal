<div class="row">
	<div class="col s12" style="padding: 0;">
		<ul class="tabs">
			<li class="tab col s3"><a href="#uses_occupation" class="occupation-text">使用数(職業)</a></li>
			<li class="tab col s3"><a href="#uses_minor_improvement" class="minor_improvement-text">使用数(小進歩)</a></li>
			<li class="tab col s3"><a href="#wins_occupation" class="occupation-text">勝利数(職業)</a></li>
			<li class="tab col s3"><a href="#wins_minor_improvement" class="minor_improvement-text">勝利数(小進歩)</a></li>
		</ul>
	</div>
	<?php foreach ($uses_ranking as $type => $ranking): ?>
	<div class="col s12" id="uses_<?= $type; ?>">
		<div class="container">
			<p>
				拡張、2人以上のゲームを集計対象としています。
			</p>
			<table>
				<thead>
					<tr>
						<th>順位</th>
						<th>カード</th>
						<th></th>
						<th>回数</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($ranking as $record): ?>
					<tr>
						<td><?= $record['rank']; ?>位</td>
						<td>
							<span class="new badge <?= $type; ?>-bg" data-badge-caption="<?= $record['card_id_display']; ?>"></span> 
							<?= $record['japanese_name']; ?>
						</td>
						<td><?= Html::anchor('/cards/view/' . $record['card_id'], '<i class="material-icons">link</i>'); ?></td>
						<td><?= $record['count']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php endforeach; ?>
	<?php foreach ($wins_ranking as $type => $ranking): ?>
	<div class="col s12" id="wins_<?= $type; ?>">
		<div class="container">
			<p>
				レギュレーションは旧版拡張、2人以上のゲームを集計対象としています。
			</p>
			<table>
				<thead>
					<tr>
						<th>順位</th>
						<th>カード</th>
						<th></th>
						<th>勝利数</th>
						<th>勝率</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($ranking as $record): ?>
					<tr>
						<td><?= $record['rank']; ?>位</td>
						<td>
							<span class="new badge <?= $type; ?>-bg" data-badge-caption="<?= $record['card_id_display']; ?>"></span> 
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
	</div>
	<?php endforeach; ?>
</div>
