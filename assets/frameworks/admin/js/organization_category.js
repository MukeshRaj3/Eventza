$(document).ready(function () {
    refresh_datatable_organization_category();
});

function refresh_datatable_organization_category(){

    if ($.fn.DataTable.isDataTable('#datatable-organization_category')) {
        $('#datatable-organization_category').DataTable().destroy();
    }

    // datatables
    tableaaa = $('#datatable-organization_category').DataTable({


        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/organization_category/ajax_list",
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
                inputTitle = $('#datatable-organization_category thead th').eq(column.index()).text();
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
    
$('body').on('click', '.Organization_Category_delete_confirmation', function(e) {
    e.preventDefault();
    $('#organization_category_id').val($(this).attr('data-id'));
});

$('body').on('click', '#organization_category-delete-save', function(e) {
    e.preventDefault();
    var organization_category_id = $('#organization_category_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/organization_category/delete",
        data: {organization_category_id: organization_category_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#Organization_Category_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_organization_category();
            }else{
                $('#Organization_Category_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_organization_category();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});
