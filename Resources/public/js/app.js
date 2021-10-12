$(document).ready( function () {
    if (locale === 'fr') {
        locale = 'French'
    } else if (locale === 'en') {
        locale = 'English'
    } else if (locale === 'nl') {
        locale = 'Dutch'
    }

    $('.fluffy-user-stats-table').DataTable({
        order: [[0, 'asc']],
        responsive: {
            details: true
        },
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + locale + ".json"
        },
        columnDefs: [{
            targets: 'no-sort',
            orderable: false,
            order: []
        }]
    });

    let $mostRouteViewed = $('#most-route-viewed');
    let dataRoutes = [];
    let routes = [];

    $.each($mostRouteViewed.data('json'), function (key, value) {
        dataRoutes.push(key);
        routes.push(value);
    });

    let chart = new ApexCharts($mostRouteViewed.get(0), {
        series: [{
            name: "nb",
            data: routes
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: dataRoutes,
            labels: {
                show: false,
            }
        }
    });
    chart.render();
} );