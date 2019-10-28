<div class="row">
        <div class="col-md-12">
    <div class="text-center">
          <h3><?=lang('app_staff_login')?></h3>
          <p><?=lang('auth_fill_in_form')?></p>
          <?php echo $this->session->flashdata('msg'); ?>
        </div>
            </div>
        </div>
    <br/>

<div>

<?php 
$attributes = array("class" => "form-signin", "name" => "form"); 
echo form_open("auth/change_password", $attributes);
?>
        <label for="username_email" class="sr-only"><?=lang('auth_username_email')?></label>
        <input type="text" id="username_email" name="username_email" class="form-control" placeholder="<?=lang('auth_username_email')?>">
        <?php echo form_error('username_email'); ?>
        <br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?=lang('admin_submit')?></button>
        <br/>
        <p class="text-center"><a href="<?=site_url('students/login')?>"><?=lang('app_student_login')?></a> | <a href="<?=site_url('auth')?>"><?=lang('app_staff_login')?></p>
      </form>

<?php echo form_close(); ?>