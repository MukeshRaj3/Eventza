$(document).ready(function () {
    refresh_datatable_facilities();
});

function refresh_datatable_facilities() {
    if ($.fn.DataTable.isDataTable('#datatable-facilities')) {
        $('#datatable-facilities').DataTable().destroy();
    }
    // datatables
    tableaaa = $('#datatable-facilities').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/facilities/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.Facility = $('#Facility').val();
                data.BusinessType = $('#BusinessType').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [2,3], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0, 1]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-facilities thead th').eq(column.index()).text();
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

$('body').on('click', '.facility_delete_confirmation', function(e) {
    e.preventDefault();
    $('#facility_id').val($(this).attr('data-id'));
});

$('body').on('click', '#facility-delete-save', function(e) {
    e.preventDefault();
    var facility_id = $('#facility_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/facilities/delete",
        data: {facility_id: facility_id},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                $('#facility_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_facilities();
            }else{
                $('#facility_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_facilities();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

function validateAndSetCrop(input) {
    var fileinput;
    var id = input.id;
    var data_id = $(input).attr('data-id');
    var ratioone = $(input).attr('data-ratioone');
    var ratiotwo = $(input).attr('data-ratiotwo');
    var maxsize = $(input).attr('data-maxsize');
    var minwidth = $(input).attr('data-minwidth');
    var minheight = $(input).attr('data-minheight');
    var maxheight = $(input).attr('data-maxheight');
    var maxwidth = $(input).attr('data-maxwidth');

    if(data_id == undefined || ratioone == undefined || ratiotwo == undefined || maxsize == undefined || minwidth == undefined || minheight == undefined){
        $('#'+id).val("");
        return false;
    }
    
    if (input.files && input.files[0]) {
        if ((fileinput = input.files[0])) {
            var validsize = maxsize * 1024;
            var filesize = fileinput.size / 1024;

            if(filesize >= validsize)
            {
                $('#'+id).val("");
                alert("Not allowed file size more than "+maxsize+" MB !","");
                return false;
            }

            if (!(fileinput.type === 'image/jpeg' || fileinput.type === 'image/png')) {
                
                $('#'+id).val("");
                alert("Not a valid file type ! Please select jpeg | png.","");
                return false;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                var i_height,i_width;
                var image = new Image();
                image.src = e.target.result;
                image.onload = function() {
                    i_height = this.height;
                    i_width = this.width;
                    if (i_height < minheight) {
                      $('#'+id).val("");
                      alert("Not Allowed. The height less than "+minheight+" pixels and width "+minwidth+" pixels","");
                      return false;
                    }

                    if (i_width < minwidth) {
                      $('#'+id).val("");
                      alert("Not Allowed. The height less than "+minheight+" pixels and width "+minwidth+" pixels","");
                      return false;
                    }

                    if (i_height > maxheight) {
                      $('#'+id).val("");
                      alert("Not Allowed. The height more than "+maxheight+" pixels and width "+maxwidth+" pixels","");
                      return false;
                    }

                    if (i_width > maxwidth) {
                      $('#'+id).val("");
                      alert("Not Allowed. The height more than "+maxheight+" pixels and width "+maxwidth+" pixels","");
                      return false;
                    }
                };
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}