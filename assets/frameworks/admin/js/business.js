

$(document).ready(function () {
    initMultiImagePicker();

    var cropper;
var init_cropper = false;
function initMultiImagePicker() {
    $("#coba").spartanMultiImagePicker({
        fieldName:        'post_images[]',
        maxCount:         4,
        rowHeight:        '100px',
        groupClassName:   'col-md-3 col-sm-3 col-xs-3',
        maxFileSize:      '10000000',
        placeholderImage: {
            image: baseUrl + 'assets/frameworks/public/images/createpost-addmore-icon.svg',
            width : '35%'
        },
        dropFileLabel : "Drop Here",
        onAddRow:       function(index){
            console.log(index);
            console.log('add new row');
        },
        onRenderedPreview : function(index){
            console.log(index);
            console.log('preview rendered');
            if (init_cropper) {
                cropper.destroy();
                cropper = null;
                init_cropper = false;
            }
            var image = document.getElementById('sample_image');
            $(".show-image-input").append('<input type="hidden" name="post_images_crop[]" id="post_image_'+index+'">');
            var fileInc = $("#file_increment").val();
            fileInc = parseInt(fileInc) + 1;
            $("#file_increment").val(fileInc);
        },
        onRemoveRow : function(index){
            console.log(index);
            if (init_cropper) {
                cropper.destroy();
                cropper = null;
                init_cropper = false;
            }
            var fileInc = $("#file_increment").val();
            fileInc = parseInt(fileInc) - 1;
            $("#file_increment").val(fileInc);
            $("#post_image_" + index).remove();
            $(".hide_show_crop_div").hide();
        },
        onExtensionErr : function(index, file){
            console.log(index, file,  'extension err');
            alert('Please only input png or jpg type file')
        },
        onSizeErr : function(index, file){
            console.log(index, file,  'file size too big');
            alert('File size too big');
        }
    });
}

    business_list_datatable();
    $( "#business_type" ).select();
});

function business_list_datatable() {
    if ($.fn.DataTable.isDataTable('#datatable-businesses')) {
        $('#datatable-businesses').DataTable().destroy();
    }
    // datatables
    tableaaa = $('#datatable-businesses').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/business/ajax_list",
            "type": "POST",
            "data": function (data) {
                data.BusinessName = $('#BusinessName').val();
                data.Firstlineofaddress = $('#Firstlineofaddress').val();
                data.BusinessEmail = $('#BusinessEmail').val();
                data.Address = $('#Address').val();
                data.ContactNumber = $('#ContactNumber').val();
                data.Website = $('#Website').val();
                data.RegisteredOn = $('#RegisteredOn').val();
            }
        },
        //Set column definition initialisation properties.
        "aaSorting": [],
        "columnDefs": [
            {"width": "2%", "targets": 0},
            {"targets": [0, 1, 2, 3, 4, 5, 6, 7], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],
        initComplete: function () {
            this.api().columns([0, 1, 2, 3, 4]).every(function () {
                var column = this;
                var inputTitle = '';
                var placeholder = 'Search...';
                inputTitle = $('#datatable-businesses thead th').eq(column.index()).text();
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

$('body').on('click', '.business_rejection_confirmation', function(e) {
    e.preventDefault();
    $('#business_id').val($(this).attr('data-id'));
});

$('body').on('click', '#business-delete-save', function(e) {
    e.preventDefault();
    var business_id = $('#business_id').val();
    $.ajax({
        type: "post",
        url: base_url+"admin/business/deleted",
        data: {business_id: business_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if(data.status == 1){
                $('#business_deleted').modal('hide');
                business_list_datatable();
                alert(data.message);
            }else{
                $('#business_deleted').modal('hide');
                business_list_datatable();
                alert(data.message); 
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});


$('#business_type').change(function(){
    $('.load-business-facilities').html('');
    var business_facilities = $('#business_facilities').val();
    var id = $('#business_type').val();
    $.ajax({
        url : baseUrl + "/account/get_business_wise_facilities",
        method : "POST",
        data : {id: id},
        async : true,
        dataType : 'json',
        success: function(response){
        	if (response.status == 1) {
        		var data = response.data;
                var html = '';
	            $.each(data, function(key, value) {
                    stadium_array = business_facilities.split(",");
                    if (stadium_array.includes(value.id)) { 
                        html += '</br><div class="col-md-4 col-6 animated fadeInUp"><div class="d-flex align-items-center">';
                        html += '<input class="custom-control-input facilities" name="facilities[]" value="'+value.id+'" id="'+value.facility+'" type="checkbox" checked>';
                        html += '<label class="custom-control-label cursor" for="'+value.facility+'">'+value.facility+'</label></div></div>';
                    } else {
                        html += '</br><div class="col-md-4 col-6 animated fadeInUp"><div class="d-flex align-items-center">';
                        html += '<input class="custom-control-input facilities" name="facilities[]" value="'+value.id+'" id="'+value.facility+'" type="checkbox">';
                        html += '<label class="custom-control-label cursor" for="'+value.facility+'">'+value.facility+'</label></div></div>';
                    }
                    $('.load-business-facilities').html(html);
	            });
        	}
        }
    });
});

$('#business_type').select(function(){
    $('.load-business-facilities').html('');
    var business_facilities = $('#business_facilities').val();
    var id = $('#business_type').val();
    $.ajax({
        url : baseUrl + "/account/get_business_wise_facilities",
        method : "POST",
        data : {id: id},
        async : true,
        dataType : 'json',
        success: function(response){
        	if (response.status == 1) {
        		var data = response.data;
                var html = '';
	            $.each(data, function(key, value) {
                    // console.log(stadium.venue_id);
                    stadium_array = business_facilities.split(",");
                    if (stadium_array.includes(value.id)) { 
                        html += '</br><div class="col-md-4 col-6 animated fadeInUp"><div class="d-flex align-items-center">';
                        html += '<input class="custom-control-input facilities" name="facilities[]" value="'+value.id+'" id="'+value.facility+'" type="checkbox" checked>';
                        html += '<label class="custom-control-label cursor" for="'+value.facility+'">'+value.facility+'</label></div></div>';
                    } else {
                        html += '</br><div class="col-md-4 col-6 animated fadeInUp"><div class="d-flex align-items-center">';
                        html += '<input class="custom-control-input facilities" name="facilities[]" value="'+value.id+'" id="'+value.facility+'" type="checkbox">';
                        html += '<label class="custom-control-label cursor" for="'+value.facility+'">'+value.facility+'</label></div></div>';
                    }
                    $('.load-business-facilities').html(html);
	            });
        	}
        }
    });
});

function checkCharacterOnly(event) {
    var inputValue = event.which;

    if((!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0 && inputValue != 8 && inputValue != 9)) || (inputValue >= 96 && inputValue <= 105)) { 
        event.preventDefault(); 
    }
}

function checkNumberOnly(e) {
    -1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()
}

$(function () {
    'use strict'
    $.validator.addMethod('validUrl', function(value, element) {
        var url = $.validator.methods.url.bind(this);
        return url(value, element) || url('http://' + value, element);
    }, 'Please enter a valid URL');
    $('form[id="add_new_offer"]').validate({
        rules: {
            title: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        //For custom messages
        messages: {
            title: {
                required: "Title is required"
            },
            description: {
                required: "Description is required"
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            $('#loaderror').html('');
            $(this).valid();
            $('#pac_input_error').html('');
            $('#submit').attr('disabled', true);
            form.submit();
        }
    });
});


$('body').on('click', '.offer_delete_confirmation', function(e) {
    e.preventDefault();
    $('#offer_id').val($(this).attr('data-id'));
});

$('body').on('click', '#offer-delete-save', function(e) {
    e.preventDefault();
    var offer_id = $('#offer_id').val();
    console.log(offer_id);
    $.ajax({
        type: "post",
        url: base_url+"admin/business/offer_delete",
        data: {offer_id: offer_id},
        dataType: "json",
        success: function (data) {
            console.log(data);
            if (data.status == 1) {
                location.reload();
            } else {
                location.reload();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

function cuisineItemUnavailable(e) {
    var id = $(e).attr("data-itemid");
    if ($('#cuisine_item_'+id).is(":checked")) {
        var status = 1;
        $('.cuisine_item_'+id).html('on');

    } else {
        var status = 0;
        $('.cuisine_item_'+id).html('off');
    }
    $.ajax({
        type: "post",
        data: {
            status: status,
            offer_id : id,
        },
        url: baseUrl+'admin/business/change_offer_status',
        dataType: "json",
        success: function (data) {
        },
        error: function () {

        }
    });
}