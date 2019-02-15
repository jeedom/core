<?php
require_once dirname(__FILE__) . "/core/php/core.inc.php";
header('Content-Type: application/json');
?>
{
  "short_name": "<?php echo config::byKey('product_name'); ?>",
  "background_color": "#ffffff",
  "theme_color": "#94ca02",
  "name": "<?php echo config::byKey('product_name'); ?>",
  "gcm_sender_id": "103953800507",
  "start_url": "index.php",
  "display": "standalone",
  "icons": [
  {
    "src": "<?php echo config::byKey('product_icon'); ?>",
    "sizes": "25x25",
    "type": "image/png"
  },
  {
    "src": "<?php echo config::byKey('product_icon192'); ?>",
    "sizes": "192x192",
    "type": "image/png"
  },
  {
    "src": "<?php echo config::byKey('product_icon512'); ?>",
    "sizes": "512x512",
    "type": "image/png"
  }
  ]
}
