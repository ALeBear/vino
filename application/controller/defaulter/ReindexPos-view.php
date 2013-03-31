<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post" data-ajax="false">
Product code: <input type="text" name="c" value="<?php echo $code; ?>"/>
<input type="submit" value="Go"/>
</form>
