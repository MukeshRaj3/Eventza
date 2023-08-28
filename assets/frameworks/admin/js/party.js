$(document).ready(function () {
    refresh_datatable_cities();
});

function refresh_datatable_cities(){
    if ($.fn.DataTable.isDataTable('#datatable-cities')) {
        $('#datatable-cities').DataTable().destroy();
    }
    // datatables
    tableaaa = $('#datatable-cities').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/party/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.Title = $('#Title').val();
                data.Description = $('#Descrition').val();
                data.Start_date = $('#Start_date').val();
                data.Start_date = $('#End_date').val();
                data.Status = $('#Status').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [5,6], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-cities thead th').eq(column.index()).text();
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

};


$('body').on('click', '.party_delete_confirmation', function(e) {
    e.preventDefault();
    $('#party_id').val($(this).attr('data-id'));
});
$('body').on('click', '#party-delete-save', function(e) {
    e.preventDefault();
    var party_id = $('#party_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/delete",
        data: {party_id:party_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#party_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#party_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});
    
$('body').on('click', '.cities_delete_confirmation', function(e) {
    e.preventDefault();
    $('#party_id').val($(this).attr('data-id'));
});

$('body').on('click', '#party-amenitie-delete-save', function(e) {
    e.preventDefault();
    var city_id = $('#city_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/Party_Amenitie/delete",
        data: {city_id: city_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#party_amenitie_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#party_amenitie_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});


$('body').on('click', '.party_view', function(e) {
    e.preventDefault();
    var party_id = $(this).attr('data-id');
    $.ajax({
        type: "post",
        url: base_url+"admin/party/party_details",
        data: {party_id: party_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#show_data').html(data.data);
            }else{
                // $('#cities_deleted').modal('hide');
                // alert(data.message);
                // refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

function update_like(p_id)
{
    var like = $('#like'+p_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/update_data",
        data: {party_id:p_id,like:like},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_like'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_like'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function update_ongoing(p_id)
{
    var ongoing = $('#ongoing'+p_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/update_data",
        data: {party_id:p_id,ongoing:ongoing},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_ongoing'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_ongoing'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function update_view(p_id)
{
    var view = $('#view'+p_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/update_data",
        data: {party_id:p_id,view:view},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_view'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_view'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

$('body').on('click', '.party_approval_confirmation', function(e) {
    e.preventDefault();
    $('#party_id').val($(this).attr('data-id'));
});

$('body').on('click', '#party-approval-save', function(e) {
    e.preventDefault();
    var party_id = $('#party_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/changes_approval_status",
        data: {party_id: party_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#party_approval').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#party_approval').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

$('body').on('click', '.party_approval_image_confirmation', function(e) {
    e.preventDefault();
    $('#party_id').val($(this).attr('data-id'));
});

$('body').on('click', '#party-image-approval-save', function(e) {
    e.preventDefault();
    var party_id = $('#party_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/changes_approval_image_status",
        data: {party_id: party_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#party_image_approval').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#party_image_approval').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

function party_status_pr(p_id)
{
    var party_status_pr = $('#party_status_pr'+p_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/party/update_data",
        data: {party_id:p_id,papular_status:party_status_pr},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_cities();
            }else{
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}

function active_status(p_id)
{
    var active_status = $('#active_status'+p_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/update_data",
        data: {party_id:p_id,active:active_status},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                //$('#update_view'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                //$('#update_view'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function changes_approval_image_status(p_id)
{
    var image_status = $('#image_status'+p_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party/changes_approval_image_status",
        data: {party_id:p_id,image_status:image_status},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                //$('#update_view'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                //$('#update_view'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function send_remark(p_id)
{
    if(confirm("Are you sure you want to send the party remark ?")){
    var description =$('textarea#remark_message'+p_id).val();    
    $.ajax({
        type: "post",
        url: base_url+"admin/party/send_remark",
        data: {p_id:p_id,description:description},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                $('#send_remark'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#send_remark'+p_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}