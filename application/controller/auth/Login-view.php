<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post" data-ajax="false">
    <?php echo $this->_('email') . $this->_('colon'); ?> <input type="email" value="<?php echo $email; ?>" name="email" autocapitalize="off"/>
    <?php echo $this->_('password') . $this->_('colon'); ?> <input type="password" name="password"/>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
</form>
<a href="#popup-forgot" data-rel="popup" data-role="button" data-theme="e" data-mini="true"><?php echo $this->_('forgot_password'); ?></a>
<div data-role="popup" id="popup-forgot" class="ui-content" style="text-align: center;">
    <?php echo $this->_('email'); ?><br/><br/>
    <form method="get" data-ajax="false">
    <input type="text" name="forgot"/>
    <input type="submit" value='<?php echo $this->_('send'); ?>'/>
    </form>
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a> 
</div>
