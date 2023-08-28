$(document).ready(function () {
    refresh_datatable_organization_amenitie();
});

function refresh_datatable_organization_amenitie(){

    if ($.fn.DataTable.isDataTable('#datatable-organization_amenitie')) {
        $('#datatable-organization_amenitie').DataTable().destroy();
    }

    // datatables
    tableaaa = $('#datatable-organization_amenitie').DataTable({


        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/organization_Amenitie/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#name').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [2], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-organization_amenitie thead th').eq(column.index()).text();
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
    
$('body').on('click', '.organization_amenitie_delete_confirmation', function(e) {
    e.preventDefault();
    $('#organization_amenitie_id').val($(this).attr('data-id'));
});

$('body').on('click', '#organization-amenitie-delete-save', function(e) {
    e.preventDefault();
    var organization_amenitie_id = $('#organization_amenitie_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/organization_Amenitie/delete",
        data: {organization_amenitie_id: organization_amenitie_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#organization_amenitie_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_organization_amenitie();
            }else{
                $('#organization_amenitie_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_organization_amenitie();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});
