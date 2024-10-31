<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if (isset($_POST['frm_pop_display']) && sanitize_text_field($_POST['frm_pop_display']) == 'yes') {
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { 
		die('<p>Are you sure you want to do this?</p>'); 
	}
	
	$pop_success_msg = false;
	$result = popupdbox_cls_dbquery::popupdbox_count($did);
	
	if ($result != '1') {
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'popup-dropping-box'); ?></strong></p></div><?php
	}
	else {
		if (isset($_GET['ac']) && sanitize_text_field($_GET['ac']) == 'del' && isset($_GET['did']) && sanitize_text_field($_GET['did']) != '') {
			check_admin_referer('pop_form_show');
			popupdbox_cls_dbquery::popupdbox_delete($did);
			$pop_success_msg = true;
		}
	}
	
	if ($pop_success_msg == true) {
		?><div class="updated fade"><p><strong><?php _e('Selected record was successfully deleted.', 'popup-dropping-box'); ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
    <h2><?php _e('Popup dropping box', 'popup-dropping-box'); ?>
	<a class="add-new-h2" href="<?php echo POPUPDBOX_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'popup-dropping-box'); ?></a></h2>
    <div class="tool-box">
	<?php
	$myData = array();
	$myData = popupdbox_cls_dbquery::popupdbox_select();
	?>
	<form name="frm_pop_display" method="post">
      <table class="widefat" cellspacing="0">
        <thead>
          <tr>
		  	<th width="30%"><?php _e('Title (ID)', 'popup-dropping-box'); ?></th>
			<th><?php _e('Group', 'popup-dropping-box'); ?></th>
			<th><?php _e('Width', 'popup-dropping-box'); ?></th>
			<th><?php _e('Date', 'popup-dropping-box'); ?></th>
            <th><?php _e('Display', 'popup-dropping-box'); ?></th>
			<th><?php _e('Window', 'popup-dropping-box'); ?></th>
			<th><?php _e('Position', 'popup-dropping-box'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
		  	<th><?php _e('Title (ID)', 'popup-dropping-box'); ?></th>
			<th><?php _e('Group', 'popup-dropping-box'); ?></th>
			<th><?php _e('Width', 'popup-dropping-box'); ?></th>
			<th><?php _e('Date', 'popup-dropping-box'); ?></th>
            <th><?php _e('Display', 'popup-dropping-box'); ?></th>
			<th><?php _e('Window', 'popup-dropping-box'); ?></th>
			<th><?php _e('Position', 'popup-dropping-box'); ?></th>
          </tr>
        </tfoot>
		<tbody>
		<?php 
		$i = 0;
		if(count($myData) > 0 ) {
			foreach ($myData as $data) {
				?>
				<tr class="<?php if ($i&1) { echo ''; } else { echo 'alternate'; }?>">
					<td>
					<?php echo $data['pop_title']; ?> (<?php echo $data['pop_id']; ?>)
					<div class="row-actions">
					<span class="edit">
					<a title="Edit" href="<?php echo POPUPDBOX_ADMIN_URL; ?>&ac=edit&amp;did=<?php echo $data['pop_id']; ?>"><?php esc_html_e('Edit', 'popup-dropping-box'); ?></a> | </span>
					<span class="trash">
					<a onClick="javascript:_popupdbox_delete('<?php echo $data['pop_id']; ?>')" href="javascript:void(0);"><?php esc_html_e('Delete', 'popup-dropping-box'); ?></a></span> 
					</div>
					</td>
					<td><?php echo $data['pop_group']; ?></td>
					<td><?php echo $data['pop_width']; ?></td>
					<td>
					<?php
					$pop_start = $data['pop_start'];
					$pop_end = $data['pop_end'];
					$now_strtotime = strtotime(date("Y-m-d"));
					$str_strtotime = strtotime($data['pop_start']);
					$end_strtotime = strtotime($data['pop_end']);
					if($end_strtotime < $now_strtotime) {
						$pop_end = '<span style="color:#FF0000;">' . $data['pop_end'] . '</span>';
					}
					if($str_strtotime > $now_strtotime) {
						$pop_start = '<span style="color:#FF0000;">' . $data['pop_start'] . '</span>';
					}
					?>
					<?php _e('Start', 'popup-dropping-box'); ?> : <?php echo $pop_start; ?> <br />
					<?php _e('End', 'popup-dropping-box'); ?> : <?php echo $pop_end; ?>
					</td>
					<td><?php echo popupdbox_cls_dbquery::popupdbox_common_text($data['pop_status']); ?></td>
					<td>
					<?php _e('Color', 'popup-dropping-box'); ?> : <?php echo $data['pop_bgcolor']; ?> <br />
					<?php _e('Boxtype', 'popup-dropping-box'); ?> : <?php echo popupdbox_cls_dbquery::popupdbox_common_text($data['pop_boxtype']); ?> <br />
					</td>
					<td><?php echo $data['pop_positionx']; ?> <?php echo $data['pop_positiony']; ?></td>
				</tr>
				<?php 
				$i = $i+1; 
			} 
		}
		else {
			?><tr><td colspan="7" align="center"><?php _e('No records available', 'popup-dropping-box'); ?></td></tr><?php 
		}
		?>
		</tbody>
        </table>
		<?php wp_nonce_field('pop_form_show'); ?>
		<input type="hidden" name="frm_pop_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
	  <a href="<?php echo POPUPDBOX_ADMIN_URL; ?>&amp;ac=add">
	  <input class="button button-primary" type="button" value="<?php _e('Add New', 'popup-dropping-box'); ?>" /></a>
	  <a target="_blank" href="http://www.gopiplus.com/work/2019/12/25/popup-dropping-box-wordpress-plugin/">
	  <input class="button button-primary" type="button" value="<?php _e('Short Code', 'popup-dropping-box'); ?>" /></a>
	  <a target="_blank" href="http://www.gopiplus.com/work/2019/12/25/popup-dropping-box-wordpress-plugin/">
	  <input class="button button-primary" type="button" value="<?php _e('Help', 'popup-dropping-box'); ?>" /></a>
	  </div>
	</div>
</div>