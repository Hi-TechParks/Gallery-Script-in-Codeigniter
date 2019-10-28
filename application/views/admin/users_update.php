
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_change_user')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
               <li><a class="btn btn-danger" href="<?=site_url('admin/users')?>"><?=lang('admin_users')?></a></li>
        <li class="active"><?=lang('admin_change_user')?></li>
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
echo form_open("admin/users/update/" . $object->id, $attributes);
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
                  <label for="name" class="col-sm-3 control-label"><?=lang('admin_is_active')?></label>
                  <div class="col-sm-5">
                    <input <?=is_checked(set_value('is_active', $object->is_active))?> value="1" name="is_active" type="checkbox" data-toggle="toggle" data-on="<span class='glyphicon glyphicon-ok'></span> Yes" data-off="<span class='glyphicon glyphicon-remove'></span> No" data-onstyle="success" data-offstyle="default">

                    <span class="text-danger"><?php echo form_error('is_active'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="is_staff" class="col-sm-3 control-label"><?=lang('admin_is_staff')?></label>
                  <div class="col-sm-5">
                    <input <?=is_checked(set_value('is_staff', $object->is_staff))?> value="1" name="is_staff" type="checkbox" data-toggle="toggle" data-on="<span class='glyphicon glyphicon-ok'></span> Yes" data-off="<span class='glyphicon glyphicon-remove'></span> No" data-onstyle="success" data-offstyle="default">

                    <span class="text-danger"><?php echo form_error('is_staff'); ?></span>
                  </div>
                </div>

                <?php if(intval($this->user->is_superuser) == 1){?>

                <div class="form-group">
                  <label for="is_superuser" class="col-sm-3 control-label"><?=lang('admin_is_superuser')?></label>
                  <div class="col-sm-5">
                    <input <?=is_checked(set_value('is_superuser', $object->is_superuser))?> value="1" name="is_superuser" type="checkbox" data-toggle="toggle" data-on="<span class='glyphicon glyphicon-ok'></span> Yes" data-off="<span class='glyphicon glyphicon-remove'></span> No" data-onstyle="success" data-offstyle="default">

                    <span class="text-danger"><?php echo form_error('is_superuser'); ?></span>
                  </div>
                </div>

                <?php } ?>

                <?php
                $saved_groups = $this->user_model->find_related('groups', $object->id);
                ?>

                <div class="form-group">
                  <label for="codename" class="col-sm-3 control-label"><?=lang('admin_groups')?></label>
                  <div class="col-sm-5">
                    <select class="form-control select2" style="width: 100%;" name="groups[]" multiple="multiple" data-placeholder="<?=lang('admin_select_groups')?>">
                    <?php foreach ($groups['results'] as $group) {
                      ?><option <?=select_multiple_option($this->input->post('groups'), $group->id, $saved_groups)?> value="<?=$group->id?>"><?=$group->name?></option><?php
                    } ?>
                </select>
                    <span class="text-danger"><?php echo form_error('groups'); ?></span>
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
                    <a class="btn btn-danger pull-right" onclick="return confirm('<?=lang('admin_delete_prompt')?>');" href="<?php echo site_url('admin/users/delete/' . $object->id)?>"><?=lang('admin_delete')?></a>
                  </div>
                </div>


              
            </form>
              
        
        
              


            <!-- End of page content -->
        
            </div>
          </div>
        </div>
      </div>
