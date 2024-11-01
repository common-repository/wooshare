<div class="wrap">
<h2>WooShare Settings</h2>
<?php
global $wpdb;
$table_name = $wpdb->prefix . 'wooshare_links';
$woodir = '../wp-content/plugins/wooshare/';
if (isset($_POST['id']) || isset($_POST['new']))
{
	include($woodir.'actions/edit.php');
	if (!trim($_POST['name']) || !trim($_POST['url'])) {
		echo '<div class="error">Name and URL must be filled!</div>';
		$_GET['edit'] = $_POST['id'];
		include($woodir.'layouts/edit.php');
	} else {
		$_POST = null;
	}
}
elseif (isset($_POST['delete']))
	include($woodir.'actions/edit.php');
elseif ($_POST['action'] == 'edit-options')
	include($woodir.'actions/edit-options.php');
if (!isset($_POST['id'])) {
	if (!isset($_GET['edit']) && !isset($_GET['new']) && !isset($_GET['delete'])) {
		include($woodir.'layouts/list.php');
		include($woodir.'layouts/options.php');
	}
	else {
		if (isset($_GET['delete'])) {
			include($woodir.'layouts/delete.php');
		}
	}
}
?>
</div>