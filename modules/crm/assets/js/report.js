'use strict';

var labels;
var months = moment.months();
var data = [
    { 'l0': [] },
    { 'l1': [] },
    { 'l2': [] },
    { 'l3': [] },
    { 'l4': [] },
    { 'l5': [] },
    { 'l6': [] },
    { 'l7': [] },
    { 'l8': [] }
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
        data[0].l0.push( tempData.l0 ),
        data[1].l1.push( tempData.l1 ),
        data[2].l2.push( tempData.l2 ),
        data[3].l3.push( tempData.l3 ),
        data[4].l4.push( tempData.l4 ),
        data[5].l5.push( tempData.l5 ),
        data[6].l6.push( tempData.l6 ),
        data[7].l7.push( tempData.l7 ),
        data[8].l8.push( tempData.l8 )
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
                label: __('L0', 'erp'),
                backgroundColor: 'rgba(244,67,54, .5)',
                borderColor: 'rgba(244,67,54, .5)',
                data: data[0].l0
            },
            {
                label: __('L1', 'erp'),
                backgroundColor: 'rgba(244,67,54, .5)',
                borderColor: 'rgba(244,67,54, .5)',
                data: data[1].l1
            },
            {
                label: __('L2', 'erp'),
                backgroundColor: 'rgba(103,58, 183, .5)',
                borderColor: 'rgba(103,58, 183, .5)',
                data: data[2].l2
            },
            {
                label: __('L3', 'erp'),
                backgroundColor: 'rgba(103,58, 183, .5)',
                borderColor: 'rgba(103,58, 183, .5)',
                data: data[3].l3
            },
            {
                label: __('L4', 'erp'),
                backgroundColor: 'rgba(3,169,244, .5)',
                borderColor: 'rgba(3,169,244, .5)',
                data: data[4].l4
            },
            {
                label: __('L5', 'erp'),
                backgroundColor: 'rgba(3,169,244, .5)',
                borderColor: 'rgba(3,169,244, .5)',
                data: data[5].l5
            },
            {
                label: __('L6', 'erp'),
                backgroundColor: 'rgba(255,193,7, .5)',
                borderColor: 'rgba(255,193,7, .5)',
                data: data[6].l6
            },
            {
                label: __('L7', 'erp'),
                backgroundColor: 'rgba(255,193,7, .5)',
                borderColor: 'rgba(255,193,7, .5)',
                data: data[7].l7
            },
            {
                label: __('L8', 'erp'),
                backgroundColor: 'rgba(255,193,7, .5)',
                borderColor: 'rgba(255,193,7, .5)',
                data: data[8].l8
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
