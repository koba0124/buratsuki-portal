'use strict';

document.addEventListener('DOMContentLoaded', () => {
    let ctx_transition = document.getElementById('transition_chart').getContext('2d');
    new Chart(ctx_transition, {
        type: 'line',
        data: {
            labels: transition_labels,
            datasets: [{
                label: '通常',
                borderColor: 'rgb(8, 150, 136)',
                data: transition_data_normal,
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

    let ctx_distribution = document.getElementById('distribution_chart').getContext('2d');
    new Chart(ctx_distribution, {
        type: 'bar',
        data: {
            labels: distribution_labels,
            datasets: [{
                label: '通常',
				borderColor: 'rgb(8, 150, 136)',
				backgroundColor: 'rgb(8, 150, 136)',
                data: distribution_data_normal,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: false,
                text: '得点分布',
			},
			scales: {
				xAxes: {
					categoryPercentage: 1.1,
				}
			},
        },
    });
});
