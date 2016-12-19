<?php

/*
  Plugin Name: Click To Call
  Plugin URI: http://mikejakobsen.com
  Description:
  Author: Hoeks
  Version: 0.0.1
  Author URI: http://mikejakobsen.com
  Text Domain: ....
 */

function if_mobile($atts, $content = null) {
  wp_enqueue_script('ifmobile');
  ob_start();
  extract(shortcode_atts(array(
    'device' => 'any',
  ), $atts));
?>

  <script>
  // Call Script
  </script>

/*
  - Absolute positioned element - Maybe add overlay.
 */

<div class="if_mobile">
    <?php echo $content; ?>
</div>
