<?php
/**
 * Testimonials Manager
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
  * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Testimonials_Manager.php v2.0 11-14-2018 davewest $
 */ 
?>
<div class="centerColumn" id="testimonialDefault">

<h2><?php echo HEADING_TITLE;  ?></h2>

<div class="center">
<?php
/** display shop total reviews */
 include($template->get_template_dir('/tpl_shop_total_reviews_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_shop_total_reviews_default.php'); ?>
</div>

<fieldset class="coms_mid" style="width:90%;margin:1.2em;">

<h3><?php echo 'Customer Review'; ?></h3>

<div style="border:2px solid rgba(0,0,0,0);">
<div style="float:left;margin:1em;"><?php echo zen_image(DIR_WS_IMAGES . $page_check->fields['testimonials_image'], $page_check->fields['testimonials_title'], TESTIMONIAL_IMAGE_WIDTH, TESTIMONIAL_IMAGE_HEIGHT); // display avatar ?></div>
<div style="margin-top:2em;"><b >by: <?php echo $page_check->fields['testimonials_name']; ?></b></div>
</div>

<br class="clearBoth" />
<div class="">
<?php 
 $star1 = '';
  for ($s=1; $s<=$page_check->fields['tm_rating']; $s++) {
 $star1 .= '<i class="redstar fa fa-star fa-2x"></i>';
 }
 $star2 = '';
  for ($r=$page_check->fields['tm_rating']; $r<=4; $r++) {
  $star2 .= '<i class="blackstar fa fa-star fa-2x"></i>';
}
  echo $star1 . $star2; 
  echo ($page_check->fields['tm_feedback'] != '') ? '&nbsp;&nbsp;&nbsp;<b>' . $page_check->fields['testimonials_title'] . ', ' . $page_check->fields['tm_feedback'] . '</b>' : ''; ?>
</div>

<?php if (DISPLAY_TESTIMONIALS_DATE_PUBLISHED == 'true') { echo '<p>' . zen_date_long($date_published) . '</p>'; } ?>

<section class="coms_text">
<p><?php if (($page_check->fields['testimonials_upimg']) != ('')) { 
  if (function_exists('zen_colorbox') && ZEN_COLORBOX_STATUS == 'true') {
echo '<a href="' . DIR_WS_IMAGES . $page_check->fields['testimonials_upimg'] . '" rel="tm-1" class="nofollow cboxElement" title="' . addslashes($page_check->fields['testimonials_title']) . '">' . zen_image(DIR_WS_IMAGES . $page_check->fields['testimonials_upimg'], $page_check->fields['testimonials_title'], 100) . '</a>';
}else{
echo '<a href="javascript:popupWindow(\'' . DIR_WS_IMAGES . $page_check->fields['testimonials_upimg'] . '\')">' . zen_image(DIR_WS_IMAGES . $page_check->fields['testimonials_upimg'], $page_check->fields['testimonials_title'], 100) . '</a>';
 }
}  
echo nl2br(zen_output_string_protected(stripslashes($page_check->fields['testimonials_html_text']))); //feedback ?></p>
</section>
<br class="clearBoth" />

<div class="forward"><b>Was this site feecback helpful to you? </b> &nbsp;&nbsp;
<?php echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=helpyes&testimonials_id=' . $page_check->fields['testimonials_id'], $request_type) . '"> <span class="btnreview">' . BUTTON_YES_ALT . '</span></a>&nbsp;' . (string)$page_check->fields['helpful_yes'] . '&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=helpno&testimonials_id=' . $page_check->fields['testimonials_id'], $request_type) . '"><span class="btnreview">' . BUTTON_NO_ALT . '</span></a>&nbsp;' . (string)$page_check->fields['helpful_no'] . '&nbsp;&nbsp;' ; ?>
</fieldset> 
<br class="clearBoth" />

<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
<div class="buttonRow forward"><a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER_ALL); ?>"><?php echo zen_image_button(BUTTON_IMAGE_VIEW_TESTIMONIALS, BUTTON_VIEW_TESTIMONIALS_ALT); ?></a></div>
<br class="clearBoth" />
<br class="clearBoth" />
<div class="buttonRow forward"><a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_ADD, '', 'SSL'); ?>"><?php echo zen_image_button(BUTTON_IMAGE_TESTIMONIALS, BUTTON_TESTIMONIALS_ADD_ALT); ?></a></div>
<br class="clearBoth" />
</div>
