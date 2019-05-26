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

  //count of total reviews
    $feedback_query = "select count(*) as count from " . TABLE_TESTIMONIALS_MANAGER . " 
                       where language_id = '" . (int)$_SESSION['languages_id'] . "'
                        and status = '1'";

    $feedback = $db->Execute($feedback_query);
    
  //Average Store Rating    
    $feedback_average_rating_query = "select avg(tm_rating) as average_rating from " . TABLE_TESTIMONIALS_MANAGER . "
                       where language_id = '" . (int)$_SESSION['languages_id'] . "'
                        and status = '1'";

    $feedback_average_rating = $db->Execute($feedback_average_rating_query);

$stars_rating = number_format((float)$feedback_average_rating->fields['average_rating'], 1, '.', '');
$prating = number_format((float)$feedback_average_rating->fields['average_rating'], 2, '.', '')  * 20;

echo '<link rel="stylesheet" type="text/css" href="' . ($template->get_template_dir('/tm_shop_reviews_star.css',DIR_WS_TEMPLATE, $current_page_base,'css') . '/tm_shop_reviews_star.css') . '" type="text/css" />';

echo '<h2>Number of store reviews of ' . $feedback->fields['count'] . ' for a average rating of ' . $stars_rating . ' out of 5 stars!</h2>';

echo '<div class="star-rating" title="Total Average rating!">
    <div class="back-stars">
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        
        <div class="front-stars" style="width: ' . $prating . '%">
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
            <i class="fa fa-star" aria-hidden="true"></i>
        </div>
    </div>
</div> ';
?>

