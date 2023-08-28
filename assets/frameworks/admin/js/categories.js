$(document).ready(function () {
    refresh_datatable_categories();
});

function refresh_datatable_categories() {
    if ($.fn.DataTable.isDataTable('#datatable-categories')) {
        $('#datatable-categories').DataTable().destroy();
    }
    // datatables
    table_category = $('#datatable-categories').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/categories/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.category_name = $('#CategoryName').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "40%", "targets": 0},
            {"targets": [1, 2], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-categories thead th').eq(column.index()).text();
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
}

$('body').on('click', '.category_delete_confirmation', function(e) {
    e.preventDefault();
    $('#category_id').val($(this).attr('data-id'));
});

$('body').on('click', '#category-delete-save', function(e) {
    e.preventDefault();
    var category_id = $('#category_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/categories/delete",
        data: {category_id: category_id},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                $('#category_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_categories();
            }else{
                $('#category_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_categories();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});