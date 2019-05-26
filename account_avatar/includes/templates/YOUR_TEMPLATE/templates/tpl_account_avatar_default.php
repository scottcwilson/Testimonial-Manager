<?php
/**
 * Testimonials Manager
 * Account Avatar system
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_avatar_default.php v2.0 11-30-2018 davewest $
 */
?>

<div class="centerColumn" id="accountAuctions">

<h1 id="accountAuctionsHeading"><?php echo HEADING_TITLE; ?></h1>

<?php echo zen_draw_form('new_testimonial', zen_href_link(FILENAME_ACCOUNT_AVATAR, 'action=send', $request_type),'post','enctype="multipart/form-data" '); ?>

<p>Avatars are little images 60 pixels square. They help to identify who's speaking across our site reviews. We offer this page so you can change the default image that's used in reviews. <br /><b>However, we reserve the right to remove, ban, delete accounts for posting any images we find offensive or degrading to anyone!</b></p><p>All images uploaded well auto clip to this size and displayed rounded. You can control this by uploading a 60x60 round image. Refreash this page to see the replacement image.</p>
<fieldset class="centerBoxContentsLinks" style="margin:1em;">  
<div class="center"><h2>Your Current Avatar</h2>
<img src="<?php echo DIR_WS_IMAGES . $tm_avatar; ?>" alt="Me" class="rounded" /></div>

<?php if ($messageStack->size('new_avatar') > 0) echo $messageStack->output('new_avatar'); ?>
<div class="center"><h2>Add yourown Avatar to use with reviews! Only in jpg or png format.<br /> User Avatars are renamed and well replace past avatars!</h2></div>
<div class="box" id="clear-box">
<input type="file" name="file" id="inp_file" class="upfile upfile-1" />
<label for="inp_file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span class="btn-file">Choose a image</span></label>
 <br />
<div class="box-preview">
<img class="north" id="upload-Preview" />
    
  <input id="inp_img" name="tm_img" type="hidden" value="">
 </div>
</div>
<br />
<div class="buttonRow center"><?php echo zen_image_button(BUTTON_IMAGE_DELETE, IMAGE_DELETE, ' id="file-reset" '); ?><br /><br /><?php echo zen_image_submit(BUTTON_IMAGE_SUBMIT, BUTTON_SUBMIT_ALT); ?></div> 
<br /><br /><br />
<script>
 
  function fileChange(e) { 
     document.getElementById('inp_img').value = '';
     
     var file = e.target.files[0];
 
     if (file.type == "image/jpeg" || file.type == "image/png") {
 
        var reader = new FileReader();  
        reader.onload = function(readerEvent) {
   
           var image = new Image();
           image.onload = function(imageEvent) {    
              var max_size = 60;
              var w = image.width;
              var h = image.height;
             
              if (w > h) {  if (w > max_size) { h*=max_size/w; w=max_size; }
              } else     {  if (h > max_size) { w*=max_size/h; h=max_size; } }
             
              var canvas = document.createElement('canvas');
              canvas.width = w;
              canvas.height = h;
              canvas.getContext('2d').drawImage(image, 0, 0, w, h);
                 
              if (file.type == "image/jpeg") {
                 var dataURL = canvas.toDataURL("image/jpeg", 1.0);
              } else {
                 var dataURL = canvas.toDataURL("image/png");   
              }
              document.getElementById('inp_img').value = dataURL;   
              document.getElementById("upload-Preview").src = canvas.toDataURL();
           }
           image.src = readerEvent.target.result;
        }
        reader.readAsDataURL(file);
     } else {
        document.getElementById('inp_file').value = ''; 
        alert('Please only select images in JPG- or PNG-format.');  
     }
  }
 
  document.getElementById('inp_file').addEventListener('change', fileChange, false);    
  
 $('#file-reset').on('click', function(e){
   var $el = $('#clear-box');
   document.getElementById("upload-Preview").src = '';
   $el.wrap('<form>').closest('form').get(0).reset();
   $el.unwrap();
});
        
</script>
<br class="clearBoth" />
</fieldset>


<br class="clearBoth" />
<div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', $request_type) . '">' . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>
