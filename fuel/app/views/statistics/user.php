<div class="container">
    <h2 class="orange-text">得点推移</h2>
    <canvas id="transition_chart"></canvas>
    <h2 class="orange-text">得点分布</h2>
    <canvas id="distribution_chart"></canvas>
</div>
<script>
    var screenName = <?= json_encode($user_data['screen_name']); ?>;
    var transition_data_normal = <?= json_encode(array_column($transition_normal, 'average')); ?>;
    var transition_labels = <?= json_encode(array_column($transition_normal, 'month')); ?>;
    var distribution_data_normal = <?= json_encode(array_values($distribution_normal)); ?>;
    var distribution_labels = <?= json_encode(array_keys($distribution_normal)); ?>;
</script>
