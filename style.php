<?php
$result = $wpdb->get_row("SELECT value FROM {$wpdb->prefix}wooshare_options WHERE name = 'css'",OBJECT);
echo $result->value;
?>