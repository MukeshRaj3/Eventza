$(document).ready(function () {
    refresh_datatable_party_category();
});

function refresh_datatable_party_category(){

    if ($.fn.DataTable.isDataTable('#datatable-party_category')) {
        $('#datatable-party_category').DataTable().destroy();
    }

    // datatables
    tableaaa = $('#datatable-party_category').DataTable({


        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/Party_Category/ajax_list",
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
                inputTitle = $('#datatable-party_category thead th').eq(column.index()).text();
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
    
$('body').on('click', '.party_category_delete_confirmation', function(e) {
    e.preventDefault();
    $('#party_category_id').val($(this).attr('data-id'));
});

$('body').on('click', '#party_category-delete-save', function(e) {
    e.preventDefault();
    var party_category_id = $('#party_category_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/party_category/delete",
        data: {party_category_id: party_category_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#party_category_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_party_category();
            }else{
                $('#party_category_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_party_category();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});
