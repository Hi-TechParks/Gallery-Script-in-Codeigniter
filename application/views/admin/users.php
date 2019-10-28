    <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('admin_select_user_to_change')?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
               <li class="active"><?=lang('admin_select_user_to_change')?></li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">


              <?php echo $this->session->flashdata('msg');?>
        
         <div class="row">
        <div class="col-md-2">
           <a href="<?=site_url('admin/users')?>" class="btn btn-default btn-xs"><?=lang('admin_clear_filters')?></a>
        </div>
        <div class="col-md-3">
          <form action="<?=site_url('admin/users')?>" method="get">
                <div class="input-group input-group-sm" style="width: 300px;">
                  <input type="text" value="<?=$this->input->get('search_term')?>" name="search_term" class="form-control pull-right" placeholder="<?=lang('admin_search')?>">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                </form>
        </div>
        <div class="col-md-7">
            <p class="text-right"><a href="<?=site_url('admin/users/create')?>" class="btn btn-success btn-sm"><?=lang('admin_create_new')?></a></p>
        </div>
      </div>
        
        
              <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><?=get_sorter(lang('admin_ID'), 'id', site_url('admin/users'), $this->input->get('sort_value'), $this->input->get('sort_direction'))?></th>
                        <th><?=lang('app_profile_photo')?></th>
                        <th><?=get_sorter(lang('admin_username'), 'username', site_url('admin/users'), $this->input->get('sort_value'), $this->input->get('sort_direction'))?></th>
                        <th><?=get_sorter(lang('admin_email'), 'email', site_url('admin/users'), $this->input->get('sort_value'), $this->input->get('sort_direction'))?></th>
                        
                        <th><?=get_sorter(lang('admin_date_joined'), 'date_joined', site_url('admin/users'), $this->input->get('sort_value'), $this->input->get('sort_direction'))?></th>

                        <th><?=get_bool_sorter(lang('admin_is_active'), 'is_active', site_url('admin/users'), $this->input->get('sort_bool_value'), $this->input->get('sort_bool_direction'))?></th>

                        <th><?=get_bool_sorter(lang('admin_is_staff'), 'is_staff', site_url('admin/users'), $this->input->get('sort_bool_value'), $this->input->get('sort_bool_direction'))?></th>

                        <th><?=get_bool_sorter(lang('admin_is_superuser'), 'is_superuser', site_url('admin/users'), $this->input->get('sort_bool_value'), $this->input->get('sort_bool_direction'))?></th>
                        
                        <th width="100px;"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(count($objects["results"]) > 0){
                        foreach($objects["results"] as $item){
                            ?>
                            <tr>
                                <td><?=$item->id?></td>
                                <td><?php

                                if($item->profile_photo){
                                  ?><img class="img-circle" src="<?=base_url('assets/timthumb.php');?>?src=<?=base_url('uploads/profile_photo/' . $item->profile_photo)?>&w=40&h=40"><?php
                                }else{
                                  ?><img class="img-circle" src="<?=base_url('assets/timthumb.php');?>?src=<?=base_url('assets/profile_placeholder.png');?>&w=40&h=40"><?php
                                }

                                ?></td>
                                <td><?=$item->username?></td>
                                <td><?=$item->email?></td>
                                <td><?=date("d/m/Y h:m a", strtotime($item->date_joined))?></td>
                                <td><?=boolean_to_icon($item->is_active)?></td>
                                <td><?=boolean_to_icon($item->is_staff)?></td>
                                <td><?=boolean_to_icon($item->is_superuser)?></td>
                                <td style="text-align: center;">
                                    <a href="<?php echo site_url('admin/users/update/' . $item->id)?>" title="" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a onclick="return confirm('<?=lang('admin_delete_prompt')?>');" href="<?php echo site_url('admin/users/delete/' . $item->id)?>" title="" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                                </td>
                            </tr>
                        <?php
                        }
                    }else{
                        ?>
                        <tr>
                            <td colspan="9">
                                <p class="text-center text-info"><?=lang('admin_no_records_found')?></p>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
      
              

              <?php if($objects['total_rows'] > $this->per_page){?>
      <div class="row">
        <div class="col-md-12">
    <div class="text-center">
          <?php echo $this->pagination->create_links();?>
        </div>
            </div>
        </div>
<?php } ?>

            <!-- End of page content -->
        
            </div>
          </div>
        </div>
      </div>
