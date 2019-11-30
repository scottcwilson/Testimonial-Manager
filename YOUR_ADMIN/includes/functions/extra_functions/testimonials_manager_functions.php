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
 
 
   if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

    // set version
       $version = '2.2';
        
     // Get db prefix if any
 	$db_prefix = DB_PREFIX;       
 
    // Set table name
 	$table_name = $db_prefix . 'testimonials_manager';

if (!defined ('TM_VERSION') || (TM_VERSION != $version)) {

global $sniffer;

//bof check for existing install

$config_check = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Testimonials Manager' ORDER BY configuration_group_id ASC;");	

$deletecatid = $config_check->fields['configuration_group_id'];

  if ($config_check->RecordCount() > 0) {
     
     while (!$config_check->EOF) {   
   // kill config
     $db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = " . $deletecatid . ";"); 
     $db->Execute("DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_id = " . $deletecatid . ";");

    $config_check->MoveNext();
  }

//remove unused colummns
if ($sniffer->field_exists($table_name,'testimonials_url')) $db->Execute("ALTER TABLE " . $table_name . " DROP testimonials_url"); 
if ($sniffer->field_exists($table_name,'testimonials_company')) $db->Execute("ALTER TABLE " . $table_name . " DROP testimonials_company"); 
if ($sniffer->field_exists($table_name,'testimonials_city')) $db->Execute("ALTER TABLE " . $table_name . " DROP testimonials_city"); 
if ($sniffer->field_exists($table_name,'testimonials_country')) $db->Execute("ALTER TABLE " . $table_name . " DROP testimonials_country"); 
if ($sniffer->field_exists($table_name,'testimonials_show_email')) $db->Execute("ALTER TABLE " . $table_name . " DROP testimonials_show_email"); 

//add new columns
if (!$sniffer->field_exists($table_name,'tm_rating')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_rating int(1) NOT NULL default 0"); 
if (!$sniffer->field_exists($table_name,'tm_feedback')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_feedback VARCHAR(255) NULL default NULL");
if (!$sniffer->field_exists($table_name,'tm_contact_user')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_contact_user VARCHAR(20) NOT NULL default 'no'");
if (!$sniffer->field_exists($table_name,'tm_contact_phone')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_contact_phone VARCHAR(32) NOT NULL default ''");
if (!$sniffer->field_exists($table_name,'tm_gen_info')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_gen_info VARCHAR(255) NOT NULL DEFAULT ''");
if (!$sniffer->field_exists($table_name,'tm_privacy_conditions')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_privacy_conditions tinyint(1) NOT NULL default 0");
if (!$sniffer->field_exists($table_name,'helpful_yes')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN helpful_yes INT(12) NOT NULL");
if (!$sniffer->field_exists($table_name,'helpful_no')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN helpful_no INT(12) NOT NULL");
if (!$sniffer->field_exists($table_name,'tm_make_public')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN tm_make_public VARCHAR(20) NULL default NULL");
if (!$sniffer->field_exists($table_name,'testimonials_upimg')) $db->Execute("ALTER TABLE " . $table_name . " ADD COLUMN testimonials_upimg VARCHAR(255) NOT NULL");

//change column for security reasons
if ($sniffer->field_exists($table_name,'testimonials_title')) $db->Execute("ALTER TABLE " . $table_name . " CHANGE testimonials_title testimonials_title VARCHAR(255) NOT NULL DEFAULT ''");
if ($sniffer->field_exists($table_name,'testimonials_mail')) $db->Execute("ALTER TABLE " . $table_name . " CHANGE testimonials_mail testimonials_mail VARCHAR(96) NOT NULL DEFAULT ''");
if ($sniffer->field_exists($table_name,'testimonials_name')) $db->Execute("ALTER TABLE " . $table_name . " CHANGE testimonials_name testimonials_name VARCHAR(255) NOT NULL DEFAULT ''");
if ($sniffer->field_exists($table_name,'testimonials_html_text')) $db->Execute("ALTER TABLE " . $table_name . " CHANGE testimonials_html_text testimonials_html_text TEXT NOT NULL");

 }  //eof check for existing install

   $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('Testimonials Manager', 'Testimonials Manager', '1', '1');");
       
        $categoryid = $db->Insert_ID(); 
       
        $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = $categoryid WHERE configuration_group_id = $categoryid");


$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Number Of Testimonials to display in Testimonials sidebox', 'MAX_DISPLAY_TESTIMONIALS_MANAGER_TITLES', '5', 'Set the number of testimonials to display in the Latest Testimonials box.', $categoryid, 1, NOW(), NOW(), NULL, NULL)");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Title Minimum Length','ENTRY_TESTIMONIALS_TITLE_MIN_LENGTH','2','Minimum length of Testimonial title.', $categoryid, 2, NOW(), NOW(), NULL, NULL)");
            
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Text Minimum Length','ENTRY_TESTIMONIALS_TEXT_MIN_LENGTH','10','Minimum length of Testimonial description.', $categoryid, 3, NOW(), NOW(), NULL, NULL)");            
           
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Contact Name Minimum Length','ENTRY_TESTIMONIALS_CONTACT_NAME_MIN_LENGTH','2','Minimum length of Testimonial contact name.', $categoryid, 4, NOW(), NOW(), NULL, NULL)");
            
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display Truncated Testimonials in Sidebox','DISPLAY_TESTIMONIALS_MANAGER_TRUNCATED_TEXT','true','Display truncated text in sidebox', $categoryid, 5, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'',''false''), ')");                       
            
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Length of truncated testimonials to display','TESTIMONIALS_MANAGER_DESCRIPTION_LENGTH','150','If Display Truncated Testimonials in Sidebox is true - set the amount of characters to display from the Testimonials in the Testimonials Manager sidebox.', $categoryid, 6, NOW(), NOW(), NULL, NULL)");            
         
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Number Of Testimonials to display on all testimonials page','MAX_DISPLAY_TESTIMONIALS_MANAGER_ALL_TESTIMONIALS','5','Set the number of testimonials to display on the all testimonials page.', $categoryid, 7, NOW(), NOW(), NULL, NULL)");           

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display Date Published on Testimonials page','DISPLAY_TESTIMONIALS_DATE_PUBLISHED','true','Display date published on testimonials page', $categoryid, 8, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'',''false''), ')");            
         
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display View All Testimonials Link In Sidebox','DISPLAY_ALL_TESTIMONIALS_TESTIMONIALS_MANAGER_LINK','true','Display View All Testimonials Link In Sidebox', $categoryid, 9, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'',''false''), ')");            
 
 $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display Add New Testimonial Link In Sidebox','DISPLAY_ADD_TESTIMONIAL_LINK','true','Display Add New Testimonial Link In Sidebox', $categoryid, 10, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'',''false''), ')");           
 
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Image Width','TESTIMONIAL_IMAGE_WIDTH','80','Set the Width of the Testimonial Image', $categoryid, 11, NOW(), NOW(), NULL, NULL)");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Image Height','TESTIMONIAL_IMAGE_HEIGHT','80','Set the Height of the Testimonial Image', $categoryid, 12, NOW(), NOW(), NULL, NULL)");
            
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Avatar Image Directory','TESTIMONIAL_IMAGE_DIRECTORY','avatars/','Set the Directory for the Testimonial Image', $categoryid, 13, NOW(), NOW(), NULL, NULL)");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Image upload Directory','TM_UPLOAD_DIRECTORY','uploads/','Set the Directory for the Testimonial file uplads.', $categoryid, 14, NOW(), NOW(), NULL, NULL)");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Display upload image field in add testimonials?','DISPLAY_ADD_IMAGE','on','Display upload image field in add testimonials on = displayed off = not displayed', $categoryid, 16, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''on'',''off''), ')");
                     
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Only registered customers may submit a testimonial','REGISTERED_TESTIMONIAL','true','Only registered customers may submit a testimonial', $categoryid, 17, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'',''false''), ')");            
            
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial - Show Store Name and Address','TESTIMONIAL_STORE_NAME_ADDRESS','true','Include Store Name and Address', $categoryid, 18, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''true'',''false''), ')");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . "(configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Define Testimonial','DEFINE_TESTIMONIAL_STATUS','1','Enable the Defined Testimonial Text?<br />0= Link ON, Define Text OFF<br />1= Link ON, Define Text ON<br />2= Link OFF, Define Text ON<br />3= Link OFF, Define Text OFF', $categoryid, 19, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(''0'',''1'',''2'',''3''), ')");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Text Maximum Length','ENTRY_TESTIMONIALS_TEXT_MAX_LENGTH','1000','Maximum length of Testimonial description.', $categoryid, 20, NOW(), NOW(), NULL, NULL)");            

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('Testimonial Manager Version','TM_VERSION',$version,'Testimonial Manager version', $categoryid, 21, NOW(), NOW(), NULL, NULL)");            

if (function_exists('zen_register_admin_page')) { //set admin pages

    // find next sort order in admin_pages table
    $result = $db->Execute("SELECT (MAX(sort_order)+2) as sort FROM ".TABLE_ADMIN_PAGES);
    $admin_page_sort = $result->fields['sort'];
    
    // Admin Menu for Testimonial Manager Configuration Menu
    zen_deregister_admin_pages('TMConfig');
    zen_register_admin_page('TMConfig', 'BOX_TOOLS_TESTIMONIALS_MANAGER', 'FILENAME_CONFIGURATION', 'gID='. $categoryid . '', 'configuration', 'Y', $admin_page_sort);
    
    // Admin Menu for Testimonial Manager Tools Menu
    zen_deregister_admin_pages('toolsTestimonialsManager');
    zen_register_admin_page('toolsTestimonialsManager', 'BOX_TOOLS_TESTIMONIALS_MANAGER', 'FILENAME_TESTIMONIALS_MANAGER', '', 'tools', 'Y', $admin_page_sort);         
}

            // check to see if table exists
          if (!$sniffer->table_exists($table_name)) {

      		$insert_query = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
      		testimonials_id INT(11) NOT NULL AUTO_INCREMENT,
      		language_id INT(11) NOT NULL DEFAULT '0',
      		testimonials_title VARCHAR(255) NOT NULL DEFAULT '',
      		testimonials_name VARCHAR(255) NOT NULL DEFAULT '',   
      		testimonials_html_text TEXT NOT NULL,  
      		testimonials_mail VARCHAR(96) NOT NULL DEFAULT '',
      		testimonials_upimg VARCHAR(255) NOT NULL,
      		testimonials_image VARCHAR(255) NOT NULL DEFAULT '',
      		tm_rating INT(1) NOT NULL DEFAULT 0, 
      		tm_feedback VARCHAR(255) NULL default NULL,
      		tm_make_public VARCHAR(20) NULL default NULL,
      		tm_contact_user VARCHAR(20) NOT NULL DEFAULT 'no',
      		tm_contact_phone VARCHAR(32) NOT NULL DEFAULT '',
      		tm_privacy_conditions TINYINT(1) NOT NULL DEFAULT 0,    		
      		status INT(1) NOT NULL DEFAULT '0',
      		date_added DATETIME NOT NULL DEFAULT '0001-01-01 00:00:00',
      		last_update DATETIME NULL DEFAULT NULL,
      		tm_gen_info VARCHAR(255) NOT NULL DEFAULT '',
      		helpful_yes INT(12) NOT NULL,
      		helpful_no INT(12) NOT NULL,
      		PRIMARY KEY  (testimonials_id)
      		);";


      		@$insert_result = $db->Execute($insert_query);
      		 
   $db->Execute("INSERT INTO ".$table_name." (language_id, testimonials_title, testimonials_name, testimonials_html_text, testimonials_mail, testimonials_image, tm_rating, tm_feedback, tm_contact_user, tm_contact_phone, tm_privacy_conditions, status, date_added, last_update, tm_gen_info, helpful_yes, helpful_no) VALUES (1, 'Store Feedback', 'Clyde Designs', 'This is just a test submission to show you how it looks, great, eh?  Testimonial Manager was first created be Clyde Designs. To honer his memory, we continue to gave credit to him for this mod. You can disable this testimonial and once you receive more, you may delete it. This is to hold the database and test it.  No requirement to keep this at all.', 'clyde@mysticmountainnaturals.com', 'avatars/cbgdave.png', 5, 'Clyde Designs Store Feedback', 'no', '000-000-0000', 0, 0, now(), now(), '', 1, 0);");    		 

  }

//modify customer table for avatars, add a default avatar
if (!$sniffer->field_exists(TABLE_CUSTOMERS,'tm_avatar')) $db->Execute("ALTER TABLE " . TABLE_CUSTOMERS . " ADD COLUMN tm_avatar VARCHAR(255) NOT NULL DEFAULT 'avatars/user-male-icon.png'");


}  //end same version installed



/**** require functions used in testimonials manager **************/

function zen_set_testimonials_status($testimonials_id, $status) {
global $db;
    if ($status == '1') {
      return $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . " set status = '1' where testimonials_id = '" . $testimonials_id . "'");
    } elseif ($status == '0') {
      return $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . " set status = '0' where testimonials_id = '" . $testimonials_id . "'");
    } elseif ($status == '2') {
      return $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . " set status = '2' where testimonials_id = '" . $testimonials_id . "'");
    } else {
      return -1;
    }
  }
?>
