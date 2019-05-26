<?php
/**
 * Testimonials Manager
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
 * @copyright Copyright 2003-2018 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Testimonials_Manager.php v2.2 11-30-2018 davewest $
 */

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (zen_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') || ($_GET['flag'] == '2') ) {
          zen_set_testimonials_status($_GET['bID'], $_GET['flag']);
          $messageStack->add_session(SUCCESS_PAGE_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }
        zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']));
        break;
      case 'insert':
      case 'update':
        if (isset($_POST['testimonials_id'])) $testimonials_id = zen_db_prepare_input($_POST['testimonials_id']);
        $testimonials_title = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_title']));
	$testimonials_name = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_name']));
	$testimonials_mail = zen_db_prepare_input($_POST['testimonials_mail']);
	$rating = zen_db_prepare_input($_POST['tm_rating']);
        $feedback = zen_db_prepare_input(zen_sanitize_string($_POST['tm_feedback']));
        $contact_user = zen_db_prepare_input($_POST['tm_contact_user']);
        $contact_phone = zen_db_prepare_input($_POST['tm_contact_phone']);
        $make_public = zen_db_prepare_input($_POST['tm_make_public']);
        $privacy = zen_db_prepare_input($_POST['tm_privacy_conditions']);
        $tm_status = zen_db_prepare_input($_POST['tm_status']);
        $tmv_yes = zen_db_prepare_input($_POST['helpful_yes']);
        $tmv_no = zen_db_prepare_input($_POST['helpful_no']);
	$testimonials_date = (empty($_POST['date_added']) ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_db_prepare_input($_POST['date_added']));
        $testimonials_html_text = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_html_text']));
        
        $page_error = false;
        if (empty($testimonials_name)) {
          $messageStack->add(ERROR_PAGE_AUTHOR_REQUIRED, 'error');
          $page_error = true;
        }
        if (empty($testimonials_mail)) {
          $messageStack->add(ERROR_PAGE_EMAIL_REQUIRED, 'error');
          $page_error = true;
        }
        if (empty($testimonials_title)) {
          $messageStack->add(ERROR_PAGE_TITLE_REQUIRED, 'error');
          $page_error = true;
        }
        if (empty($testimonials_html_text)) {
		$messageStack->add(ERROR_PAGE_TEXT_REQUIRED, 'error');
          $page_error = true;
        }
        if ($page_error == false) {
        
		$language_id = (int)$_SESSION['languages_id'];

	$sql_data_array = array(array('fieldName'=>'language_id', 'value'=>$language_id, 'type'=>'integer'), 
	                        array('fieldName'=>'testimonials_title', 'value'=>$testimonials_title, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_name', 'value'=>$testimonials_name, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_html_text', 'value'=>$testimonials_html_text, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_mail', 'value'=>$testimonials_mail, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_rating', 'value'=>$rating, 'type'=>'integer'),
                                array('fieldName'=>'tm_feedback', 'value'=>$feedback, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_make_public', 'value'=>$make_public, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_contact_user', 'value'=>$contact_user, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_contact_phone', 'value'=>$contact_phone, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_privacy_conditions', 'value'=>$privacy, 'type'=>'integer'),
                                array('fieldName'=>'helpful_yes', 'value'=>$tmv_yes, 'type'=>'integer'),
                                array('fieldName'=>'helpful_no', 'value'=>$tmv_no, 'type'=>'integer'),
                               );  
                               
          if ($action == 'insert') {
          
	     if (empty($_POST['date_added'])) {
		$testimonials_date = 'now()';
	     }else {
		$testimonials_date = zen_date_raw($_POST['date_added']);
	     }
		
            $sql_data_array[] = array('fieldName'=>'status', 'value'=>0, 'type'=>'integer');
            $sql_data_array[] = array('fieldName'=>'date_added', 'value'=>$testimonials_date, 'type'=>'noquotestring');
                                   
            $db->perform(TABLE_TESTIMONIALS_MANAGER, $sql_data_array);           	
            $testimonials_id = zen_db_insert_id();
            $messageStack->add_session(SUCCESS_PAGE_INSERTED, 'success');
            
          } elseif ($action == 'update') {
          
            $sql_data_array[] = array('fieldName'=>'status', 'value'=>$_POST['tm_status'], 'type'=>'integer');
            $sql_data_array[] = array('fieldName'=>'last_update', 'value'=>'now()', 'type'=>'noquotestring');

            $db->perform(TABLE_TESTIMONIALS_MANAGER, $sql_data_array, 'update', "testimonials_id = '" . (int)$testimonials_id . "'");
            
            $messageStack->add_session(SUCCESS_PAGE_UPDATED, 'success');
          }
 
  
       if ($_POST['avatar_image'] != '') {
        // add image manually
        $existing_avatar = TESTIMONIAL_IMAGE_DIRECTORY . $_POST['avatar_image'];
        $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_image = '" . $existing_avatar . "'
                            where testimonials_id = '" . (int)$testimonials_id . "'");
      } else {
        if ($testimonials_image = new upload('testimonials_image')) {
          $testimonials_image->set_extensions(array('jpg','gif','png'));
          $testimonials_image->set_destination(DIR_FS_CATALOG_IMAGES . TESTIMONIAL_IMAGE_DIRECTORY);
          if ($testimonials_image->parse() && $testimonials_image->save()) {
            $testimonials_image_name = zen_db_input(TESTIMONIAL_IMAGE_DIRECTORY . $testimonials_image->filename);
          }
          if ($testimonials_image->filename != 'none' && $testimonials_image->filename != '') {
            // save filename when not set to none and not blank
            $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_image = '" . $testimonials_image_name . "'
                          where testimonials_id = '" . (int)$testimonials_id . "'");
          } 
        }
      }

       if ($_POST['image_upimg'] != '') {
        // add image manually
        $existing_image = TM_UPLOAD_DIRECTORY . $_POST['image_upimg'];
        $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_upimg = '" . $existing_image . "'
                            where testimonials_id = '" . (int)$testimonials_id . "'");
      } else {
        if ($testimonials_upimg = new upload('testimonials_upimg')) {
          $testimonials_upimg->set_extensions(array('jpg','png'));
          $testimonials_upimg->set_destination(DIR_FS_CATALOG_IMAGES . TM_UPLOAD_DIRECTORY);
          if ($testimonials_upimg->parse() && $testimonials_upimg->save()) {
            $testimonials_upimg_name = zen_db_input(TM_UPLOAD_DIRECTORY . $testimonials_upimg->filename);
          }
          if ($testimonials_upimg->filename != 'none' && $testimonials_upimg->filename != '') {
            // save filename when not set to none and not blank
            $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_upimg = '" . $testimonials_upimg_name . "'
                          where testimonials_id = '" . (int)$testimonials_id . "'");
          } 
        }
      }
       
          zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'bID=' . $testimonials_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        $testimonials_id = zen_db_prepare_input($_GET['bID']);
        $db->Execute("delete from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = '" . (int)$testimonials_id . "'");
        $messageStack->add_session(SUCCESS_PAGE_REMOVED, 'success');
        zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page']));
        break;
    }
  } 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script src="includes/menu.js"></script>
<script src="includes/general.js"></script>
<script>
      function init() {
          cssjsmenu('navbar');
          if (document.getElementById) {
              var kill = document.getElementById('hoverJS');
              kill.disabled = true;
          }
      }
</script>
</head>
  <body onLoad="init()">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->

 <!-- body //-->
<div>
 <div class="main" ><a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=mor'); ?>" class="btn btn-primary" role="button">Remove Testimonials Manager</a> <a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=ckupdate', 'NONSSL'); ?>" class="btn btn-primary" role="button">Update Testimonials Manager</a> </div>     
  </div> 
  
<?php if ($action == 'mor') { 
  $action = '';
  ?>
<div class="BMalert"><?php echo TEXT_REMOVE_WARRING; ?>
<br /> <br />
<a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER); ?>" class="btn btn-primary" role="button"> <?php echo IMAGE_CANCEL; ?></a> 
<a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=remove', 'NONSSL'); ?>" class="btn btn-primary" role="button">Remove Testimonials Manager</a>


</div>
  <?php  
    } elseif ($action == 'ckupdate') { 
  $action = '';
  ?>
<div class="BMalert"><br /> 
<?php echo TEXT_UPDATE_WARRING; ?>
<br />
<?php echo TEXT_UPDATE_DISCLAMER; ?>
<br /> <br /> 
       <a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER) ?>" class="btn btn-primary" role="button"><?php echo IMAGE_CANCEL; ?></a> <a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=ckupd') ?>" class="btn btn-primary" role="button">Check for Updated Testimonials Manager</a>
       </div> 
       
<?php  }elseif ($action == 'remove') { 
              $action = '';
      
   $categoryid = array();
	$id_result = $db->Execute("SELECT configuration_group_id FROM ". TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Testimonials Manager'");
	if (!$id_result->EOF) {
			$categoryid = $id_result->fields;
			$isit_installed .= 'Testimonials Manager Configuration_Group ID = ' . $categoryid['configuration_group_id']. '<br>';
			$rm_config_id = $categoryid['configuration_group_id'];
			// kill config
			$db->Execute("DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_group_id = '" . $rm_config_id ."'");
                        $db->Execute("DELETE FROM ". TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_id = '" . $rm_config_id ."'");
                        $isit_installed .= 'deleted Testimonials Manager Configuration files!<br />';
                        // kill admin pages for ZC1.5.x only
                        if (function_exists('zen_deregister_admin_pages')) {  
                               zen_deregister_admin_pages('toolsTestimonialsManager');
                               zen_deregister_admin_pages('TMConfig');
                        $isit_installed .= 'deleted Testimonials Manager Admin Pages!<br />';
                        }

                     

if ($sniffer->table_exists(TABLE_TESTIMONIALS_MANAGER)) $db->Execute("DROP TABLE " . TABLE_TESTIMONIALS_MANAGER );

          
//check for and remove the auto loader page so it wont install again
  if(file_exists(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'functions/extra_functions/testimonials_manager_functions.php')) {
         if(!unlink(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'functions/extra_functions/testimonials_manager_functions.php')) {
		$isit_installed .= 'Autoloader deleted<br />';
	};
    }

///done 
     echo $isit_installed . '<br /><br />Testimonials Manager SQL and Menues have been deleted! Please delete all files! ' . ' <a href="' . zen_href_link(FILENAME_DEFAULT) .'"> ' . zen_image_button('button_go.gif', 'Exit this installer') . '</a><br />';
    exit;

    } else { 
//not done 
    $messageStack->add_session('Failed Finding Testimonials Manager Configuration_Group ID!<br />No change made.', 'error');
    echo $isit_installed . '<br /><br />Read the help to help figure out what went wrong ' . ' <a href="' . zen_href_link(FILENAME_DEFAULT) .'"> ' . zen_image_button('button_go.gif', 'Exit this installer') . '</a><br />';
    	   
    }	
    


} elseif ($action == 'ckupd') {
               $action = '';
           
        $module_constant = 'TM_VERSION'; // This should be a UNIQUE name followed by _VERSION for convention
	$module_name = "Testimonial Manager"; // This should be a plain English or Other in a user friendly way
	$zencart_com_plugin_id = 299; // from zencart.com plugins - Leave Zero not to check
	$current_version = TM_VERSION; //this should be the current installed version

  $configuration_group_id = '';
  $checklinknote = '';

    $config = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key= '" . $module_name . "'");
    $configuration_group_id = $config->fields['configuration_group_id'];

// Version Checking 
$new_version_details = plugin_version_check_for_updates($zencart_com_plugin_id, $current_version);
    if ($new_version_details != FALSE) {
        echo '<div class="BMalert">Version ' . $new_version_details['latest_plugin_version']. ' of ' . $new_version_details['title'] . ' is available at <a href="' . $new_version_details['link'] . '" target="_blank">[Details]</a>';
    } else {
     echo '<div class="BMalert">No New Version for Testimonials Manager is available or ID is set to 0.</div>';
     
    }
 } //end remove-update  ?>
    
<!-- body //-->

<div class="container-fluid">
      <!-- body_text //-->
      <h1><?php echo HEADING_TITLE; ?></h1> 
<?php
  if ($action == 'new') {  
    $form_action = 'insert';

    $parameters = array('testimonials_title' => '',
	                'language_id' => '',  
	                'tm_rating' => '',  
	                'tm_feedback' => '',  
			'testimonials_name' => '',
	                'testimonials_mail' => '',
			'testimonials_image' => '',
			'testimonials_title' => '',  
                        'testimonials_html_text' => '',
                        'tm_contact_user' => '',
                        'tm_contact_phone' => '',
                        'tm_make_public' => '',
                        'tm_privacy_conditions' => '',
                        'helpful_yes' => '',
                        'helpful_no' => '',
                        'tm_gen_info' => '',
                        'testimonials_upimg' => '',
			'date_added' => '',
                        'status' =>'');

    $bInfo = new objectInfo($parameters);

    if (isset($_GET['bID'])) {
      $form_action = 'update';

      $bID = zen_db_prepare_input($_GET['bID']);

      $page_query = "select * from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = '" . $_GET['bID'] . "'";
      $page = $db->Execute($page_query);
      $bInfo->objectInfo($page->fields);
    } elseif (zen_not_null($_POST)) {
      $bInfo->objectInfo($_POST);
    }
?>
<!--  edit/create form body -->
<div class="container-fluid">
        <?php echo zen_draw_form('new_page', FILENAME_TESTIMONIALS_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'action=' . $form_action, 'post', 'enctype="multipart/form-data"'); if ($form_action == 'update') echo zen_draw_hidden_field('testimonials_id', $bID); 
        echo zen_hide_session_id();
        ?>

          <?php
          $tm_status_array = array(
            array('id' => '0', 'text' => TEXT_TM_STATUS_0), //Pending Review
            array('id' => '1', 'text' => TEXT_TM_STATUS_1), //Approved
            array('id' => '2', 'text' => TEXT_TM_STATUS_2), // Banned - Not allowed to create
          );
          ?>

  <div class="form-group">
     <?php echo zen_draw_label('Feedback Status:', 'tm_status', 'class="col-sm-2 col-form-label"'); ?>
     <div class="col-sm-10">
       <?php echo zen_draw_pull_down_menu('tm_status', $tm_status_array, $bInfo->status, 'class="form-control"'); ?>
            </div>
          </div>
<br />          
  <div class="form-group">
     <?php echo zen_draw_label('Testimonial Rating:', 'tm_rating', 'class="col-sm-2 col-form-label"'); ?>
     <div class="col-sm-10">
     <?php echo zen_draw_input_field('tm_rating', $bInfo->tm_rating, 'min="0" max="5" class="form-control"', true, 'number') . TEXT_FIELD_REQUIRED; ?>
     </div>
  </div>
<br />    
  <div class="form-group">
     <?php echo zen_draw_label(TEXT_TESTIMONIALS_NAME, 'testimonials_name', 'class="col-sm-2 col-form-label"'); ?>
     <div class="col-sm-10">
     <?php echo zen_draw_input_field('testimonials_name', $bInfo->testimonials_name, ' class="form-control"', true) . TEXT_FIELD_REQUIRED; ?>
     </div>
  </div>
<br />     
  <div class="form-group">
     <?php echo zen_draw_label(TEXT_TESTIMONIALS_MAIL, 'testimonials_mail', 'class="col-sm-2 col-form-label"'); ?>
     <div class="col-sm-10">
     <?php echo zen_draw_input_field('testimonials_mail', $bInfo->testimonials_mail, ' class="form-control"', true) . TEXT_FIELD_REQUIRED; ?>
     </div>
  </div>
<br />  
 <?php if ($form_action == 'insert') { ?>
	<p><?php echo TEXT_TESTIMONIALS_DATE_INFO; ?></p>
  <div class="form-group ">
     <?php echo zen_draw_label(TEXT_TESTIMONIALS_DATE, 'date_added', 'class="col-sm-2 col-form-label"'); ?>
      <div class="col-sm-10">
        <div class="date input-group" id="datepicker">
        <span class="input-group-addon datepicker_icon">
          <i class="fa fa-calendar fa-lg"></i>
        </span>
               <?php echo zen_draw_input_field('date_added', zen_date_short($bInfo->date_added), ' class="form-control"', false); ?>
          </div>
      <span class="help-block errorText"><?php echo TEXT_TESTIMONIALS_OPTIONAL . ENTRY_DATE_ADDED_TEXT; ?></span>  
            </div>
          </div>     
 <br />
<?php
 }
?>

  <div class="form-group">
           <?php echo zen_draw_label('Can we contact User:', 'tm_contact_user', 'class="col-sm-2 col-form-label"'); ?>
            <div class="col-sm-10">
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_contact_user', 'no', ($pInfo->product_is_always_free_shipping == 1)) . 'no'; ?></label>
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_contact_user', 'email', ($pInfo->product_is_always_free_shipping == 0)) . 'email'; ?></label>
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_contact_user', 'phone', ($pInfo->product_is_always_free_shipping == 2)) . 'phone'; ?></label>
           </div>
      </div>
<br />
         <div class="form-group">
              <?php echo zen_draw_label('Contact User phone:', 'tm_contact_phone', 'class="col-sm-2 col-form-label"'); ?>
              <div class="col-sm-10">
             <?php echo zen_draw_input_field('tm_contact_phonel', $bInfo->tm_contact_phone, ' class="form-control"', false) . TEXT_TESTIMONIALS_OPTIONAL; ?>
            </div>
          </div>            
<br />          
	<div class="form-group">
            <?php echo zen_draw_label('Make Public:', 'tm_make_public', 'class="col-sm-2 col-form-label"'); ?>
            <div class="col-sm-10">
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_make_public', 'yes', ($bInfo->tm_make_public == 'yes' ? true : false),'id="tm_make_public_left"') . 'Yes'; ?></label>
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_make_public', 'no', ($bInfo->tm_make_public == 'no' ? true : false), 'id="tm_make_public_right"') . 'No'; ?></label>
              <span><?php echo TEXT_FIELD_REQUIRED; ?> </span>
           </div>
      </div>	
<br />
	<div class="form-group">
            <?php echo zen_draw_label('Privacy checked:', 'tm_privacy_conditions', 'class="col-sm-2 col-form-label"'); ?>
            <div class="col-sm-10">
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_privacy_conditions', 1, ($bInfo->tm_privacy_conditions == 1 ? true : false),'id="email_format_left"') . 'Yes'; ?></label>
              <label class="radio-inline"><?php echo zen_draw_radio_field('tm_privacy_conditions', 0, ($bInfo->tm_privacy_conditions == 0 ? true : false), 'id="email_format_right"') . 'No'; ?></label>
           </div>
      </div>
<br />
         <div class="form-group">
              <?php echo zen_draw_label(TEXT_TESTIMONIALS_TITLE, 'testimonials_title', 'class="col-sm-2 col-form-label"'); ?>
              <div class="col-sm-10">
             <?php echo zen_draw_input_field('testimonials_title', $bInfo->testimonials_title, ' class="form-control"', true) . TEXT_FIELD_REQUIRED; ?>
            </div>
          </div> 
 <br />                  
  <div class="form-group">
      <?php echo zen_draw_label(TEXT_TESTIMONIALS_HTML_TEXT, 'testimonials_html_text', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
        <div class="input-group">
          <?php echo zen_draw_textarea_field('testimonials_html_text', 'soft', '100%', '10', $bInfo->testimonials_html_text, ' class="form-control"', TRUE); ?>
        </div>
    </div>
  </div>
<br />
  <div class="form-group">
      <?php echo zen_draw_label('Other fields:', 'tm_gen_info', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
        <div class="input-group">
          <?php echo zen_draw_textarea_field('tm_gen_info', 'soft', '100%', '10', $bInfo->tm_gen_info, 'disabled="disabled"', false); ?>
        </div>
    </div>
  </div>
<br />		  
    <?php
     if (($bInfo->testimonials_image) != ('')) {  ?>
           <div class="form-group">
            <?php echo zen_draw_label(TEXT_AVATAR_CURRENT_IMAGE, 'testimonials_image', 'class="col-sm-2 col-form-label"'); ?>
            <div class="col-sm-10">
		<?php echo $bInfo->testimonials_image; ?>
	    </div>
          </div>
<?php
}
?> 
<br />
  <div class="form-group">
      <?php echo zen_draw_label(TEXT_AVATAR_PAGE_IMAGE, 'testimonials_image', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
            <?php echo zen_draw_file_field('testimonials_image', '', ' class="form-control"') . TEXT_TESTIMONIALS_OPTIONAL; ?>
       </div>     
      </div>       
<br />
  <div class="form-group">
      <?php echo zen_draw_label(TEXT_AVATAR_IMAGE_MANUAL, 'avatar_image', 'class="col-sm-2 col-form-label"'); ?> 
    <div class="col-sm-10">
            <?php echo zen_draw_input_field('avatar_image', '', ' class="form-control"') . TEXT_TESTIMONIALS_OPTIONAL; ?>
       </div>     
      </div>       

<br />
  <div class="form-group">
      <?php echo zen_draw_label('&nbsp;&nbsp;Helpful yes voting! ', 'helpful_yes', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
          <?php echo zen_draw_input_field('helpful_yes', $bInfo->helpful_yes, ' class="form-control"', false) . TEXT_TESTIMONIALS_OPTIONAL; ?>
    </div>
  </div>
<br />
  <div class="form-group">
      <?php echo zen_draw_label('&nbsp;&nbsp;Helpful no voting! ', 'helpful_no', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
          <?php echo zen_draw_input_field('helpful_no', $bInfo->helpful_no, ' class="form-control"', false) . TEXT_TESTIMONIALS_OPTIONAL; ?>
    </div>
  </div>    
<br />	  
<?php if (($bInfo->testimonials_upimg) != ('')) { ?>
           <div class="form-group">
            <?php echo zen_draw_label(TEXT_INFO_CURRENT_IMAGE, 'testimonials_upimg', 'class="col-sm-2 col-form-label"'); ?>
            <div class="col-sm-10">
		<?php echo $bInfo->testimonials_upimg; ?>
	    </div>
          </div>
<?php
}
?> 
<br />
  <div class="form-group">
      <?php echo zen_draw_label(TEXT_INFO_PAGE_IMAGE, 'testimonials_upimg', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
            <?php echo zen_draw_file_field('testimonials_upimg', '', 'class="form-control"') . TEXT_TESTIMONIALS_OPTIONAL; ?>
        </div>
      </div>
<br />
  <div class="form-group">
            <?php echo zen_draw_label(TEXT_PRODUCTS_IMAGE_MANUAL, 'testimonials_upimg', 'class="col-sm-2 col-form-label"'); ?>
    <div class="col-sm-10">
            <?php echo zen_draw_input_field('image_upimg', '', 'class="form-control"') . TEXT_TESTIMONIALS_OPTIONAL; ?>
        </div>
   </div>     
<br />

 <?php echo (($form_action == 'insert') ? '<button type="submit" class="btn btn-primary">' . IMAGE_INSERT . '</button>' : '<button type="submit" class="btn btn-primary">' . IMAGE_UPDATE . '</button>') . ' <a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . (isset($_GET['bID']) ? 'bID=' . $_GET['bID'] : '')) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'; ?> 

      </form> 
 

<?php
  } else { 
?>
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft">    

         <table class="table table-hover">
            <thead>
              <tr class="dataTableHeadingRow" width="100%">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TESTIMONIALS; ?></td>
		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MAIL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent"></td>
                <td class="dataTableHeadingContent"></td>
              </tr>
              </thead>
            <tbody>  
<?php
    $testimonials_query_raw = "select * from " . TABLE_TESTIMONIALS_MANAGER . " order by date_added DESC, testimonials_title";
    $testimonials_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $testimonials_query_raw, $testimonials_query_numrows);
    $testimonials = $db->Execute($testimonials_query_raw);
//TODO: fix for php7.3
while (!$testimonials->EOF) {  
     if ((!isset($_GET['bID']) || (isset($_GET['bID']) && ($_GET['bID'] == $testimonials->fields['testimonials_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
        $bInfo_array = array_merge($testimonials->fields);
        $bInfo = new objectInfo($bInfo_array);
      }
      if (isset($bInfo) && is_object($bInfo) && ($testimonials->fields['testimonials_id'] == $bInfo->testimonials_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $testimonials->fields['testimonials_title']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $testimonials->fields['testimonials_name']; ?></td>
		<td class="dataTableContent" align="left"><?php echo $testimonials->fields['testimonials_mail']; ?></td>
                <td class="dataTableContent"><?php echo $testimonials->fields['date_added']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($testimonials->fields['status'] == '0') { //status pending yellow
        echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=1') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Approved', 10, 10) . '</a>&nbsp;&nbsp;' . zen_image(DIR_WS_IMAGES . 'icon_status_yellow.gif', 'Pending', 10, 10) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=2') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Baned', 10, 10) . '</a>';

      } elseif ($testimonials->fields['status'] == '1') {  //status approved green
      echo zen_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Approved', 10, 10) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=0') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_yellow_light.gif', 'Set Pending', 10, 10) . '</a>&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=2') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Banned', 10, 10) . '</a>';      
      }else{ //status baned red
       echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=1') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Approved', 10, 10) . '</a>&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=0') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_yellow_light.gif', 'Set Pending', 10, 10) . '</a>&nbsp;&nbsp;' . zen_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Banned', 10, 10);
      }
?>
 </td>
                <td class="dataTableContent" align="right"><?php if (isset($bInfo) && is_object($bInfo) && ($testimonials->fields['testimonials_id'] == $bInfo->testimonials_id)) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, zen_get_all_get_params(array('bID')) . 'bID=' . $testimonials->fields['testimonials_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                <td class="dataTableContent" align="right"></td>
              </tr>
<?php

 $testimonials->MoveNext();
    }
?>
                  <tr>
                    <td class="smallText" valign="top"><?php echo $testimonials_split->display_count($testimonials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TESTIMONIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $testimonials_split->display_links($testimonials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(array('page', 'info', 'x', 'y', 'lID'))); ?></td>
                   <td  colspan="5"class="dataTableContent" align="right"></td>
                  </tr>


              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=new') . '" class="btn btn-primary" role="button">' . IMAGE_NEW_PAGE . '</a>'; ?></td>
                  </tr>
                   </table>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
<?php
if ($bInfo->status == 0) {
$teststatus = 'Pending';
} else {
$teststatus = 'Approved';
} ?>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight">
          <?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $bInfo->testimonials_title . '</b>');

      $contents = array('form' => zen_draw_form('testimonials', FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->testimonials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $bInfo->testimonials_title . '</b>');
    //  if ($bInfo->testimonials_image) $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('delete_image', 'on', true) . ' ' . TEXT_INFO_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($bInfo)) {
	  
        $heading[] = array('text' => '<b>' . $bInfo->testimonials_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->testimonials_id . '&action=new') . '" class="btn btn-primary" role="button">' . IMAGE_EDIT . '</a> <a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->testimonials_id . '&action=delete') . '" class="btn btn-primary" role="button">' . IMAGE_DELETE . '</a><br /><br /><br />');

        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_STATUS . ' '  . $teststatus);

		if (zen_not_null($bInfo->testimonials_image)) {
        $contents[] = array('text' => '<br />' . zen_image(DIR_WS_CATALOG_IMAGES . $bInfo->testimonials_image, $bInfo->testimonials_title, TESTIMONIAL_IMAGE_WIDTH, TESTIMONIAL_IMAGE_HEIGHT) . '<br /><br />' . $bInfo->testimonials_title);
		} else {
		$contents[] = array('text' => '<br />' . TEXT_IMAGE_NONEXISTENT);
		}
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_RATING . ' '  . $bInfo->tm_rating . ' Stars');
        $contents[] = array('text' => '<br /><b>' . TEXT_INFO_TESTIMONIALS_PUBLIC  . ' '  . $bInfo->tm_make_public . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_FEEDBACK . ' '  . $bInfo->tm_feedback);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT_NAME . ' '  . $bInfo->testimonials_name);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT_EMAIL . ' ' . $bInfo->testimonials_mail);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_TITLE . ' ' . $bInfo->testimonials_title);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_DESCRIPTION . '<br /> ' . $bInfo->testimonials_html_text);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT . ' '  . $bInfo->tm_contact_user);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT_PHONE . ' '  . $bInfo->tm_contact_phone);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_PRIVACY . ' '  . $bInfo->tm_privacy_conditions);
        $contents[] = array('text' => '<br />' . 'Helpful yes voting' . ' '  . $bInfo->helpful_yes);
        $contents[] = array('text' => '<br />' . 'Helpful no voting' . ' '  . $bInfo->helpful_no);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_GEN_INFO . '<br />'  . $bInfo->tm_gen_info);
        $contents[] = array('text' => '<br />' . 'Submited image:' . '<br />'  . $bInfo->testimonials_upimg);
        
        $contents[] = array('text' => '<br />' . TEXT_DATE_TESTIMONIALS_CREATED . ' ' . zen_date_short($bInfo->date_added));

        if (zen_not_null($bInfo->last_update)) {
          $contents[] = array('text' => TEXT_DATE_TESTIMONIALS_LAST_MODIFIED . ' ' . zen_date_short($bInfo->last_update));
        } else {		
          $contents[] = array('text' => TEXT_DATE_TESTIMONIALS_LAST_MODIFIED);
		}
		
        if ($bInfo->date_scheduled) $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_SCHEDULED_AT_DATE, zen_date_short($bInfo->date_scheduled)));

        if ($bInfo->expires_date) {
          $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_EXPIRES_AT_DATE, zen_date_short($bInfo->expires_date)));
        } elseif ($bInfo->expires_impressions) {
          $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_EXPIRES_AT_IMPRESSIONS, $bInfo->expires_impressions));
        }

        if ($bInfo->date_status_change) $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_STATUS_CHANGE, zen_date_short($bInfo->date_status_change)));
      }
      break;
  }


      if ((zen_not_null($heading)) && (zen_not_null($contents))) {
            $box = new box;
            echo $box->infoBox($heading, $contents);
          }   /*  */
?>

<?php
  } 
?>
        </div>
      </div>

<!-- body_eof //-->
    <script>
      $(function(){
        $('input[name="date_added"]').datepicker();
      })
    </script>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
