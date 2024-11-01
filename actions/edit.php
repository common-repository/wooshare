<?php
if (trim($_POST['name']) && trim($_POST['url'])) {
	if ($_POST['id'])
		$wpdb->query("UPDATE `$table_name` SET `name` = '".$wpdb->escape($_POST['name'])."',
		`text` = '".$wpdb->escape($_POST['text'])."',
		`image` = '".$wpdb->escape($_POST['image'])."',
		`title` = '".$wpdb->escape($_POST['title'])."',
		`url` = '".$wpdb->escape($_POST['url'])."'
		WHERE `id` = '{$_POST['id']}'" );
	elseif ($_POST['new'])
		$wpdb->query("INSERT INTO `$table_name`
		(`name`,`text`,`image`,`title`,`url`)
		VALUES (
		'".$wpdb->escape($_POST['name'])."',
		'".$wpdb->escape($_POST['text'])."',
		'".$wpdb->escape($_POST['image'])."',
		'".$wpdb->escape($_POST['title'])."',
		'".$wpdb->escape($_POST['url'])."')" );
}
elseif (isset($_POST['delete']))
	$wpdb->query("DELETE FROM `$table_name` WHERE `id` = '{$_POST['delete']}'");
?>