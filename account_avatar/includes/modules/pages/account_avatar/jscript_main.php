<?php
/**
 * Account Auctions
 *
 * @package proauction 
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 *
 * Pro-Auction
 * @copyright Copyright 2012 SmokeGranderWebDesign Development Team
 * @license http://www.SmokeGranderWebDesign.com/license/LICENSE-3.TXT GNU Public License V3.0
 * @version $Id: jscript_main.php v1.0 5/5/2012 6:12 davewest $
 */
 
//
?>
<script language="javascript" type="text/javascript"><!--
/*countdown clock event davewest*/
  var days = <?php echo $remainingDay; ?>  
  var hours = <?php echo $remainingHour; ?>  
  var minutes = <?php echo $remainingMinutes; ?>  
  var seconds = <?php echo $remainingSeconds; ?>  
  
function setCountDown()
{
  seconds--;
  if (seconds < 0){
      minutes--;
      seconds = 59
  }
  if (minutes < 0){
      hours--;
      minutes = 59
  }
  if (hours < 0){
      days--;
      hours = 23
  }
if (minutes != 0 ) {
  document.getElementById("remain").innerHTML = days+" days, "+hours+" hours, "+minutes+" minutes, "+seconds+" seconds";
  setTimeout ( "setCountDown()", 1000 );
  } else {
  document.getElementById("remain").innerHTML = "Time is up, reload this page Please.";
}
}

/*lets use a onload event without affecting other onload events*/
if (window.addEventListener)
window.addEventListener("load", setCountDown, false)
else if (window.attachEvent)
window.attachEvent("onload", setCountDown)
else if (document.getElementById)
window.onload=setCountDown

//--></script>