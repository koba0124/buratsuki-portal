<div class="row">
	<div class="col s12" style="padding: 0;">
		<ul class="tabs">
			<li class="tab col s6"><a href="#ranking_normal">ハイスコア(通常)</a></li>
			<li class="tab col s6"><a href="#ranking_moor">ハイスコア(泥沼)</a></li>
		</ul>
	</div>
	<div class="col s12" id="ranking_normal">
		<div class="container">
			<p>
				レギュレーションは旧版拡張、2人以上のゲームを集計対象としています。
			</p>
			<table>
				<thead>
					<tr>
						<th>順位</th>
						<th>点数</th>
						<th></th>
                        <th></th>
                        <th>プレイヤー</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($normal_ranking as $record): ?>
					<tr>
						<td><?= $record['rank']; ?>位</td>
						<td>
							<?= $record['total_points']; ?>点
						</td>
						<td><?= Html::anchor('/games/view/' . $record['game_id'], '<i class="material-icons">link</i>'); ?></td>
                        <td class="right-align"><?= Html::anchor('/users/view/' . $record['username'], Asset::img($record['icon'], ['class' => 'circle', 'style' => 'width: 2em;'])); ?></td>
						<td><?= $record['screen_name']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
    <div class="col s12" id="ranking_moor">
		<div class="container">
			<p>
				レギュレーションは旧版拡張、2人以上のゲームを集計対象としています。
			</p>
			<table>
				<thead>
					<tr>
						<th>順位</th>
						<th>点数</th>
						<th></th>
                        <th></th>
                        <th>プレイヤー</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($moor_ranking as $record): ?>
					<tr>
						<td><?= $record['rank']; ?>位</td>
						<td>
							<?= $record['total_points']; ?>点
						</td>
						<td><?= Html::anchor('/games/view/' . $record['game_id'], '<i class="material-icons">link</i>'); ?></td>
                        <td class="right-align"><?= Html::anchor('/users/view/' . $record['username'], Asset::img($record['icon'], ['class' => 'circle', 'style' => 'width: 2em;'])); ?></td>
						<td><?= $record['screen_name']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
