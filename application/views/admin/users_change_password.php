
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_change_my_password')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
        <li><a class="btn btn-danger" href="<?=site_url('admin/users')?>"><?=lang('admin_users')?></a></li>
        <li class="active"><?=lang('admin_change_my_password')?></li>
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
echo form_open("admin/users/change_password", $attributes);
?>
              
                
                <div class="form-group">
                  <label for="current_password" class="col-md-3 control-label"><?=lang('admin_current_password')?></label>
                  <div class="col-md-5">
                      <input class="form-control" name="current_password" placeholder="<?=lang('admin_current_password')?>" type="password" value="" />
                      <span class="text-danger"><?php echo form_error('current_password'); ?></span>
                  </div>
              </div>

                <div class="form-group">
                  <label for="password" class="col-md-3 control-label"><?=lang('admin_password')?></label>
                  <div class="col-md-5">
                      <input class="form-control" name="password" placeholder="<?=lang('admin_password')?>" type="password" value="" />
                      <span class="text-danger"><?php echo form_error('password'); ?></span>
                  </div>
              </div>

              <div class="form-group">
                  <label for="password_confirm" class="col-md-3 control-label"><?=lang('admin_confirm_password')?></label>
                  <div class="col-md-5">
                      <input class="form-control" name="password_confirm" placeholder="<?=lang('admin_confirm_password')?>" type="password" value="" />
                      <span class="text-danger"><?php echo form_error('password_confirm'); ?></span>
                  </div>
              </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-5">
                    <button type="submit" class="btn btn-info">Submit</button>
                  </div>
                </div>


              
            </form>
              
        
        
              


            <!-- End of page content -->
        
            </div>
          </div>
        </div>
      </div>