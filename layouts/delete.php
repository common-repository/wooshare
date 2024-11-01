<form action="plugins.php?page=wooshare/options.php" method="post">
	<input type="hidden" name="delete" value="<?=$wpdb->escape($_GET['delete'])?>" />
	Are you sure you want to delete this link? The action CAN NOT be undone!
	<p class="submit">
	<input type="submit" class="button-primary" value="<? _e('Yes')?>" />
	<input type="button" value="<? _e('No')?>" onclick="document.location.href='plugins.php?page=wooshare/options.php'" />
	</p>
</form>