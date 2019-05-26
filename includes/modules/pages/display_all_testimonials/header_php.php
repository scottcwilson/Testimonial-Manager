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
$zco_notifier->notify('NOTIFY_HEADER_START_ALL_TESTIMONIALS');

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

  $breadcrumb->add(NAVBAR_TITLE);
  
  $testimonials_query_raw = "select * from " . TABLE_TESTIMONIALS_MANAGER . " where status = 1 and tm_make_public = 'yes' and language_id = '" . (int)$_SESSION['languages_id'] . "' order by date_added DESC, testimonials_title";

  $testimonials_split = new splitPageResults($testimonials_query_raw, MAX_DISPLAY_TESTIMONIALS_MANAGER_ALL_TESTIMONIALS);

  // This should be last line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_END_ALL_TESTIMONIALS');
