<div class="container-fluid">
  <h3 class="espaco-top"><?=$gallery->name?></h3>
  <hr>
   
   <a href="<?php echo site_url('gallery/view/' . $gallery->id)?>" class="btn btn-info button-back" role="button"><?=lang('app_back')?></a>
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

<!-- <div class="container-fluid">
<div class="row">
    <div class="col-md-12">
             <div class="text-center">
               <a class="btn btn-info" href=""><?=lang('app_back')?></a>
               <a class="btn btn-info" href="<?php echo site_url('gallery/view/' . $gallery->id)?>"><?=lang('app_gallery')?></a>
               <a class="btn btn-info" href=""><?=lang('app_forward')?></a>

             </div>
        </div>
      </div>
</div> -->

<?php foreach($objects["results"] as $photo){ ?>

<div class="py-5">
	<div class="container-fluid">
<div class="row">
    <div class="col-md-12">
             <div class="text-center"><img src="<?=base_url('uploads/photos/' . $photo->location);?>"></div>
        </div>
      </div>
    
  </div>

<?php } ?>


<!-- Space Footer -->
<div class="footer-gallery"><hr></div>


</div>
</div>
<!-- End Container / Row -->
