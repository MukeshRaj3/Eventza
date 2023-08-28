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
            "url": base_url+"admin/cities/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.Name = $('#Name').val();
                data.Fullname = $('#Fullname').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [2,3,4], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0,1]).every(function () {
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
    
$('body').on('click', '.cities_delete_confirmation', function(e) {
    e.preventDefault();
    $('#city_id').val($(this).attr('data-id'));
});

$('body').on('click', '#city-delete-save', function(e) {
    e.preventDefault();
    var city_id = $('#city_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/cities/delete",
        data: {city_id: city_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#cities_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }else{
                $('#cities_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_cities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});
