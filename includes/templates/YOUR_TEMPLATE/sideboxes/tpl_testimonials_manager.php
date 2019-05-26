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
$content = '';


  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';
  for ($i=1; $i<=sizeof($page_query_list); $i++) {
  
 $star1 = '';
  for ($s=1; $s<=$page_query_list[$i]['rating']; $s++) {
 $star1 .= '<i class="fa fa-star fa-fw" style="color:rgba(239,41,41,1);"></i>';
 }
 $star2 = '';
  for ($r=$page_query_list[$i]['rating']; $r<=4; $r++) {
  $star2 .= '<i class="blackstar fa fa-star fa-fw" style="color:rgba(0,0,0,1);"></i>';
}
  
  $content .= '<b><a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials_id=' . $page_query_list[$i]['id']) . '">' . $page_query_list[$i]['name'] . '</a></b><div class="testimonial">';

  $content .= '<div class="buttonRow ">' . $star1 . $star2 . '</div>';
  
if ($page_query_list[$i]['image'] != '') {  
$content .= '<p class="testimonialImage">' . zen_image(DIR_WS_IMAGES . $page_query_list[$i]['image'], $page_query_list[$i]['name'], TESTIMONIAL_IMAGE_WIDTH, TESTIMONIAL_IMAGE_HEIGHT) . '</p>';  
  }
  if (DISPLAY_TESTIMONIALS_MANAGER_TRUNCATED_TEXT == 'true') {
    $content .= '<p>' . zen_trunc_string($page_query_list[$i]['story'],TESTIMONIALS_MANAGER_DESCRIPTION_LENGTH) . '<br /><span><strong><a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials_id=' . $page_query_list[$i]['id']) . '">' .TESTIMONIALS_MANAGER_READ_MORE .'</a></strong></span></p></div>';
	$content .= '<hr class="catBoxDivider" />';
  }
  }
  if (DISPLAY_ALL_TESTIMONIALS_TESTIMONIALS_MANAGER_LINK == 'true') {
  $content .= '<div class="bettertestimonial"><a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER_ALL) . '">' . TESTIMONIALS_MANAGER_DISPLAY_ALL_TESTIMONIALS . '</a></div>';
 }
  if (DISPLAY_ADD_TESTIMONIAL_LINK == 'true') {
  $content .= '<div class="bettertestimonial"><a href="' . zen_href_link(FILENAME_TESTIMONIALS_ADD, '', 'SSL') . '">' . TESTIMONIALS_MANAGER_ADD_TESTIMONIALS . '</a></div>';
 }
$content .= '</div>';
