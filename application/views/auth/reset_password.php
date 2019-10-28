<div class="row">
        <div class="col-md-12">
    <div class="text-center">
          <h3><?=lang('auth_reset_password')?></h3>
          <p><?=lang('auth_fill_in_form')?></p>
          <?php echo $this->session->flashdata('msg'); ?>
        </div>
            </div>
        </div>
    <br/>

<div>

<?php 
$attributes = array("class" => "form-signin", "name" => "form"); 
echo form_open("auth/reset_password" . $token_uuid_url_part, $attributes);
?>
        <label for="password" class="sr-only"><?=lang('auth_password')?></label>
        <input type="password" id="password" name="password" class="form-control" placeholder="<?=lang('auth_password')?>">
        <?php echo form_error('password'); ?>
        <label for="password_confirm" class="sr-only"><?=lang('auth_confirm_password')?></label>
        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="<?=lang('auth_confirm_password')?>">
        <?php echo form_error('password_confirm'); ?>
        <br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?=lang('admin_submit')?></button>
        <br/>
        <p class="text-center"><a href="<?=site_url('students/login')?>"><?=lang('app_student_login')?></a> | <a href="<?=site_url('auth')?>"><?=lang('app_staff_login')?></p>
      </form>

<?php echo form_close(); ?>