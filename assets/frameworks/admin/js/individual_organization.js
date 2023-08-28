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
            "url": base_url+"admin/individual_organization/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
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
    
$('body').on('click', '.organization_delete_confirmation', function(e) {
    e.preventDefault();
    $('#organization_id').val($(this).attr('data-id'));
});

$('body').on('click', '#organization-delete-save', function(e) {
    e.preventDefault();
    var organization_id = $('#organization_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/delete",
        data: {organization_id: organization_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#organization_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#organization_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});


$('body').on('click', '.organization_view', function(e) {
    e.preventDefault();
    var organization_id = $(this).attr('data-id');
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/organization_details",
        data: {organization_id: organization_id},
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

function update_like(org_id)
{
    var like = $('#like'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/update_data",
        data: {org_id:org_id,like:like},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_like'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_like'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function update_ongoing(org_id)
{
    var ongoing = $('#ongoing'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/update_data",
        data: {org_id:org_id,ongoing:ongoing},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_ongoing'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_ongoing'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function update_view(org_id)
{
    var view = $('#view'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/update_data",
        data: {org_id:org_id,view:view},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_view'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_view'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

}
function update_rating(org_id)
{
    var rating = $('#rating'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/update_data",
        data: {org_id:org_id,rating:rating},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_rating'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_rating'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}


$(document).ready(function () {
    refresh_datatable_org_pdf();
});

function refresh_datatable_org_pdf(){
    if ($.fn.DataTable.isDataTable('#datatable-org_pdf')) {
        $('#datatable-org_pdf').DataTable().destroy();
    }
    // datatables
    tableaaa = $('#datatable-org_pdf').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/individual_organization/ajax_list_org_pdf",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
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
            {"targets": [1], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-org_pdf thead th').eq(column.index()).text();
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

function pdf_status (pdf_id)
{
    var pdf_status = $('#pdf_status'+pdf_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/pdf_update_data",
        data: {pdf_id:pdf_id,status:pdf_status},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_org_pdf();
            }else{
                alert(data.message);
                refresh_datatable_org_pdf();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}

/*-----------------PDF verification code-------------------------*/
$(document).ready(function () {
    refresh_datatable_org_pdf_verification();
});

function refresh_datatable_org_pdf_verification(){
    if ($.fn.DataTable.isDataTable('#datatable-org_pdf_verification')) {
        $('#datatable-org_pdf_verification').DataTable().destroy();
    }
    // datatables
    tableaaa = $('#datatable-org_pdf_verification').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/individual_organization/ajax_list_user_org_pdf_verification",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
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
            {"targets": [1], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-org_pdf_verification thead th').eq(column.index()).text();
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

function pdf_a_status(pdf_id)
{
    var pdf_status = $('#pdf_a_status'+pdf_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/pdf_verification_status_update_data",
        data: {pdf_id:pdf_id,pdf_a_status:pdf_status},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_org_pdf_verification();
            }else{
                alert(data.message);
                refresh_datatable_org_pdf_verification();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}

function pdf_b_status(pdf_id)
{
    var pdf_status = $('#pdf_b_status'+pdf_id).val();
    if(confirm("Are you sure you want to change the status ?")){
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/pdf_verification_status_update_data",
        data: {pdf_id:pdf_id,pdf_b_status:pdf_status},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                alert(data.message);
                refresh_datatable_org_pdf_verification();
            }else{
                alert(data.message);
                refresh_datatable_org_pdf_verification();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
 }
}

function changes_profile_pic_approval_status(org_id)
{
     if(confirm("Are you sure you want to change the status ?")){
    var profile_pic_approval_status = $('#profile_pic_approval_status'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/changes_approval_image_status",
        data: {org_id:org_id,profile_pic_approval_status:profile_pic_approval_status},
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
}

$('body').on('click', '.organization_approval_confirmation', function(e) {
    e.preventDefault();
    $('#organization_id').val($(this).attr('data-id'));
});

$('body').on('click', '#organization-approval-save', function(e) {
    e.preventDefault();
    var organization_id = $('#organization_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/changes_approval_status",
        data: {organization_id: organization_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#organization_approval').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#organization_approval').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

function send_remark(org_id)
{
    if(confirm("Are you sure you want to send the organization remark ?")){
    var description =$('textarea#remark_message'+org_id).val();    
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/send_remark",
        data: {org_id:org_id,description:description},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                $('#send_remark'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#send_remark'+org_id).modal('hide');
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


function changes_bluetick_status(org_id)
{
     if(confirm("Are you sure you want to change the status ?")){
    var bluetick_status = $('#bluetick_status'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/changes_bluetick_status",
        data: {org_id:org_id,bluetick_status:bluetick_status},
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
}

function update_display_name(org_id)
{
    var display_name = $('#display_name'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/update_data",
        data: {org_id:org_id,display_name:display_name},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#update_display_name'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#update_display_name'+org_id).modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function changes_display_status(org_id)
{
     if(confirm("Are you sure you want to change the status ?")){
    var display_status = $('#display_status'+org_id).val();
    $.ajax({
        type: "post",
        url: base_url+"admin/individual_organization/changes_display_status",
        data: {org_id:org_id,display_status:display_status},
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
}