<?php
/**
 * testimonials_manager jscript_main.php
 *
 * @package
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 *
 * @version $Id: Testimonials_Manager  v2.0 11-14-2018 davewest $
 */
?>
<script><!--
function popupWindow(url) {
 // simple popup window for a image

 var w = 380, h = 240;

    if (document.getElementById) {
      w = screen.availWidth;
      h = screen.availHeight;
    }

    var popW = 650, popH = 500;

    var leftPos = (w - popW) / 2;
    var topPos = (h - popH) / 2;


    msgWindow = window.open('', 'popup', 'width=' + popW + ',height=' + popH + ',top=' + topPos + ',left=' + leftPos + ',       scrollbars=yes');

     msgWindow.document.write ('<HTML><HEAD><TITLE>Popup Window</TITLE></HEAD><BODY><FORM NAME="form1">' + '<img src="' + url + '" />' + ' Click the button below to close this window.<br />' + '<INPUT TYPE="button" VALUE="OK"onClick="window.close();"></FORM></BODY></HTML>');
}    
//--></script>
