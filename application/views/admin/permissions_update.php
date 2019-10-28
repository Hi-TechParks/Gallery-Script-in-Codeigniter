
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_change_permission')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
        <li><a class="btn btn-danger" href="<?=site_url('admin/permissions')?>"><?=lang('admin_permissions')?></a></li>
        <li class="active"><?=lang('admin_change_permission')?></li>
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
echo form_open("admin/permissions/update/" . $object->id, $attributes);
?>
             
                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label"><?=lang('admin_name')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('name', $object->name); ?>" type="text" class="form-control" name="name" placeholder="<?=lang('admin_name')?>">
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="codename" class="col-sm-3 control-label"><?=lang('admin_code_name')?></label>
                  <div class="col-sm-5">
                    <input readonly value="<?php echo set_value('codename', $object->codename); ?>" type="text" class="form-control" name="codename" placeholder="<?=lang('admin_code_name')?>">
                    <span class="text-danger"><?php echo form_error('codename'); ?></span>
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