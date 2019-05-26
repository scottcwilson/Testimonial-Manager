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

define('BOX_HEADING_TESTIMONIALS_MANAGER', 'Testimonials');
define('TESTIMONIALS_MANAGER_DISPLAY_ALL_TESTIMONIALS', 'View All Testimonials');
define('TESTIMONIALS_BY', 'By: ');
define('TESTIMONIALS_MANAGER_ADD_TESTIMONIALS', 'Add A Testimonial');

//Button Defines
define('BUTTON_IMAGE_TESTIMONIALS', 'button_testimonials.gif');
define('BUTTON_TESTIMONIALS_ADD_ALT', '<i class="fa fa-pencil-square-o"></i> Add Testimonial');
define('BUTTON_IMAGE_SUBMIT_TESTIMONIALS', 'button_submit_testimonials.gif');
define('BUTTON_TESTIMONIALS_SUBMIT_ALT', '&#xf1d8; Submit Testimonial');
define('BUTTON_IMAGE_VIEW_TESTIMONIALS', 'button_view_testimonials.gif');
define('BUTTON_VIEW_TESTIMONIALS_ALT', '<i class="fa fa-star"></i> View All Testimonials');
define('CITY_STATE_SEPARATOR', ',&nbsp;');
define('NAME_SEPARATOR', '&nbsp;&mdash;&nbsp;');
define('TESTIMONIALS_MANAGER_READ_MORE', 'Read More ->');

define('TESTIMONIALS_SUBMIT_THANKS', '<h3>Thank you for submitting a testimonial!</h3>');
define('TESTIMONIALS_SUBMIT_MESSAGE', '<p>Your testimonial will be actived as soon as our store manager reviews it.? If you have any questions regarding your testimonial, please contact us.</p><br /><h4>Thanks Again, we look forward to serving you in the future.</h4>');

// Change the words 'should_be_*' to something else..  If zenNonCAPTCHA is installed, the following is not used...
  defined('SPAM_ANSWER') || define('SPAM_ANSWER', 'Human');  //Slider error message if wrong.
  defined('SPAM_TEST_IQ') || define('SPAM_TEST_IQ', 'should_be_15');	//CAPTCHA field name.
  defined('SPAM_TEST_TEXT') || define('SPAM_TEST_TEXT', 'should_be_13');	//Hidden input field name.
  defined('SPAM_TEST_USER') || define('SPAM_TEST_USER', 'should_be_14');	//Hidden radio field name.  
  defined('SPAM_TEST') || define('SPAM_TEST', '10');	//Select a test number
  defined('HUMAN_TEXT_DISPLAYED') || define('HUMAN_TEXT_DISPLAYED', 'Are you Human? Slide to Human..');	//Slider Question Text.
  defined('HUMAN_TEXT_NOT_DISPLAYED') || define('HUMAN_TEXT_NOT_DISPLAYED', 'To prevent Spam we ask if you are a human or a computer.<br />If for some reason you are reading this line.<br /><b>Do not Answer!</b>'); //Radio hidden display text.
  defined('SPAM_ERROR') || define('SPAM_ERROR', 'You don\'t seem to be Human yet!'); //Slider error message if wrong.
  defined('SPAM_USE_SLIDER') || define('SPAM_USE_SLIDER', 'true'); //true=on false=off

//EOF
