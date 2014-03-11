<form method="get" data-ajax="false" action="<?php echo $searchUrl; ?>">
<input type="text" name="q" value="<?php echo $query; ?>" autocapitalize="off"/>
<input type="submit" value='<?php echo $this->_('proceed'); ?>'/>
</form>
<br/>
<?php if (count($wines)): ?>
    <?php include $this->getPartialFile('paging'); ?>

    <?php include $this->getPartialFile('wines_listview'); ?>

    <?php include $this->getPartialFile('paging'); ?>
<?php elseif ($noResults): ?>
<?php echo $this->_('no_results'); ?>

<?php endif; ?>
