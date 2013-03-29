<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post" data-ajax="false">
    <?php echo $this->_('name') . $this->_('colon'); ?> <input type="text" value="<?php echo $name; ?>" name="name"/>
    <?php echo $this->_('email') . $this->_('colon'); ?> <input type="text" value="<?php echo $email; ?>" name="email"/>
    <?php echo $this->_('password') . $this->_('colon'); ?> <input type="password" name="password"/>
    <?php echo $this->_('password') . $this->_('colon'); ?> <input type="password" name="password2"/>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/>
</form>
