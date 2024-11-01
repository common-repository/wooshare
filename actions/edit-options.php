<?php
$i = false;
foreach ($_POST as $key=>$value) {
	$query = "UPDATE `{$wpdb->prefix}wooshare_options` SET `value` = '$value' WHERE `name` = '$key'";
	$wpdb->query($query);
}
?>