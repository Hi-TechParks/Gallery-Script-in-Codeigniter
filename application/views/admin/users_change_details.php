
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_change_my_details')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
        <li><a class="btn btn-danger" href="<?=site_url('admin/users')?>"><?=lang('admin_users')?></a></li>
        <li class="active"><?=lang('admin_change_my_details')?></li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">



              <?php 
        if(validation_errors()){
          ?><div class="alert alert-danger text-center"><?=lang('admin_error_saving_msg')?></div><?php
        }else{
          echo $this->session->flashdata('msg');
        }
      ?>
        
         
        
        <?php 
$attributes = array("class" => "form-horizontal", "name" => "form", "enctype" => "multipart/form-data"); 
echo form_open("admin/users/change_details", $attributes);
?>
              
                <div class="form-group">
                  <label for="username" class="col-sm-3 control-label"><?=lang('admin_username')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('username', $object->username); ?>" type="text" class="form-control" name="username" placeholder="<?=lang('admin_username')?>">
                    <span class="text-danger"><?php echo form_error('username'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="email" class="col-sm-3 control-label"><?=lang('admin_email')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('email', $object->email); ?>" type="email" class="form-control" name="email" placeholder="<?=lang('admin_email')?>">
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="first_name" class="col-sm-3 control-label"><?=lang('admin_first_name')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('first_name', $object->first_name); ?>" type="text" class="form-control" name="first_name" placeholder="<?=lang('admin_first_name')?>">
                    <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="last_name" class="col-sm-3 control-label"><?=lang('admin_last_name')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('last_name', $object->last_name); ?>" type="text" class="form-control" name="last_name" placeholder="<?=lang('admin_last_name')?>">
                    <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="profile_photo" class="col-sm-3 control-label"><?=lang('app_profile_photo')?></label>
                  <div class="col-sm-5">
                    <input type="file" class="form-control" name="profile_photo" id="profile_photo">
                    <span class="text-danger"><?php echo form_error('profile_photo'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-5">
                    <button type="submit" class="btn btn-info"><?=lang('admin_submit')?></button>
                  </div>
                </div>


              
            </form>
              
        
        
              


            <!-- End of page content -->
        
            </div>
          </div>
        </div>
      </div>