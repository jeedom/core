<?php
require_once dirname(__FILE__) . "/core/php/core.inc.php";
header('Content-Type: application/json');
?>
{
  "short_name": "<?php echo config::byKey('product_name'); ?>",
  "background_color": "#ffffff",
  "theme_color": "#ffffff",
  "name": "<?php echo config::byKey('product_name'); ?>",
  "gcm_sender_id": "103953800507",
  "icons": [
    {
      "src": "<?php echo config::byKey('product_icon'); ?>",
      "sizes": "72x72",
      "type": "image/png"
    },
    {
      "src": "<?php echo config::byKey('product_icon'); ?>",
      "sizes": "96x96",
      "type": "image/png"
    },
    {
      "src": "<?php echo config::byKey('product_icon'); ?>",
      "sizes": "128x128",
      "type": "image/png"
    },
    {
      "src": "<?php echo config::byKey('product_icon'); ?>",
      "sizes": "144x144",
      "type": "image/png"
    },
    {
      "src": "<?php echo config::byKey('product_icon'); ?>",
      "sizes": "192x192",
      "type": "image/png"
    },
     {
      "src": "<?php echo config::byKey('product_icon'); ?>",
      "sizes": "256x256",
      "type": "image/png"
    }
  ],
  "start_url": "index.php",
  "display": "standalone"
}
