$(function() {
    // Product table JS setup
    var dataTable = $('.table-filter.referrals').dataTable({
        iDisplayLength: 25,
        "oLanguage": {
            "sLengthMenu": 'Display <select>'+
            '<option value="25">25</option>'+
            '<option value="50">50</option>'+
            '<option value="100">100</option>'+
            '<option value="200">200</option>'+
            '<option value="-1">All</option>'+
            '</select> referrals',
            "sInfo": "Showing (_START_ to _END_) of _TOTAL_ Products"}
    }).columnFilter({
        aoColumns: [
            { type: "text" },
            { type: "text" },
            { type: "text" },
            { type: "text" },
            { type: "text" },
            { type: "text" }
        ]
    });


    // Hide and show columns when ajax slide happens
    var showCol = 3;
    $('#main-slide').on('show.cp-livePane-slide', function(e, data) {
        $('.dataTables_length').hide();

        for (var i = 0; i < dataTable.fnSettings().aoColumns.length; ++i) {
            if (i!==showCol) {
                dataTable.fnSetColumnVis( i, false);
            }
        }
        $('table.referrals, .dataTables_paginate, .dataTables_info').animate({width: "18%"}, data.speed);

        $('.dataTables_filter').hide();

        $('.dataTables_info').css({ paddingBottom: '50px'});

    });

    $('#main-slide').on('hide.cp-livePane-slide', function(e, data) {
        $('.dataTables_length').show();
        for (var i = 0; i < dataTable.fnSettings().aoColumns.length; ++i) {
            if (i!==showCol) {
                dataTable.fnSetColumnVis( i, true);
            }
        }
        $('table.referrals, .dataTables_paginate, .dataTables_info').animate({width: "100%"}, data.speed);

        $('.dataTables_filter').show();

        $('.dataTables_info').css({ paddingBottom: '20px'});
    });
});