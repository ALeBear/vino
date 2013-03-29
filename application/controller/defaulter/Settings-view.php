<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post" data-ajax="false">
    <?php echo $this->_('limitAvailDisplay') . $this->_('colon'); ?> <input type="range" min="0" max="5" value="<?php echo $limitAvailDisplay; ?>" name="limitAvailDisplay"/>
    <br/>
    <label for="hideOnlineAvail"><?php echo $this->_('hideOnlineAvail') . $this->_('colon'); ?></label>
    <select name="hideOnlineAvail" id="hideOnlineAvail" data-role="slider">
	<option value="1"<?php if ($hideOnlineAvail) echo ' selected="selected"'; ?>><?php echo $this->_('option_is_off'); ?></option>
	<option value="0"<?php if (!$hideOnlineAvail) echo ' selected="selected"'; ?>><?php echo $this->_('option_is_on'); ?></option>
    </select><br/>
    <?php echo $this->_('language') . $this->_('colon'); ?><br/>
    <div data-role="controlgroup" data-type="horizontal">
        <?php foreach ($allLocales as $locale => $urlPrefix): ?>
        <a href="<?php echo sprintf('/%s/%s', $urlPrefix, substr($localeUrl, 4)); ?>" data-role="button"<?php if ($locale == $currentLocale) echo ' onclick="return false;" class="ui-btn-active"'; ?> rel="external">
            <?php echo $this->_($locale); ?></a>
        <?php endforeach; ?>
    </div>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/>
</form>
