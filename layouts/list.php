<table class="form-table">
<?php
	$links = $wpdb->get_results("SELECT `id`,`name`,`text`,`image`,`title`,`url` FROM `$table_name`", OBJECT);
	foreach ($links as $item) :
?>
	<tr>
		<th scope="row">
			<img src="<?=$item->image?>" /> <?=($item->text) ? $item->text : $item->name.' (no title)'?></a><br />
			<pre><?=str_ireplace("\\",'',$item->url)?></pre>
		</th>
		<td valign="top">
			<a href="plugins.php?page=wooshare/options.php&edit=<?=$item->id?>">Edit</a>
			| <a href="plugins.php?page=wooshare/options.php&delete=<?=$item->id?>">Delete</a>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<p class="submit">
<input type="button" class="button-primary" value="<?_e('New Link')?>" onclick="document.location.href='plugins.php?page=wooshare/options.php&new=true'" />
</p>