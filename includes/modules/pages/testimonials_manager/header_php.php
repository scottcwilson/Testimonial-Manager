<?php
/**
 * Testimonials Manager
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: DrByte  Sun Oct 18 23:02:01 2015 -0400 Modified in v1.5.5 $
 * @version $Id: Testimonials_Manager.php v2.0 11-14-2018 davewest $
 */
 
 
// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_TESTIMONIALS_MANAGER');

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
   
 $id = (int)$_GET['testimonials_id'];
 
 //should not pass without an id
   if ($id == '') {
    zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER_ALL));
  }
  
  $page_check = $db->Execute("select * from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = $id");
  
  $date_published = $page_check->fields['date_added'];
  
$breadcrumb->add(NAVBAR_TITLE);


$action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (zen_not_null($action)) {
    switch ($action) {

  case 'helpyes': 
  $tm_id = zen_db_prepare_input($_GET['testimonials_id']); 
  
    $tmv_yes = $db->Execute("select * from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = '" . (int)$tm_id . "'"); 
 
 $totalYes = ($tmv_yes->fields['helpful_yes'] + 1);
    
    $sq2 = "update " . TABLE_TESTIMONIALS_MANAGER . " set helpful_yes = '" .  $totalYes  . "' where testimonials_id = '" . (int)$tm_id . "'  LIMIT 1 ";
    $db->Execute($sq2);  
    
    // set cookie parameters
/*
$tmcookie_na = 'cw' . $tm_id;
$tmcookie_val = $tm_id . '%' . $tmv_yes->fields['tm_rating'];

setcookie($tmcookie_na, $tmcookie_val, time() + (86400 * 5), $path);  //86400 = 1 day
*/
zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials_id=' . $tm_id, $request_type)); 

   break;
  case 'helpno': 
  $tm_id = zen_db_prepare_input($_GET['testimonials_id']); 
  
    $tmv_no = $db->Execute("select * from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = '" . (int)$tm_id . "'"); 
 
 $totalNo = ($tmv_no->fields['helpful_no'] + 1);
    
    $sq2 = "update " . TABLE_TESTIMONIALS_MANAGER . " set helpful_no = '" .  $totalNo  . "' where testimonials_id = '" . (int)$tm_id . "'  LIMIT 1 ";
    $db->Execute($sq2);  
    
    // set cookie parameters
/*
$tmcookie_na = 'cw' . $tm_id;
$tmcookie_val = $tm_id . '%' . $tmv_no->fields['tm_rating'];

setcookie($tmcookie_na, $tmcookie_val, time() + (86400 * 5), $path);  //86400 = 1 day
*/
zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials_id=' . $tm_id, $request_type)); 

   break;
 }
}

  // This should be last line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_END_TESTIMONIALS_MANAGER');
?>
