<?php  if (count($errors) > 0) : ?>
  <div class="error" >
  	<?php foreach ($errors as $error) : ?>
  	  <p style="color:red;font-weight:500;"><?php echo $error ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>