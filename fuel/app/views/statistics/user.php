<div class="container">
    <h2 class="orange-text">得点推移</h2>
    <canvas id="transition_chart"></canvas>
</div>
<script>
    var screenName = <?= json_encode($user_data['screen_name']); ?>;
    var data = <?= json_encode(array_column($transition_normal, 'average')); ?>;
    var labels = <?= json_encode(array_column($transition_normal, 'month')); ?>;
</script>
