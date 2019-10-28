<div class="container-fluid">
	<h1 class="espaco-top"><?=lang('app_gallery_images')?></h1>
</div>

<div class="container-fluid">
<?php if($objects['total_rows'] > $this->per_page){?>
      <div class="row">
        <div class="col-md-12">
    <div class="text-center">
          <?php echo $this->pagination->create_links();?>
        </div>
            </div>
        </div>
<?php } ?>
</div>

<div class="py-5">
<div class="container-fluid">
  <?php if(count($objects["results"]) > 0){
            $row_count = 0;
            foreach($objects["results"] as $item){ ?>
              <?php 
              $row_count = $row_count + 1; 
              if($row_count == 1){
                ?> <div class="row"><!-- Row -->  <?php
              }
              ?>
              <div class="col-md-3 espaco-card ">
          <div class="card " >
            <img class="card-img-top" src="<?php echo featured_photo($item->id); ?>" width="100%" alt="Card image cap">
            <div class="card-body espaco-card">
              <h5 class="card-title"><?php echo $item->name?></h5>
              <p class="card-text"><?php echo $item->description?></p>
              <a href="<?php echo site_url('gallery/view/' . $item->id)?>" class="btn btn-primary"><?=lang('app_view_gallery')?></a>
            </div>
          </div>
            </div>
                <?php 
              if($row_count == 4){
                ?> </div><!-- End Row -->  <?php
                $row_count = 0;
              }
              ?>
            <?php }
            if($row_count != 0){
              ?> </div><!-- End Row -->  <?php
            }
        }else{
          ?><p class="text-center text-info"><?=lang('admin_no_records_found')?></p><?php
        } ?>

</div>

</div>

<div class="container-fluid">
<?php if($objects['total_rows'] > $this->per_page){?>
      <div class="row">
        <div class="col-md-12">
    <div class="text-center">
          <?php echo $this->pagination->create_links();?>
        </div>
            </div>
        </div>
<?php } ?>
</div>