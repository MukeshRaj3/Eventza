$(document).ready(function () {
    refresh_datatable_user();
});

$(document).ready(function () {
    refresh_datatable_users_deactivation_request();
    
});


$(document).ready(function () {
    refresh_datatable_users_block_report();
    
});

function refresh_datatable_user(){

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#datatable-users')) {
        $('#datatable-users').DataTable().destroy();
    }
    // datatables
    tableaaa = $('#datatable-users').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
                data.Email = $('#Email').val();
                data.Phone = $('#Phone').val();
                data.LastLogin = $('#LastLogin').val();
                data.CreatedOn = $('#CreatedOn').val();
                data.Status = $('#Status').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [3,4], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0, 1, 2]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-users thead th').eq(column.index()).text();
                $('<input type="text" style="width: 150px;" class="form-control" id="' + inputTitle.replace(/\s+/g, '') + '" placeholder="' + placeholder + '" />')
                .appendTo($(column.footer()).empty())
                .on('keyup change', function () {
                    if (column.search() !== this.value) {
                        column
                                .search(this.value)
                                .draw();
                    }
                });
            });
        }
    });
});
}

function user_status(u_id)
{
    var user_status = $('#user_status'+u_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/users/update_data",
        data: {u_id:u_id,active:user_status},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_user();
            }else{
                alert(data.message);
                refresh_datatable_user();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}

function refresh_datatable_users_deactivation_request(){

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#datatable-users-deactivation-request')) {
        $('#datatable-users-deactivation-request').DataTable().destroy();
    }

    // datatables
    tableaaa = $('#datatable-users-deactivation-request').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/ajax_list_deactivation_request",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
                data.Email = $('#Email').val();
                data.Phone = $('#Phone').val();
                data.LastLogin = $('#LastLogin').val();
                data.CreatedOn = $('#CreatedOn').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [3,4], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0, 1, 2]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-users-deactivation-request thead th').eq(column.index()).text();
                $('<input type="text" style="width: 150px;" class="form-control" id="' + inputTitle.replace(/\s+/g, '') + '" placeholder="' + placeholder + '" />')
                .appendTo($(column.footer()).empty())
                .on('keyup change', function () {
                    if (column.search() !== this.value) {
                        column
                                .search(this.value)
                                .draw();
                    }
                });
            });
        }
    });
});
}

function status(udr_id)
{
    var status = $('#status'+udr_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/users/update_deactivation_request",
        data: {udr_id:udr_id,status:status},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_users_deactivation_request();
            }else{
                alert(data.message);
                refresh_datatable_users_deactivation_request();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}

function refresh_datatable_users_block_report(){

$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#datatable-users-block-report')) {
        $('#datatable-users-block-report').DataTable().destroy();
    }

    // datatables
    tableaaa = $('#datatable-users-block-report').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/ajax_list_block_report",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
                data.Email = $('#Email').val();
                data.Phone = $('#Phone').val();
                data.LastLogin = $('#LastLogin').val();
                data.CreatedOn = $('#CreatedOn').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [3,4], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0, 1, 2]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-users-block-report thead th').eq(column.index()).text();
                $('<input type="text" style="width: 150px;" class="form-control" id="' + inputTitle.replace(/\s+/g, '') + '" placeholder="' + placeholder + '" />')
                .appendTo($(column.footer()).empty())
                .on('keyup change', function () {
                    if (column.search() !== this.value) {
                        column
                                .search(this.value)
                                .draw();
                    }
                });
            });
        }
    });
});
}

function status_block_report(udr_id)
{
    var status = $('#status'+udr_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/users/update_block_report",
        data: {udr_id:udr_id,status:status},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_users_block_report();
            }else{
                alert(data.message);
                refresh_datatable_users_block_report();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}