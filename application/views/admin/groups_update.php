
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_change_group')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
        <li><a class="btn btn-danger" href="<?=site_url('admin/groups')?>"><?=lang('admin_groups')?></a></li>
        <li class="active"><?=lang('admin_change_group')?></li>
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
$attributes = array("class" => "form-horizontal", "name" => "form"); 
echo form_open("admin/groups/update/" . $object->id, $attributes);
?>
              
                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label"><?=lang('admin_name')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('name', $object->name); ?>" type="text" class="form-control" name="name" placeholder="<?=lang('admin_name')?>">
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                  </div>
                </div>

                <?php
    $saved_permissions = $this->group_model->find_related('permissions', $object->id);
    ?>

                <div class="form-group">
                  <label for="codename" class="col-sm-3 control-label"><?=lang('admin_permissions')?></label>
                  <div class="col-sm-5">
                    <select class="form-control select2" style="width: 100%;" name="permissions[]" multiple="multiple" data-placeholder="<?=lang('admin_select_permissions')?>">
                    <?php foreach ($permissions['results'] as $permission) {
                      ?><option <?=select_multiple_option($this->input->post('permissions'), $permission->id, $saved_permissions)?> value="<?=$permission->id?>"><?=$permission->name?></option><?php
                    } ?>
                </select>
                    <span class="text-danger"><?php echo form_error('permissions'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-5">
                    <button type="submit" class="btn btn-info"><?=lang('admin_submit')?></button><a class="btn btn-danger pull-right" onclick="return confirm('<?=lang('admin_delete_prompt')?>');" href="<?php echo site_url('admin/groups/delete/' . $object->id)?>"><?=lang('admin_delete')?></a>
                  </div>
                </div>


              
            </form>
              
        
        
              


            <!-- End of page content -->
        
            </div>
          </div>
        </div>
      </div>