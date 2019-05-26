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
 * @version $Id: Testimonials_Manager.php v2.2 02-10-2019 davewest $
 */

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_ADD_TESTIMONIALS');
 
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));



if (REGISTERED_TESTIMONIAL == 'true'){
  if (!$_SESSION['customer_id']) {
    $_SESSION['navigation']->set_snapshot();
    $messageStack->add_session('login', TEXT_TESTIMONIAL_LOGIN_PROMPT, 'warning');
    zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
}

  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    
    //saved in database table
        $rating = zen_db_prepare_input($_POST['rating']); //stars 1-5
        $feedback = zen_db_prepare_input($_POST['feedback']); //label of selected group
        $testimonials_name = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_name'])); //customer name
        $testimonials_mail = zen_db_prepare_input($_POST['testimonials_mail']); //email address
        $testimonials_title = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_title'])); //title default or user
        $testimonials_html_text = zen_db_prepare_input(strip_tags($_POST['testimonials_html_text'])); //message
        $testimonials_avatar = zen_db_prepare_input($_POST['avatar_register']);
        $make_public = zen_db_prepare_input($_POST['make_public']); //yes, no
        //footer lines
        $contact_user = zen_db_prepare_input($_POST['contact_3']);    //email, no, phone 
        $user_phone = zen_db_prepare_input(zen_sanitize_string($_POST['telephone'])); //123-123-1234           
        $privacy_conditions = zen_db_prepare_input($_POST['privacy_conditions']);  //1=checked 0=unchecked
           
    //used in admin only
        $testimonials_wanted = zen_db_prepare_input(zen_sanitize_string($_POST['find-1'])); //yes, no
        $ordered = zen_db_prepare_input($_POST['order1']); //yes, no
        $mobile_device = zen_db_prepare_input($_POST['mobile_device']);
        $mobile_device_name = zen_db_prepare_input(zen_sanitize_string($_POST['mobile_device_name']));
        $screen_size = zen_db_prepare_input(zen_sanitize_string($_POST['screen_size']));
        $feedback_about = zen_db_prepare_input($_POST['feedback_about']); //Associate feedback, In-Store experience, Associate feedback

//format image for storage/use
$base_image = substr($testimonials_avatar, strrpos($testimonials_avatar, '/')); //remove images/ folder from name
$testimonials_avatar = TESTIMONIAL_IMAGE_DIRECTORY . $base_image; //reformat with image base folder and name


            // Upload when form field is filled in by user 
            
         $noImage = true;
        if (DISPLAY_ADD_IMAGE == 'on') {  //on off turns on/off uploads
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
                $file = TM_UPLOAD_DIRECTORY . 'img'.date("YmdHis").$ext;
     if (file_put_contents(DIR_WS_IMAGES . $file, $data)) {
      $messageStack->add('new_testimonial', 'The image was saved');
  } else {
      $messageStack->add('new_testimonial', 'The image could not be saved' , 'error');
  } 
             
            } 
        }
   
                
        $gen_info = 
"Find what you wanted:" . ". . . " . $testimonials_wanted . "<br />" . 
"Already placed an order:" . " . " . $ordered . "<br />" .
"Mobile divice used:" . ". . . . " . $mobile_device . "<br />" .
"Mobile divice name:" . ". . . . " . $mobile_device_name . "<br />" .
"Screen info:" . ". . . . . . . " . $screen_size . "<br />" .
"In store feedback:" . ". . . . " . $feedback_about;
     
        
         
  $antiSpam = isset($_POST[SPAM_TEST_TEXT]) ? zen_db_prepare_input($_POST[SPAM_TEST_TEXT]) : '';
  $antiSpam .= isset($_POST[SPAM_TEST_USER]) ? zen_db_prepare_input($_POST[SPAM_TEST_USER]) : '';

//begin testing for errors
  $error = false;
if (SPAM_USE_SLIDER == 'true') { 
  $humanTest = zen_db_prepare_input($_POST[SPAM_TEST_IQ]);
  
     if ($humanTest != SPAM_TEST) {
     $messageStack->add('new_testimonial',SPAM_ERROR , 'error');
     $error = true; 
     }
  }
 
  $zc_validate_email = zen_validate_email($testimonials_mail);

//added for blockemail mod
if (!defined('BLOCK_EMAIL_STATUS')  && ('BLOCK_EMAIL_STATUS' == 'true')) {
  if ($zc_validate_email == TRUE) {
  $zc_validate_email = zc_validate_blockemail($testimonials_mail);
  $testimonials_mail = ($zc_validate_email == false) ? TESTIMONIAL_BLOCKEMAIL_ADDRESS_CHECK_ERROR : $testimonials_mail;
  $error = true;
  }
} elseif ($zc_validate_email == false) {
    $error = true;
    $messageStack->add('new_testimonial', ENTRY_EMAIL_ADDRESS_CHECK_ERROR, 'error');
  }

//check for banned email
  $check_banned = $db->Execute("SELECT * FROM " . TABLE_TESTIMONIALS_MANAGER . " WHERE testimonials_mail = '" . $testimonials_mail . "' and status = 2");

  if($check_banned->fields['status'] == '2') {
    $error = true;
    $messageStack->add('new_testimonial', 'Sorry, you was Banned for Spam!', 'error');
  }

   if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
    if ($privacy_conditions != '1') {
      $error = true;
      $messageStack->add('new_testimonial', ERROR_PRIVACY_STATEMENT_NOT_ACCEPTED, 'error');
    }
  }  
    
  if (strlen($testimonials_name) < 3) {
    $error = true;
    $messageStack->add('new_testimonial', ERROR_TESTIMONIALS_NAME_REQUIRED, 'error');
  } 
  
  if (empty($testimonials_html_text) or strlen($testimonials_html_text) < ENTRY_TESTIMONIALS_TEXT_MIN_LENGTH) {
    $error = true;
    $messageStack->add('new_testimonial', ERROR_TESTIMONIALS_TEXT_MIN_LENGTH, 'error');
  }
  
  if (strlen($testimonials_html_text) > ENTRY_TESTIMONIALS_TEXT_MAX_LENGTH) {
    $error = true;
    $entry_description_big_error = true;
    $messageStack->add('new_testimonial', ERROR_TESTIMONIALS_TEXT_MAX_LENGTH, 'error');  
  }
  
if ($contact_user == 'phone') {
  if (strlen($user_phone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $messageStack->add('new_testimonial', ENTRY_TELEPHONE_NUMBER_ERROR, 'error');
  }
}

  if (($rating < 1) || ($rating > 5)) {
    $error = true;
    $messageStack->add('new_testimonial', TESTIMONIAL_RATING, 'error');
  }
  
  if (($contact_user != 'no') && ($contact_user != 'email') && ($contact_user != 'phone')) {
    $error = true;
    $messageStack->add('new_testimonial', ERROR_CONTACT_USER, 'error');
  }
  
  if ($testimonials_title == '') {
    $error = true;
    $messageStack->add('new_testimonial', ERROR_TESTIMONIALS_TITLE, 'error');
  }
  

        
    if ($error == false) {
 // if anti-spam is not triggered, prepare and send email:
   if ($antiSpam != '') {
      $zco_notifier->notify('NOTIFY_SPAM_DETECTED_USING_CONTACT_US');
   } elseif ($antiSpam == '')  {

      $language_id = (int)$_SESSION['languages_id'];

	$sql_data_array = array(array('fieldName'=>'language_id', 'value'=>$language_id, 'type'=>'integer'), 
	                        array('fieldName'=>'testimonials_title', 'value'=>$testimonials_title, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_name', 'value'=>$testimonials_name, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_html_text', 'value'=>$testimonials_html_text, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_image', 'value'=>$testimonials_avatar, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_upimg', 'value'=>$file, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_mail', 'value'=>$testimonials_mail, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_rating', 'value'=>$rating, 'type'=>'integer'),
                                array('fieldName'=>'tm_feedback', 'value'=>$feedback, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_make_public', 'value'=>$make_public, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_contact_user', 'value'=>$contact_user, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_contact_phone', 'value'=>$user_phone, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_privacy_conditions', 'value'=>$privacy_conditions, 'type'=>'integer'),
                                array('fieldName'=>'status', 'value'=>0, 'type'=>'integer'),
                                array('fieldName'=>'date_added', 'value'=>'now()', 'type'=>'noquotestring'),
                                array('fieldName'=>'tm_gen_info', 'value'=>$gen_info, 'type'=>'stringIgnoreNull'),
                               );


    $db->perform(TABLE_TESTIMONIALS_MANAGER, $sql_data_array);

   $testimonials_id = $db->Insert_ID(); 
 
 // build the message content
  
      $name = $testimonials_name;  //sender name
      $email_text = sprintf(EMAIL_GREET_NONE, $name);
      
      $html_msg['EMAIL_GREETING'] = str_replace('\n','',$email_text);


      // initial welcome
      $email_text .=  EMAIL_WELCOME;
	  $html_msg['EMAIL_WELCOME'] = str_replace('\n','',EMAIL_WELCOME);
      // add in regular email welcome text
      $email_text .= "\n\n" . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_GV_CLOSURE;      
	  $html_msg['EMAIL_MESSAGE_HTML']  = str_replace('\n','',EMAIL_TEXT);
	  
 //$html_msg['EMAIL_MESSAGE_HTML'] = $gen_info;
 	  
	  $html_msg['EMAIL_CONTACT_OWNER'] = str_replace('\n','',EMAIL_CONTACT);
	  $html_msg['EMAIL_CLOSURE']       = nl2br(EMAIL_GV_CLOSURE);

    // include create-account-specific disclaimer
    $email_text .= "\n\n" . sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, STORE_OWNER_EMAIL_ADDRESS). "\n\n";
    $html_msg['EMAIL_DISCLAIMER'] = sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS .' </a>');
    
// send welcome email
   zen_mail($name, $testimonials_mail, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $html_msg, 'testimonial_add');
    // zen_mail($name, $testimonials_mail, EMAIL_SUBJECT, $email_text, STORE_NAME, EMAIL_FROM, $html_msg, 'testimonial_add');
  
   ////SEND ADMIN EMAIL   
   zen_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_OWNER_SUBJECT, $email_text, $name, $testimonials_mail, $html_msg, 'testimonial_add');
   

  }
      zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_ADD, 'action=success'));

}
  } // eof form submit

if (!empty($_SESSION['customer_id'])) {
  $sql = "SELECT * FROM " . TABLE_CUSTOMERS . " WHERE customers_id = :customersID ";
  
  $sql = $db->bindVars($sql, ':customersID', $_SESSION['customer_id'], 'integer');
  $check_customer = $db->Execute($sql);
  
      $testimonials_mail = $check_customer->fields['customers_email_address'];
      $testimonials_name = $check_customer->fields['customers_firstname'];
      $user_phone = $check_customer->fields['customers_telephone'];
      $tm_avatar = $check_customer->fields['tm_avatar'];  //only works with user avatar installed

}

//images/avatars/at_1.png
//get all avatars with at_ .png ONLY 
//any avatars not name as above is not used
//randaum selection of 7 to display.
$dir = 'images/' . TESTIMONIAL_IMAGE_DIRECTORY;  
$at_avatars = '';
$loop = GLOB($dir. '{at_}*{jpg,png}', GLOB_BRACE); // look for only jpg and png *{.jpg, .png}

$catchme = array_rand($loop, 7);

 $at_avatars .= '<img src="' . $loop[$catchme[0]] . '"><img src="' . $loop[$catchme[1]] . '"><img src="' . $loop[$catchme[2]] . '"><img src="' . $loop[$catchme[3]] . '"><img src="' . $loop[$catchme[4]] . '"><img src="' . $loop[$catchme[5]] . '"><img src="' . $loop[$catchme[6]] . '"> ';

  // include template specific file name defines

$define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_TESTIMONIALS_ADD, 'false');

    $breadcrumb->add(NAVBAR_TITLE);

  // This should be last line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_END_ADD_TESTIMONIALS');
