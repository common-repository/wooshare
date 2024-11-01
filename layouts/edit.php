<?php
$item = $wpdb->get_row("SELECT * FROM `$table_name` WHERE `id` = '" . $wpdb->escape($_GET['edit']) . "'", OBJECT);
	$item->url = str_replace(array('"'),array('&quot;'),$item->url);
?>
<form action="plugins.php?page=wooshare/options.php" method="post">
<? echo
($_GET['edit']) ?
'<input type="hidden" name="id" value="'.$item->id.'" />' :
'<input type="hidden" name="new" value="true" />';
?>
<table class="form-table">
	<tr>
		<th scope="row">Name*</th>
		<td><input type="text" name="name" value="<?=($_POST['name']) ? $_POST['name'] : $item->name ?>" class="regular-text" /></td>
	</tr>
	<tr>
		<th scope="row">Button Text</th>
		<td><input type="text" name="text" value="<?=($_POST['text']) ? $_POST['text'] : $item->text ?>" class="regular-text" /></td>
	</tr>
	<tr>
		<th scope="row">Button Image</th>
		<td><input type="text" name="image" value="<?=($_POST['image']) ? $_POST['image'] : $item->image ?>" class="regular-text" /></td>
	</tr>
	<tr>
		<th scope="row">Button Title</th>
		<td><input type="text" name="title" value="<?=($_POST['title']) ? $_POST['title'] : $item->title ?>" class="regular-text" /></td>
	</tr>
	<tr>
		<th scope="row">Button URL*</th>
		<td>
			<input type="text" name="url" value="<?=str_ireplace("\\",'',$item->url) ?>" class="regular-text" />
			<span class="description">Enter the URL to be linked to. You can use %url% and %title% to be replaced with the url or title of the post.</span>
		</td>
	</tr>
</table><br />
<span class="description">* required field</span>
<p class="submit">
<input type="submit" class="button-primary" value="<? _e('Save Changes')?>" />
</p>
</form>