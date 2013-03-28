<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post" data-ajax="false">
    <?php echo $this->_('limitAvailDisplay') . $this->_('colon'); ?> <input type="range" min="0" max="5" value="<?php echo $limitAvailDisplay; ?>" name="limitAvailDisplay"/>
    <br/>
    <label for="hideOnlineAvail"><?php echo $this->_('hideOnlineAvail'); ?></label>
    <select name="hideOnlineAvail" id="hideOnlineAvail" data-role="slider">
	<option value="1"<?php if ($hideOnlineAvail) echo ' selected="selected"'; ?>><?php echo $this->_('option_off'); ?></option>
	<option value="0"<?php if (!$hideOnlineAvail) echo ' selected="selected"'; ?>><?php echo $this->_('option_on'); ?></option>
    </select>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/>
</form>
