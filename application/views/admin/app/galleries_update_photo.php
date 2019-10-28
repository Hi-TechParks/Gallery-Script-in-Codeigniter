
            <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('app_change_gallery')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
               <li><a class="btn btn-danger" href="<?=site_url('admin/galleries')?>"><?=lang('app_galleries')?></a></li>
               <li><a class="btn btn-danger" href="<?=site_url('admin/galleries/photos/' . $object->gallery_id)?>"><?=lang('app_gallery')?></a></li>
        <li class="active"><?=lang('app_change_photo')?></li>
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
echo form_open("admin/galleries/photo_update/" . $object->id, $attributes);
?>
                

                <div class="form-group">
                  <label for="name" class="col-sm-3 control-label"><?=lang('admin_name')?></label>
                  <div class="col-sm-5">
                    <input value="<?php echo set_value('name', $object->name); ?>" type="text" class="form-control" name="name" placeholder="<?=lang('admin_name')?>">
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="content" class="col-sm-3 control-label"><?=lang('app_description')?></label>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="description" placeholder="<?=lang('app_description')?>"><?php echo set_value('description', $object->description); ?></textarea>
                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="is_cover_photo" class="col-sm-3 control-label"><?=lang('app_is_cover_photo')?></label>
                  <div class="col-sm-5">
                    <input <?=is_checked($is_cover_photo)?> value="1" name="is_cover_photo" type="checkbox" data-toggle="toggle" data-on="<span class='glyphicon glyphicon-ok'></span> Yes" data-off="<span class='glyphicon glyphicon-remove'></span> No" data-onstyle="success" data-offstyle="default">

                    <span class="text-danger"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-5">
                    <a href="<?=base_url('uploads/photos/' . $object->location);?>" data-toggle="lightbox">
                      <img class="img-responsive" src="<?=base_url('uploads/photos/' . $object->location);?>">
                    </a>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-5">
                    <button type="submit" class="btn btn-info"><?=lang('admin_submit')?></button><a class="btn btn-danger pull-right" onclick="return confirm('<?=lang('admin_delete_prompt')?>');" href="<?php echo site_url('admin/galleries/delete_photo/' . $object->id)?>"><?=lang('admin_delete')?></a>
                  </div>
                </div>
            </form>


            <!-- End of page content -->
        
            </div>
          </div>
        </div>
      </div>