<form method="get" action="<?php echo $formUrl; ?>">
<input type="text" name="q" value="<?php echo $query; ?>"/>
<input type="submit" value='<?php echo $this->_('proceed'); ?>'/>
</form>
<br/>
<?php if (count($wines)): ?>
    <?php include $this->getPartialFile('paging'); ?>

    <?php include $this->getPartialFile('wines_listview'); ?>

    <?php include $this->getPartialFile('paging'); ?>
<?php endif; ?>
