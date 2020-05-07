'use strict';

var labels;
var months = moment.months();
var data = [
    { 'initial': [] },
    { 'learning': [] },
    { 'pending': [] },
    { 'dropped': [] },
    { 'completed': [] }
];

if ( 'this_year' == growthReport.type ) {
    labels = months;

    for ( var i = 0, len = labels.length; i < len; i++ ) {
        if ( ! growthReport.reports[ labels[i] ] ) {
            growthReport.reports[ labels[i] ] = {};
        }
    }
} else if ( 'custom' == growthReport.type ) {
    labels = Object.keys( growthReport.reports );
}

for ( var i = 0; i < labels.length; i++) {
    var tempData = growthReport.reports[ labels[i] ];

    if ( tempData ) {
        data[0].initial.push( tempData.initial ),
        data[1].learning.push( tempData.learning ),
        data[2].pending.push( tempData.pending ),
        data[3].dropped.push( tempData.dropped ),
        data[4].completed.push( tempData.completed )
    }
}

// Reference the chart canvas
var ctx = document.getElementById('growth-chart').getContext('2d');

var chart = new Chart(ctx, {
    type: 'bar',

    data: {
        labels: labels,
        datasets: [
            {
                label: __('Initial', 'erp'),
                backgroundColor: 'rgba(3,169,244, .5)',
                borderColor: 'rgba(3,169,244, .5)',
                data: data[0].initial
            },
            {
                label: __('Learning', 'erp'),
                backgroundColor: 'rgba(103,58, 183, .5)',
                borderColor: 'rgba(103,58, 183, .5)',
                data: data[1].learning
            },
            {
                label: __('Pending', 'erp'),
                backgroundColor: 'rgba(255,193,7, .5)',
                borderColor: 'rgba(255,193,7, .5)',
                data: data[2].pending
            },
            {
                label: __('Dropped', 'erp'),
                backgroundColor: 'rgba(244,67,54, .5)',
                borderColor: 'rgba(244,67,54, .5)',
                data: data[3].dropped
            },
            {
                label: __('Completed', 'erp'),
                backgroundColor: 'rgba(104,245,87, .5)',
                borderColor: 'rgba(104,245,87, .5)',
                data: data[4].completed
            }
        ]
    },

    options: {
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                stacked: true,
                gridLines: {
                    display: false
                 }
            }],
            yAxes: [{
                stacked: true,
                gridLines: {
                    display: true
                 }
            }]
        }
    }
});
