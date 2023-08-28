$(document).ready(function () {
    $('.hide_show_crop_div').hide();
    $('.show-tick').selectpicker('refresh');
    refresh_datatable_posts();
});

function refresh_datatable_posts() {
    if ($.fn.DataTable.isDataTable('#datatable-posts')) {
        $('#datatable-posts').DataTable().destroy();
    }
    // datatables
    table_category = $('#datatable-posts').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        responsive: true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/articles/ajax_list",
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
                inputTitle = $('#datatable-posts thead th').eq(column.index()).text();
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

$('body').on('click', '.post_delete_confirmation', function(e) {
    e.preventDefault();
    $('#post_id').val($(this).attr('data-id'));
});

$('body').on('click', '#post-delete-save', function(e) {
    e.preventDefault();
    var post_id = $('#post_id').val();
    console.log(post_id);
    $.ajax({
        type: "post",
        url: base_url+"admin/articles/delete",
        data: {post_id: post_id},
        dataType: "json",
        success: function (data) {
            if(data.status == 1){
                $('#post_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_posts();
            }else{
                $('#post_deleted').modal('hide');
                alert(data.message);
                refresh_datatable_posts();
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
});

function validate_profile(input) {
    var field_id = input.id;
    var data_id = $(input).attr('data-id');
    if ((file = input.files[0])) {
      if(file.size >= 10000000)
      {
        $('#'+field_id).val("");
        $('.hide_show_crop_div').hide();
        alert('Not allowed file size more than 10 MB !');
        return false;
      }

      if (!(file.type === 'image/jpg' || file.type === 'image/jpeg' || file.type === 'image/png')) {
          $('#'+field_id).val("");
          $('.hide_show_crop_div').hide();
          alert('Please choose png or jpg or jpeg');
          return false;
      }

      $('.hide_show_crop_div').show();
      var reader = new FileReader();
      reader.onload = function (e) {
        var i_height,i_width;
        var image = new Image();
        image.src = e.target.result;
        image.onload = function() {
            i_height = this.height;
            i_width = this.width;
            if (i_height < 150) {
              alert("Not Allowed. The height less than 150 pixels and width 300 pixels","");
              $('.hide_show_crop_div').hide();
              $('#'+field_id).val("");
              return false;
            }

            if (i_width < 300) {
              alert("Not Allowed. The height less than 150 pixels and width 300 pixels","");
              $('.hide_show_crop_div').hide();
              $('#'+field_id).val("");
              return false;
            }
        };

        $('.cropper').cropper('replace', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);

      load_cropper_module_for_post(data_id);
    }
}

function load_cropper_module_for_post(data_id) {
    var $cropper = $(".cropper"),

  $dataX = $('#dataX_'+data_id),

  $dataY = $('#dataY_'+data_id),

  $dataHeight = $('#dataHeight_'+data_id),

  $dataWidth = $('#dataWidth_'+data_id),

  $dataRotate = $('#dataRotate_'+data_id),

  console = window.console || {log:$.noop},

  cropper;



    $cropper.cropper({

        zoomable: false,

        aspectRatio: 10/7,

            data: {

                x: 0,

                y: 0,

                width: 0,

                height: 0

            },

            preview: ".preview",



            // autoCrop: false,

            // dragCrop: false,

            // modal: false,

            // moveable: false,

            // resizeable: false,

            // scalable: false,



            // maxWidth: 1440,

            // maxHeight: 810,

            minWidth: 1024,

            minHeight: 720,



            done: function(data) {

                $dataX.val(data.x);

                $dataY.val(data.y);

                $dataHeight.val(data.height);

                $dataWidth.val(data.width);

                $dataRotate.val(data.rotate);

            },

            build: function(e) {

                console.log(e.type);

            },

            built: function(e) {

                console.log(e.type);

            },

            dragstart: function(e) {

                console.log(e.type);

            },

            dragmove: function(e) {

                console.log(e.type);

            },

            dragend: function(e) {

                console.log(e.type);

            }
    });

    cropper = $cropper.data("cropper");



    $cropper.on({

        "build.cropper": function(e) {

            // e.preventDefault();

        },

        "built.cropper": function(e) {

            console.log(e.type);

            // e.preventDefault();

        },

        "dragstart.cropper": function(e) {

            console.log(e.type);

            // e.preventDefault();

        },

        "dragmove.cropper": function(e) {

            console.log(e.type);

            // e.preventDefault();

        },

        "dragend.cropper": function(e) {

            console.log(e.type);

            // e.preventDefault();

        }

    });



    $("#reset").click(function() {

        $cropper.cropper("reset");

    });



    $("#reset2").click(function() {

        $cropper.cropper("reset", true);

    });



    $("#clear").click(function() {

        $cropper.cropper("enable");

        $cropper.cropper("clear");

    });



    $("#destroy").click(function() {

        $cropper.cropper("destroy");
    });



    $("#enable").click(function() {

        $cropper.cropper("enable");

    });



    $("#disable").click(function() {

        $cropper.cropper("disable");

    });



    $("#zoomIn").click(function() {

        $cropper.cropper("zoom", 0.1);

    });



    $("#zoomOut").click(function() {

        $cropper.cropper("zoom", -0.1);

    });



    $("#rotateLeft").click(function() {

        $cropper.cropper("rotate", -90);

    });



    $("#rotateRight").click(function() {

        $cropper.cropper("rotate", 90);

    });



    $("#setAspectRatio").click(function() {

        $cropper.cropper("setAspectRatio", $("#aspectRatio").val());

    });



    $("#replace").click(function() {

        $cropper.cropper("replace", $("#replaceWith").val());

    });



    $("#getImageData").click(function() {

        $("#showImageData").val(JSON.stringify($cropper.cropper("getImageData")));

    });



    $("#setData").click(function() {

        $cropper.cropper("setData", {

            x: $dataX.val(),

            y: $dataY.val(),

            width: $dataWidth.val(),

            height: $dataHeight.val(),

            rotate: $dataRotate.val()
        });
    });



    $("#getData").click(function() {

        $("#showData").val(JSON.stringify($cropper.cropper("getData")));
    });



    $("#getDataURL").click(function() {

        var dataURL = $cropper.cropper("getDataURL");



        $("#dataURL").text(dataURL);

        $("#showDataURL").html('<img src="' + dataURL + '">');
    });



    $("#getDataURL2").click(function() {

        var dataURL = $cropper.cropper("getDataURL", "image/jpeg");



        $("#dataURL").text(dataURL);

        $("#showDataURL").html('<img src="' + dataURL + '">');
    });

}

function checkNumberOnly(e) {
    -1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()
}

function checkCharacterOnly(event) {
    var inputValue = event.which;

    if((!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0 && inputValue != 8 && inputValue != 9)) || (inputValue >= 96 && inputValue <= 105)) { 
        event.preventDefault(); 
    }
}