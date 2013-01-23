<?php if (count($lists)): ?>
    <ul data-role="listview" data-filter="true" data-inset="true">
    <?php foreach ($lists as $list): ?>
        <li><a href="<?php echo $this->router->buildRoute('lists/contents', array('id' => $list->getId()))->getUrl(); ?>">
            <?php echo $list->__toString(); ?></a></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<h3><?php echo $this->_('newlist'); ?></h3>
<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post">
<input type="text" name="name"/><br/>
<input type="submit" value='<?php echo $this->_('create'); ?>'/>
</form>
