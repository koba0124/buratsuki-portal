<div class="row">
	<?php
		$players_number_list = [2, 3, 4, 5];
	?>
	<div class="col s12" style="padding: 0;">
		<ul class="tabs">
			<?php foreach ($players_number_list as $players_number): ?>
			<li class="tab col s3"><a href="#players_number_<?= $players_number; ?>"><?= $players_number; ?>グリ</a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php foreach ($players_number_list as $players_number): ?>
	<div class="col s12" id="players_number_<?= $players_number; ?>">
		<div class="container">
			<p>
				通常ゲームを対象としています。
			</p>
			<table>
				<thead>
					<tr>
						<th>番手</th>
						<th>平均点</th>
						<th>平均順位</th>	
					</tr>
				</thead>
				<tbody>
					<?php for ($player_order = 1; $player_order <= $players_number; $player_order++): ?>
					<tr>
						<td><?= $player_order ?>番手</td>
						<td><?= $score_average_data[$players_number][$player_order] ?? '-'; ?>点</td>
						<td><?= $rank_average_data[$players_number][$player_order] ?? '-'; ?>位</td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php endforeach; ?>
</div>
