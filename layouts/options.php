<?php
	$options = $wpdb->get_results("SELECT `name`,`friendly_name`,`value` FROM `{$wpdb->prefix}wooshare_options`", OBJECT);
?>
<h2>Options</h2>
<form action="plugins.php?page=wooshare/options.php" method="post">
<table class="form-table">
<input type="hidden" name="action" value="edit-options" />
<?php
	foreach ($options as $option) :
?>
	<tr>
		<th scope="row"><?=$option->friendly_name?></th>
		<td>
			<?php
				$fill = $_POST[$option->name] ? $_POST[$option->name] : $option->value;
				if ($option->name != 'css')
					echo '
					<input type="text" name="'.$option->name.'" value="'.$fill.'" class="regular-text" />';
				else echo '
					<textarea name="'.$option->name.'" cols="40" rows="10">'.$fill.'</textarea>';
			?>
		</td>
	</tr>
<?php
	endforeach;
?>
</table>
<input type="submit" name="edit-options" class="button-primary" value="<? _e('Save Changes')?>" />
</form>