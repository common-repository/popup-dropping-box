<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$pop_errors = array();
$pop_success = '';
$pop_error_found = false;

$form = array(
	'pop_title' => '',
	'pop_text' => '',
	'pop_group' => '',
	'pop_width' => '',
	'pop_bgcolor' => '',
	'pop_boxtype' => '',
	'pop_animation' => '',
	'pop_positionx' => '',
	'pop_positiony' => '',
	'pop_freq' => '',
	'pop_deferred' => '',
	'pop_showduration' => '',
	'pop_status' => '',
	'pop_start' => '',
	'pop_end' => ''
);

if (isset($_POST['pop_form_submit']) && sanitize_text_field($_POST['pop_form_submit']) == 'yes') {
	check_admin_referer('pop_form_add');
	
	$form['pop_title'] = isset($_POST['pop_title']) ? sanitize_text_field($_POST['pop_title']) : '';
	if ($form['pop_title'] == '') {
		$pop_errors[] = __('Please enter popup title.', 'popup-dropping-box');
		$pop_error_found = true;
	}

	$form['pop_text'] = isset($_POST['pop_text']) ? wp_filter_post_kses($_POST['pop_text']) : '';
	$form['pop_group'] = isset($_POST['pop_group']) ? sanitize_text_field($_POST['pop_group']) : '';
	if ($form['pop_group'] == '') {
		$form['pop_group'] = isset($_POST['pop_group_txt']) ? sanitize_text_field($_POST['pop_group_txt']) : '';
	}
	if ($form['pop_group'] == '') {
		$pop_errors[] = __('Please select group for this popup.', 'popup-dropping-box');
		$pop_error_found = true;
	}
	
	$form['pop_width'] = isset($_POST['pop_width']) ? intval(sanitize_text_field($_POST['pop_width'])) : '';
	$form['pop_bgcolor'] = isset($_POST['pop_bgcolor']) ? sanitize_text_field($_POST['pop_bgcolor']) : '0';
	$form['pop_boxtype'] = isset($_POST['pop_boxtype']) ? sanitize_text_field($_POST['pop_boxtype']) : '';
	$form['pop_animation'] = isset($_POST['pop_animation']) ? sanitize_text_field($_POST['pop_animation']) : '';
	$form['pop_positionx'] = isset($_POST['pop_positionx']) ? sanitize_text_field($_POST['pop_positionx']) : '';
	$form['pop_positiony'] = isset($_POST['pop_positiony']) ? sanitize_text_field($_POST['pop_positiony']) : '';
	$form['pop_freq'] = isset($_POST['pop_freq']) ? sanitize_text_field($_POST['pop_freq']) : '';
	$form['pop_deferred'] = isset($_POST['pop_deferred']) ? intval(sanitize_text_field($_POST['pop_deferred'])) : '';
	$form['pop_showduration'] = isset($_POST['pop_showduration']) ? intval(sanitize_text_field($_POST['pop_showduration'])) : '';
	$form['pop_status'] = isset($_POST['pop_status']) ? sanitize_text_field($_POST['pop_status']) : '';
	$form['pop_start'] = isset($_POST['pop_start']) ? sanitize_text_field($_POST['pop_start']) : '';
	$form['pop_end'] = isset($_POST['pop_end']) ? sanitize_text_field($_POST['pop_end']) : '';
	
	if ($form['pop_deferred'] == 0) {
		$form['pop_deferred'] = 1;
	}
	
	if ($form['pop_showduration'] == 0) {
		$form['pop_showduration'] = 10;
	}
	
	if ($form['pop_status'] != 'Yes' && $form['pop_status'] != 'No') {
		$form['pop_status'] = 'Yes';
	}
	
	if ($pop_error_found == false)
	{
		$status = popupdbox_cls_dbquery::popupdbox_insert($form);
		if($status == 'inserted') {
			$pop_success = __('New details was successfully added.', 'popup-dropping-box');
		}
		else {
			$pop_errors[] = __('Oops, something went wrong. try again.', 'popup-dropping-box');
			$pop_error_found = true;
		}
		
		$form = array(
			'pop_title' => '',
			'pop_text' => '',
			'pop_group' => '',
			'pop_width' => '',
			'pop_bgcolor' => '',
			'pop_boxtype' => '',
			'pop_animation' => '',
			'pop_positionx' => '',
			'pop_positiony' => '',
			'pop_freq' => '',
			'pop_deferred' => '',
			'pop_showduration' => '',
			'pop_status' => '',
			'pop_start' => '',
			'pop_end' => ''
		);
	}
}

if ($pop_error_found == true && isset($pop_errors[0]) == true) {
	?><div class="error fade"><p><strong><?php echo $pop_errors[0]; ?></strong></p></div><?php
}
if ($pop_error_found == false && strlen($pop_success) > 0) {
	?><div class="updated fade"><p><strong><?php echo $pop_success; ?>
	<a href="<?php echo POPUPDBOX_ADMIN_URL; ?>"><?php _e('Click here', 'popup-dropping-box'); ?></a> <?php _e('to view the details', 'popup-dropping-box'); ?>
	</strong></p></div><?php
}
?>

<script language="JavaScript" src="<?php echo plugin_dir_url( __DIR__ ); ?>inc/color/jscolor.js"></script>
<div class="form-wrap">
	<h1 class="wp-heading-inline"><?php _e('Add popup', 'popup-dropping-box'); ?></h1>
	<form name="pop_form" method="post" action="#" onsubmit="return _popupdbox_submit()" >
	  
	  <label><strong><?php _e('Popup title', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_title" type="text" id="pop_title" value="" size="60" maxlength="1000" />
      <p><?php _e('Please enter popup title.', 'popup-dropping-box'); ?></p>
	  
      <label><strong><?php _e('Popup text', 'popup-dropping-box'); ?></strong></label>
      <?php 
	  wp_editor("", "pop_text"); 
	  ?>
      <p><?php _e('Please enter popup text.', 'popup-dropping-box'); ?></p>
	    
      <label><strong><?php _e('Popup group', 'popup-dropping-box'); ?></strong></label>
		<select name="pop_group" id="pop_group">
			<option value=''><?php _e('Select', 'email-posts-to-subscribers'); ?></option>
			<?php
			$groups = array();
			$groups = popupdbox_cls_dbquery::popupdbox_group();
			if(count($groups) > 0) {
				foreach ($groups as $group) {
					?>
					<option value="<?php echo stripslashes($group["pop_group"]); ?>">
						<?php echo stripslashes($group["pop_group"]); ?>
					</option>
					<?php
				}
			}
			?>
		</select>
		(or) 
	   	<input name="pop_group_txt" type="text" id="pop_group_txt" value="" maxlength="10" onkeyup="return _popupdbox_numericandtext(document.pop_form.pop_group_txt)" />
      <p><?php _e('Please select group for this popup.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup window width', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_width" type="text" id="pop_width" value="300" maxlength="3" />
      <p><?php _e('Please enter width of the popup window, only number.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup window background', 'popup-dropping-box'); ?></strong></label>
      <input class="color" type="text" name="pop_bgcolor" id="pop_bgcolor" value="#EDEDED" maxlength="7" />
      <p><?php _e('Please select background color of the popup window.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup window type', 'popup-dropping-box'); ?></strong></label>
      <select name="pop_boxtype" id="pop_boxtype">
        <option value='round'>Rounded Corner</option>
        <option value='square'>Square Corner </option>
      </select>
      <p><?php _e('Please select popup box type.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup window position', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_positionx" type="text" id="pop_positionx" value="center" maxlength="10" />
      <p><?php _e('Please enter popup position x value.', 'popup-dropping-box'); ?> (ex: center, 30, 50, -10, -20)</p>
	  
	  <label><strong><?php _e('Popup window position', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_positiony" type="text" id="pop_positiony" value="center" maxlength="10" />
      <p><?php _e('Please enter popup position y value', 'popup-dropping-box'); ?> (ex: center, 30, 50, -10, -20)</p>
	  
	  <label><strong><?php _e('Popup window deferred', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_deferred" type="text" id="pop_deferred" value="1" maxlength="3" />
      <p><?php _e('Please enter popup window delay value in seconds, only number.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup window show duration', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_showduration" type="text" id="pop_showduration" value="10" maxlength="3" />
      <p><?php _e('Please enter popup window disappear seconds, only number.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup window frequency', 'popup-dropping-box'); ?></strong></label>
      <select name="pop_freq" id="pop_freq">
        <option value='always'>Always</option>
        <option value='session'>Once every session</option>
		<option value='5min'>Once every 5 min</option>
		<option value='30min'>Once every 30 min</option>
		<option value='1days'>Once every day</option>
      </select>
      <p><?php _e('Please select popup window frequency.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Display status', 'popup-dropping-box'); ?></strong></label>
      <select name="pop_status" id="pop_status">
        <option value='Yes'>Yes</option>
        <option value='No'>No</option>
      </select>
      <p><?php _e('Please select display status of this popup.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup start date', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_start" type="text" id="pop_start" value="<?php echo date("Y-m-d"); ?>" maxlength="10" />
      <p><?php _e('Please enter start date of this popup window, format YYYY-MM-DD.', 'popup-dropping-box'); ?></p>
	  
	  <label><strong><?php _e('Popup end date', 'popup-dropping-box'); ?></strong></label>
      <input name="pop_end" type="text" id="pop_end" value="9999-12-31" maxlength="10" />
      <p><?php _e('Please enter end date of this popup window, format YYYY-MM-DD.', 'popup-dropping-box'); ?></p>
	  	  	  
      <input name="pop_id" id="pop_id" type="hidden" value="">
      <input type="hidden" name="pop_form_submit" value="yes"/>
      <p class="submit">
        <input name="submit" class="button button-primary" value="<?php _e('Submit', 'popup-dropping-box'); ?>" type="submit" />
        <input name="cancel" class="button button-primary" onclick="_popupdbox_redirect()" value="<?php _e('Cancel', 'popup-dropping-box'); ?>" type="button" />
        <input name="help" class="button button-primary" onclick="_popupdbox_help()" value="<?php _e('Help', 'popup-dropping-box'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('pop_form_add'); ?>
    </form>
</div>
</div>