<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

		</div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <script type="text/javascript">
        	var base_url = "<?php echo base_url(); ?>";
            var baseUrl = '<?php echo base_url(); ?>';
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoV6qPEAsqAH2ff6MAUY_-xyZ-zfAVhs8&callback=initMap&libraries=places">
        </script>
        <!-- Vendor js -->
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/vendor.min.js"></script>
        <!-- App js-->
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/app.min.js"></script>

        <!-- third party js -->
	    <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/datatables/jquery.dataTables.min.js"></script>
	    <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/datatables/dataTables.bootstrap4.js"></script>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	    <!-- third party js ends -->
	    <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/jquery.dataTables.dtFilter.min.js"></script>
	    <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/dataTables.fixedHeader.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url($frameworks_dir.'/public'); ?>/js/jquery.validate.min.js"></script>
        
        
        <script src="<?php echo base_url($frameworks_dir . '/admin/js/'); ?>cloudflare/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url($frameworks_dir . '/admin/js/'); ?>cloudflare/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/cropper/cropper.min.js"></script>
        <?php if ($this->router->fetch_class() == 'users'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/users.js"></script>
        <?php endif; ?>
        <?php if ($this->router->fetch_class() == 'cities'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/cities.js"></script>
        <!-- Map Init Js -->
        <script src="<?php echo base_url($frameworks_dir . '/admin/js/map_init.js'); ?>" type="text/javascript"></script>
        <?php endif; ?>

        <?php if ($this->router->fetch_class() == 'party'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/party.js"></script>
        <?php endif; ?>
        <?php if ($this->router->fetch_class() == 'business'): ?>
            <!-- Map Init Js -->
            <script src="<?php echo base_url($frameworks_dir . '/public/js/map_init.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url($frameworks_dir); ?>/admin/libs/multi-image-picker/spartan-multi-image-picker-min.js"></script>
            <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/business.js?time=<?php echo time(); ?>"></script>
            <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/register.js"></script>
        <?php endif; ?>
        <?php if ($this->router->fetch_class() == 'facilities'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/facilities.js"></script>
        <?php endif; ?>
        <?php if ($this->router->fetch_class() == 'categories'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/categories.js"></script>
        <?php endif; ?>
        <?php if ($this->router->fetch_class() == 'articles'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/posts.js"></script>
        <?php endif; ?>
        <?php if ($this->router->fetch_class() == 'sports'): ?>
        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>js/sports.js"></script>
        <?php endif; ?>

        <script src="<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/ckeditor/ckeditor.js"></script>

        <script type="text/javascript">
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                startDate: new Date()
            });
        </script>

<?php if ($this->router->fetch_class() == 'cities') { ?>
    <script type="text/javascript">
        $( document ).ready(function() {
            var lat  = $('#address_latitude').val();
            var long = $('#address_longitude').val();
            initMap(lat, long);
        });
        
    </script>
    <script type="text/javascript">
        $('form').on('submit', function() {
            var lat  = $('#address_latitude').val();
            var long = $('#address_longitude').val();
            
            if(lat == "" || long == ""){
                swal("Please set marker on google map.","");
                return false;
            }else{
                return true;
            }
        });
    </script>
<?php } ?>
        <?php if ($this->router->fetch_class() == 'articles' && ($this->router->fetch_method() == 'create' || $this->router->fetch_method() == 'edit')): ?>
        <script type="text/javascript">
            CKEDITOR.replace('body',{
                toolbar : 'basic',
                uiColor : '#e3732c',
                //enterMode : CKEDITOR.ENTER_BR,
                autoParagraph : false,
                height: '400px',
                extraPlugins: 'html5video,div,justify',
                allowedContent: true,
                filebrowserBrowseUrl: '<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/filemanager/index.php'
            });
        </script>
        <?php endif; ?>

        <?php if ($this->router->fetch_class() == 'sports' && ($this->router->fetch_method() == 'create' || $this->router->fetch_method() == 'edit')): ?>
        <script type="text/javascript">
            CKEDITOR.replace('text_content',{
                toolbar : 'basic',
                uiColor : '#e3732c',
                //enterMode : CKEDITOR.ENTER_BR,
                autoParagraph : false,
                height: '400px',
                extraPlugins: 'html5video,div,justify',
                allowedContent: true,
                filebrowserBrowseUrl: '<?php echo base_url($frameworks_dir . '/admin/'); ?>libs/filemanager/index.php'
            });
        </script>
        <?php endif; ?>
    </body>
</html>