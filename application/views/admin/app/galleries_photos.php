        <div class="content-wrapper">
        <div class="page-title">
          <div>
            <h1><?=lang('app_photos_in_gallery')?> <?=$gallery->name?></h1>
          </div>
          <div>
            <ul class="breadcrumb">
               <li><a class="btn btn-danger" href="<?=site_url('admin/dashboard')?>"><i class="fa fa-dashboard"></i>  <?=lang('admin_dashboard')?></a></li>
               <li><a class="btn btn-danger" href="<?=site_url('admin/galleries')?>"><?=lang('app_galleries')?></a></li>
               <li class="active"><?=lang('app_photos_in_gallery')?> <?=$gallery->name?></li>
            </ul>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card">
                <pre id="console" style="color: red;"></pre>
                <ul id="filelist"></ul>
                <br />
                <div id="container">
                    <a id="browse" class="btn btn-info" href="javascript:;">Select Files to Upload</a>
                    <a id="start-upload" class="btn btn-warning" href="javascript:;">Start Upload</a>
                </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
        var uploader = new plupload.Uploader({
          browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
          url: '<?=site_url('admin/galleries/photos_upload/' . $gallery->id)?>'
        });
         
        uploader.init();
         
        uploader.bind('FilesAdded', function(up, files) {
          var html = '';
          plupload.each(files, function(file) {
            html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
          });
          document.getElementById('filelist').innerHTML += html;
        });
         
        uploader.bind('UploadProgress', function(up, file) {
          document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        });
         
        uploader.bind('Error', function(up, err) {
          document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
        });

        uploader.bind('UploadComplete', function(up, file) {
          location.reload();
        });
         
        document.getElementById('start-upload').onclick = function() {
          uploader.start();
        };
        </script>


        <div class="row">
          <div class="col-md-12">
            <div class="card">

            <form method="post" action="<?=site_url('admin/galleries/bulk_delete_photos/' . $gallery->id)?>" name="form">
        
         <div class="row">
        <div class="col-md-5">
        <div class="input-group input-group-sm" style="width: 300px;">
           <select class="form-control input-sm" id="bulk_action" name="bulk_action">
                        <option value="">--<?=lang('admin_deleted_selected')?>--</option>
                    </select>

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default btn-sm">Go</button>
                  </div>
                  </div>
        </div>
      </div>
        
        
              <table class="table table-striped">
                    <thead>
                    <tr>
                      <th width="40px;">
                            <input type="checkbox" class="checkall" id="checkall"  onchange="check_uncheck();">
                        </th>
                        <th><?=lang('admin_ID')?></th>
                        <th></th>
                        <th><?=lang('app_name')?></th>
                        
                        <th><?=lang('app_date_created')?></th>
                        
                        <th width="100px;"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(count($objects["results"]) > 0){
                        foreach($objects["results"] as $item){
                            ?>
                            <tr>
                                <td><input type="checkbox" class="table_checkboxes" name="ids[<?php echo $item->id?>]" id="ids_<?php echo $item->id?>" value="<?php echo $item->id?>"></td>
                                <td><?=$item->id?></td>
                                <td>
                                  <a href="<?=base_url('uploads/photos/' . $item->location);?>" data-toggle="lightbox" data-gallery="gallery"">
                                  <img src="<?=base_url('assets/timthumb.php');?>?src=<?=base_url('uploads/photos/' . $item->location);?>&w=40&h=40">
                                  </a>
                                </td>
                                <td><?=$item->name?></td>
                                <td><?=date("d/m/Y h:m a", strtotime($item->date_created))?></td>
                                <td style="text-align: center;">
                                    <a href="<?php echo site_url('admin/galleries/photo_update/' . $item->id)?>" title="" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
                                </td>
                            </tr>
                        <?php
                        }
                    }else{
                        ?>
                        <tr>
                            <td colspan="6">
                                <p class="text-center text-info"><?=lang('admin_no_records_found')?></p>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
        
        
               </form>

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