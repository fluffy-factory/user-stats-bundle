$(document).ready( function () {
    if (locale === 'fr') {
        locale = 'French'
    } else if (locale === 'en') {
        locale = 'English'
    } else if (locale === 'nl') {
        locale = 'Dutch'
    }

    $('.fluffy-user-stats-table').DataTable({
        order: [[0, 'desc']],
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
} );