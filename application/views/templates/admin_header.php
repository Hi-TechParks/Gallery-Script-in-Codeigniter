<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$page_title;?></title>
    <link href="<?=base_url('assets/dashboard_v20/css/custom.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/bootstrap-icons.css')?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?=base_url('assets/js/select2/select2.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/js/lightbox/ekko-lightbox.css')?>">

    <link href="<?=base_url('assets/js/bootstrap-datepicker/css/datepicker.css');?>" rel="stylesheet" type="text/css" />

    <!-- Javascripts-->
    <script src="<?=base_url('assets/dashboard_v20/js/jquery-2.1.4.min.js')?>"></script>
    <script src="<?=base_url('assets/dashboard_v20/js/essential-plugins.js')?>"></script>
    <script src="<?=base_url('assets/dashboard_v20/js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('assets/dashboard_v20/js/plugins/pace.min.js')?>"></script>
    <script src="<?=base_url('assets/dashboard_v20/js/main.js')?>"></script>

    <script src="<?=base_url('assets/js/select2/select2.full.min.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-filestyle.min.js')?>" type="text/javascript"></script>

    <script src="<?=base_url('assets/js/lightbox/ekko-lightbox.js')?>" type="text/javascript"></script>

        <script>
      $(function () {
        $(".select2").select2();
      });
      function check_uncheck() {
        jQuery('.table_checkboxes').prop('checked', jQuery('#checkall').prop('checked'));
    }
    </script>

    <script>
      $(document).ready(function(){
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                    event.preventDefault();
                    $(this).ekkoLightbox();
                });
    });
      </script>

      <script src="<?=base_url('assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js');?>" type="text/javascript"></script>

      <script>
        $(function() {
            $( ".datepicker_input" ).datepicker({
                format: 'dd/mm/yyyy'
            });
        });

    </script>

    <script type="text/javascript" src="<?=base_url('assets/plupload-2.3.6/js/plupload.full.min.js');?>"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!--if lt IE 9
    script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
    script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
    -->
  </head>
  <body class="sidebar-mini fixed">
    <div class="wrapper">
      <!-- Navbar-->
      <header class="main-header hidden-print"><a class="logo" href="<?=site_url('admin/dashboard')?>"> <?=$this->config->item('site_name', 'site')?></a>
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button--><a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
          <!-- Navbar Right Menu-->
          <div class="navbar-custom-menu">
            <ul class="top-nav">
              <!-- User Menu-->
              <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu settings-menu">
                  <li><a href="<?=site_url('admin/users/change_password')?>"><i class="fa fa-user fa-lg"></i> <?=lang('admin_change_my_password')?></a></li>
                  <li><a href="<?=site_url('admin/users/change_details')?>"><i class="fa fa-user fa-lg"></i> <?=lang('admin_change_my_details')?></a></li>
                  <li><a href="<?=site_url('auth/logout')?>"><i class="fa fa-sign-out fa-lg"></i> <?=lang('admin_sign_out')?></a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Side-Nav-->
      <aside class="main-sidebar hidden-print">
        <section class="sidebar">
          <div class="user-panel">
            <?php if($auth_user_photo){ ?>
                <div class="pull-left image"><img class="img-circle" src="<?=base_url('assets/timthumb.php');?>?src=<?=$auth_user_photo?>&w=48&h=48" alt="User Image"></div>
              <?php }else{ ?>
                <div class="pull-left image"><img class="img-circle" src="<?=base_url('assets/timthumb.php');?>?src=<?=base_url('assets/profile_placeholder.png');?>&w=48&h=48" alt="User Image"></div>
              <?php } ?>
            <div class="pull-left info">
              <p><?=$auth_username?></p>
              <!-- <p class="designation">Registration</p> -->
            </div>
          </div>
          
      
      <!--  ********************* START - Sidebar Menu ************************* -->
          <ul class="sidebar-menu">
            <li <?=($active_menu == 'dash' ? 'class="active"' : '')?>><a href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i><span><?=lang('admin_dashboard')?></span></a></li>

            <li <?=($active_menu == 'gal' ? 'class="active"' : '')?>><a href="<?=site_url('admin/galleries')?>"><i class="fa fa-image"></i><span><?=lang('app_galleries')?></span></a></li>
           
            
      
            
      
            <li class="treeview"><a href="<?=site_url('admin/users')?>"><i class="fa fa-user-circle-o"></i><span><?=lang('admin_users_management')?></span><i class="fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="<?=site_url('admin/users')?>"><i class="fa fa-ellipsis-v"></i> <?=lang('admin_list_all_users')?></a></li>
                <li><a href="<?=site_url('admin/users/create')?>"><i class="fa fa-plus-square-o"></i> <?=lang('admin_create_new_user')?></a></li>
                <li><a href="<?=site_url('admin/groups')?>"><i class="fa fa-ellipsis-v"></i> <?=lang('admin_list_all_groups')?></a></li>
                <li><a href="<?=site_url('admin/groups/create')?>"><i class="fa fa-plus-square-o"></i> <?=lang('admin_create_new_group')?></a></li>
                <li><a href="<?=site_url('admin/permissions')?>"><i class="fa fa-ellipsis-v"></i><?=lang('admin_list_all_permissions')?></a></li>
                <li><a href="<?=site_url('admin/users/change_password')?>"><i class="fa fa-lock"></i> <?=lang('admin_change_my_password')?></a></li>
                <li><a href="<?=site_url('admin/users/change_details')?>"><i class="fa fa-user-o"></i> <?=lang('admin_change_my_details')?></a></li>
              </ul>
            </li>
      
            <li <?=($active_menu == 'email' ? 'class="active"' : '')?>><a href="<?=site_url('admin/emails')?>"><i class="fa fa-envelope-o"></i><span><?=lang('admin_email_templates')?></span></a></li>

          </ul>
        </section>
      </aside>