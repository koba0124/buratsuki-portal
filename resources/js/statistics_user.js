'use strict';

document.addEventListener('DOMContentLoaded', () => {
    let ctx = document.getElementById('transition_chart').getContext('2d');
    let transitionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: '通常',
                borderColor: 'rgb(255, 99, 132)',
                data: data,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false,
                text: '得点推移',
            }
        },
    });
});
