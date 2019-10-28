<div class="row">
        <div class="col-md-12">
    <div class="text-center">
          <h3><?=lang('auth_registration')?></h3>
          <p><?=lang('auth_fill_in_form')?></p>
          <?php echo $this->session->flashdata('msg'); ?>
        </div>
            </div>
        </div>
    <br/>

<div>

<?php 
$attributes = array("class" => "form-horizontal", "name" => "form"); 
echo form_open("auth/registration", $attributes);
?>
<div class="row">
        <div class="col-md-offset-1 col-md-9">

            <div class="form-group">
                <label for="username" class="col-md-4 control-label"><?=lang('auth_username')?></label>
                <div class="col-md-8">
                    <input class="form-control" name="username" placeholder="<?=lang('auth_username')?>" type="text" value="<?php echo set_value('username'); ?>" />
                    <span class="text-danger"><?php echo form_error('username'); ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-md-4 control-label"><?=lang('auth_email')?></label>
                <div class="col-md-8">
                    <input class="form-control" name="email" placeholder="<?=lang('auth_email')?>" type="text" value="<?php echo set_value('email'); ?>" />
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-md-4 control-label"><?=lang('auth_password')?></label>
                <div class="col-md-8">
                    <input class="form-control" name="password" placeholder="<?=lang('auth_password')?>" type="password" value="" />
                    <span class="text-danger"><?php echo form_error('password'); ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="col-md-4 control-label"><?=lang('auth_confirm_password')?></label>
                <div class="col-md-8">
                    <input class="form-control" name="password_confirm" placeholder="<?=lang('auth_confirm_password')?>" type="password" value="" />
                    <span class="text-danger"><?php echo form_error('password_confirm'); ?></span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-4 col-md-8">
                    <input name="submit" type="submit" class="btn btn-info" value="<?=lang('auth_submit')?>" />
                </div>
            </div>

</div>
            </div>
<?php echo form_close(); ?>