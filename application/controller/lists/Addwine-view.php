<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<h4><?php echo $name; ?></h4>
<form method="post" action="?c=<?php echo $code; ?>&n=<?php echo $name; ?>">
    <select name="list">
        <?php foreach ($lists as $list): ?>
        <option value="<?php echo $list->getId(); ?>"><?php echo $list->__toString(); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
</form>
