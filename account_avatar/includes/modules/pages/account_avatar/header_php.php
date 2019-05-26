<?php
/**
 * Testimonials Manager
 * Account Avatar system
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Author: davewest Jan 12 23:02:01 2019 -0400 Modified in v1.5.6 $
 */

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_AVATAR');
 
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

  if (!$_SESSION['customer_id']) {
  $_SESSION['navigation']->set_snapshot();
  zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
}

 if (isset($_GET['action']) && ($_GET['action'] == 'send')) {

   if (strpos($_POST['tm_img'], 'data:image') === 0) {
                $img = $_POST['tm_img'];
   
                if (strpos($img, 'data:image/jpeg;base64,') === 0) {
                $img = str_replace('data:image/jpeg;base64,', '', $img);  
                $ext = '.jpg';
                }
                if (strpos($img, 'data:image/png;base64,') === 0) {
                $img = str_replace('data:image/png;base64,', '', $img); 
                $ext = '.png';
                }
   
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = TESTIMONIAL_IMAGE_DIRECTORY . 'img_' . $_SESSION['customer_id'] . $ext;
                
     if (file_put_contents(DIR_WS_IMAGES . $file, $data)) {
      $messageStack->add('new_avatar', 'The image was saved', 'success');
      //update customer 
      $tm_query = "UPDATE " . TABLE_CUSTOMERS . "
                 SET tm_avatar = '" . $file . "'
                 WHERE customers_id = :customer_id:";
         $tm_query = $db->bindVars($tm_query, ':customer_id:', (int)$_SESSION['customer_id'], 'integer');
        $db->Execute($tm_query);
      
  } else {
      $messageStack->add('new_avatar', 'The image could not be saved' , 'error');
  } 
             
            } 
}


$avatar_query = "SELECT tm_avatar
             FROM " . TABLE_CUSTOMERS . "
             WHERE customers_id = :customersID";

$avatar_query = $db->bindVars($avatar_query, ':customersID', $_SESSION['customer_id'], 'integer');
$tm_result = $db->Execute($avatar_query);
$tm_avatar = $tm_result->fields['tm_avatar'];

 // This should be last line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_END_AVATAR');
