<div class="container-fluid">
	<h3 class="espaco-top"><?=$gallery->name?></h3>
	<hr>
	<p><?=lang('app_date_created')?>: <?=date("d/m/Y", strtotime($gallery->date_created))?></p>
	 <a href="<?php echo site_url('gallery/')?>" class="btn btn-info button-back" role="button"><?=lang('app_back')?></a>
	 
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
            $item_num = 0;
            foreach($objects["results"] as $item){ ?>
              <?php 
              $row_count = $row_count + 1; 
              if($row_count == 1){
                ?> <div class="row"><!-- Row -->  <?php
              }
              ?>
              <div class="col-md-3 espaco-card ">
                <div class="card " >
                  <?php $page_num = $item_num++; ?>
                  <a href="<?php echo site_url('gallery/view_full/' . $gallery_id).'?page_num='.$page_num ?>"><img class="card-img-top" src="<?=base_url('uploads/photos/' . $item->location);?>" width="100%" alt="<?=$item->name?>"></a>
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
      }?>
    
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

<!-- Space Footer -->
<div class="footer-gallery"><hr></div>


</div>
</div>
<!-- End Container / Row -->
