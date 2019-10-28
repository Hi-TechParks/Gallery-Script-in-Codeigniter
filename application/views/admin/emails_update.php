
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_change_email_template')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
        <li><a class="btn btn-danger" href="<?=site_url('admin/emails')?>"><?=lang('admin_email_templates')?></a></li>
        <li class="active"><?=lang('admin_change_email_template')?></li>
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
echo form_open("admin/emails/update/" . $object->id, $attributes);
?>
              
                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label"><?=lang('admin_name')?></label>
                  <div class="col-sm-5">
                    <input readonly value="<?php echo set_value('name', $object->name); ?>" type="text" class="form-control" name="name" placeholder="<?=lang('admin_name')?>">
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="slug" class="col-sm-3 control-label"><?=lang('admin_slug')?></label>
                  <div class="col-sm-5">
                    <input readonly value="<?php echo set_value('slug', $object->slug); ?>" type="text" class="form-control" name="slug" placeholder="<?=lang('admin_slug')?>">
                    <span class="text-danger"><?php echo form_error('slug'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="subject" class="col-sm-3 control-label"><?=lang('admin_subject')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('subject', $object->subject); ?>" type="text" class="form-control" name="subject" placeholder="<?=lang('admin_subject')?>">
                    <span class="text-danger"><?php echo form_error('subject'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label"><?=lang('admin_body')?></label>
                  <div class="col-sm-8">
                    <textarea type="text" class="form-control editor" name="body" placeholder="<?=lang('admin_body')?>"><?php echo set_value('body', $object->body); ?></textarea>
                    <span class="text-danger"><?php echo form_error('body'); ?></span>
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