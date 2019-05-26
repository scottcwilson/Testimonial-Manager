<?php
/**
 * Testimonials Manager
  * @copyright 2007-2008 Clyde Jones
 *
 * @package Admin
  * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Testimonials_Manager.php v2.0 11-14-2018 davewest $
 */

define('HEADING_TITLE', 'Testimonials Manager');
define('TABLE_HEADING_TESTIMONIALS', 'Testimonials Title');
define('TABLE_HEADING_NAME', 'Author\'s Name');
define('TABLE_HEADING_MAIL', 'Author\'s E-Mail');
define('TABLE_HEADING_LANGUAGES', 'Language');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_SORT_ORDER', 'Sort Order');
define('TEXT_TESTIMONIALS_TITLE', 'Testimonials Title:');
define('TEXT_TESTIMONIALS_NAME', 'Author\'s Name:');
define('TEXT_TESTIMONIALS_URL', 'Author\'s URL:');
define('TEXT_TESTIMONIALS_MAIL', 'Author\'s E-Mail:');
define('TEXT_TESTIMONIALS_DATE', 'Date Added:');
define('ENTRY_DATE_ADDED_TEXT', '&nbsp;(eg. 05/21/1970)');
define('TEXT_TESTIMONIALS_COMPANY', 'Company Name:');
define('TEXT_TESTIMONIALS_CITY', 'City:');
define('TEXT_TESTIMONIALS_COUNTRY', 'State or Country:');
define('TEXT_TESTIMONIALS_HTML_TEXT', 'Testimonial:');
define('TEXT_TESTIMONIALS_DATE_INFO', '<strong>Leaving the Date Added Field empty will insert today\'s date<br />or you can enter the date of any past testimonials.</strong>');
define('TABLE_HEADING_DATE_ADDED', 'Date Added:');
//define('TEXT_FIELD_OPTIONAL', '&nbsp;<span class="fieldRequired">(Optional)</span>');

define('TEXT_INFO_TESTIMONIALS_STATUS', 'Status:');
define('TEXT_INFO_TESTIMONIALS_RATING', 'Store Rating:');
define('TEXT_INFO_TESTIMONIALS_PUBLIC', 'Can we Display this one:');
define('TEXT_INFO_TESTIMONIALS_FEEDBACK', 'Testimonial Feedback:');
define('TEXT_INFO_TESTIMONIALS_CONTACT_NAME', 'Contact Name:');
define('TEXT_INFO_TESTIMONIALS_CONTACT_EMAIL', 'Contact Email:');
define('TEXT_INFO_TESTIMONIALS_TITLE', 'Testimonial Title:');
define('TEXT_INFO_TESTIMONIALS_DESCRIPTION', 'Testimonial:');
define('TEXT_DATE_TESTIMONIALS_CREATED', 'Testimonial Submitted:');
define('TEXT_DATE_TESTIMONIALS_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_INFO_TESTIMONIALS_CONTACT', 'Can we Contact you:');
define('TEXT_INFO_TESTIMONIALS_CONTACT_PHONE', 'Contact Phone:');
define('TEXT_INFO_TESTIMONIALS_PRIVACY', 'Privacy Checked:');
define('TEXT_INFO_TESTIMONIALS_GEN_INFO', 'More INFO:');

define('TEXT_TESTIMONIALS_STATUS_CHANGE', 'Status Change: %s');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this Testimonial?');
define('TEXT_INFO_DELETE_IMAGE', 'Delete this Image?');
define('SUCCESS_PAGE_INSERTED', 'Success: The Testimonial has been inserted.');
define('SUCCESS_PAGE_UPDATED', 'Success: The Testimonial has been updated.');
define('SUCCESS_PAGE_REMOVED', 'Success: The Testimonials item has been removed.');
define('SUCCESS_PAGE_STATUS_UPDATED', 'Success: The status of the Testimonial item has been updated.');
define('ERROR_PAGE_AUTHOR_REQUIRED', 'Error: The Author\'s Name is Required!');
define('ERROR_PAGE_EMAIL_REQUIRED', 'Error: The Author\'s E-mail Address is Required!');
define('ERROR_PAGE_TEXT_REQUIRED', 'Error: A Testimonial is Required!');
define('ERROR_PAGE_TITLE_REQUIRED', 'Error: A Testimonial Title is Required!');
define('ERROR_UNKNOWN_STATUS_FLAG', 'Error: Unknown status flag.');
define('TABLE_HEADING_STATUS', 'Status');
define('TEXT_DISPLAY_NUMBER_OF_TESTIMONIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> testimonials items)');
define('IMAGE_NEW_PAGE', 'New Testimonials Item');
define('TEXT_INFO_PAGE_IMAGE', 'Testimonial Image:');
define('TEXT_INFO_CURRENT_IMAGE', 'Current Image:');
define('TEXT_IMAGES_TESTIMONIALS_DELETE', '<strong>Use \'No Avatar\' image!</strong>');
define('TEXT_INFO_DELETE_IMAGE', '<strong>Please Delete the Avatar image yourself!</strong>');
define('TEXT_AVATAR_IMAGE_MANUAL', 'Or, select an existing Avatar image file from server, filename:');
define('TEXT_AVATAR_PAGE_IMAGE', 'Testimonial Avatar:');
define('TEXT_AVATAR_CURRENT_IMAGE', 'Current Avatar:');

define('TEXT_REMOVE_WARRING', '<b> Warring: </b> this will remove only the menus and configuration settings!<br /> You must delete all files yourself!');
define('TEXT_UPDATE_WARRING', '<b> Warring: </b> this will check for updated Testimonials Manager from Zen Cart Plugin\'s. If one exists, you will still need to download and follow the install/upgrade instructions!');
define('TEXT_UPDATE_DISCLAMER','<b>Note: </b> We don\'t endorse auto checking by constantly calling to another site<br />We will make it easy for you to do that by clicking this check button, your option not mine.');

define('TEXT_TM_STATUS_0', 'Pending Review');
define('TEXT_TM_STATUS_1', 'Approved');
define('TEXT_TM_STATUS_2', 'Banned - Not allowed to create');

?>
