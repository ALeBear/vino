<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post">
    <?php echo $this->_('email') . $this->_('colon'); ?> <input type="text" value="<?php echo $email; ?>" name="email"/><br/>
    <?php echo $this->_('password') . $this->_('colon'); ?> <input type="text" value="<?php echo $password; ?>" name="password"/><br/>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
</form>
