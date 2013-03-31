<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div><br/>
<?php endif; ?>

<?php if ($mode == constant(get_class($this) . '::MODE_VIEW')): ?>
<select name="select-avail" id="select-avail" data-min="true" class="allow-wrap" onChange="window.location = '<?php echo $currentUrl; ?>?a=' + $('#select-avail').val();">
   <option value="no"><?php echo $this->_('check_list_avail'); ?></option>
   <option value="online"<?php if ($showAvailabilityFor == 'online') echo ' selected="selected"'; ?>>SAQ.com</option>
   <optGroup label="<?php echo $this->_('favorites'); ?>" id="optgroupFavorites">
   <?php foreach ($favoritePos as $favoriteId): ?>
       <option value="<?php echo $favoriteId; ?>"></option>
   <?php endforeach; ?>    
   </optGroup>
   <optGroup label="<?php echo $this->_('closest'); ?>" id="optgroupClosest">
   </optGroup>
</select>
    <?php if($showAvailabilityFor && $showAvailabilityFor != 'online'): ?>
        <?php if (in_array($showAvailabilityFor, $favoritePos)): ?>
            <a href="<?php echo $favoritesUrl; ?>?rem=<?php echo $showAvailabilityFor; ?>" data-role="button" data-mini="true" rel="external">
                <?php echo $this->_('remove_from_favorites'); ?></a><br/>
        <?php else: ?>
            <a href="<?php echo $favoritesUrl; ?>?add=<?php echo $showAvailabilityFor; ?>" data-role="button" data-mini="true" rel="external">
                <?php echo $this->_('add_to_favorites'); ?></a><br/>
        <?php endif; ?>
    <?php else: ?>
        <br/>
    <?php endif; ?>
<?php endif; ?>

<ul data-role="listview" data-filter="true" data-inset="true" data-split-icon="<?php echo $mode == constant(get_class($this) . '::MODE_VIEW') ? 'grid' : 'delete'; ?>">
    <?php foreach ($wines as $wine): ?>
    <?php $price = $wine->hasPrixReduit() ? sprintf('<span style="color:red">%s</span> <strike>%s</strike>', $wine->getPrix(), $wine->getPrix(true)) : $wine->getPrix(); ?>
    <li>
        <a class="allow-wrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $wine->getCode(), 'f' => 'l-' . $listId))->getUrl(); ?>">
            <img src="/images/<?php echo $wine->getVignette(); ?>.png" class="ui-li-icon"/>
            <?php echo $wine->__toString(); ?> <span class="listDetails"> - $<?php echo $price; ?></span></a>
        <?php if ($mode == constant(get_class($this) . '::MODE_VIEW')): ?>
        <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode(), 'f' => 'l-' . $listId))->getUrl(); ?>" rel="external">
            <?php echo $this->_('availability'); ?></a>
        <?php else: ?>
        <a href="<?php echo $this->router->buildRoute('lists/contents', array('id' => $listId, 'c' => $wine->getCode(), 'm' => $mode))->getUrl(); ?>">
            <?php echo $this->_('remove'); ?></a>
        <?php endif; ?>
        <?php if (isset($availabilities[$wine->getCode()])): ?>
        <span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $availabilities[$wine->getCode()]; ?></span>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>

<a href="#popup-edit" data-rel="popup" data-role="button" data-mini="true" data-inline="true" style="float:left;"><?php echo $this->_('rename'); ?></a>
<a href="#popup-delete" data-rel="popup" data-role="button" data-theme="e" data-mini="true" data-inline="true" style="float:right;"><?php echo $this->_('delete'); ?></a>
<div style="clear:both;"></div>

<div data-role="popup" id="popup-edit" class="ui-content" style="text-align: center;">
    <form method="get" data-ajax="false">
    <input type="text" name="listname" value="<?php echo $list->__toString(); ?>"/>
    <input type="submit" value='<?php echo $this->_('rename'); ?>'/>
    </form>
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a> 
</div>
<div data-role="popup" id="popup-delete" class="ui-content" style="text-align: center;">
    <?php echo $this->_('confirm_delete_list', $list->__toString()); ?><br/><br/>
    <a href="<?php echo $this->router->buildRoute('lists/contents', array('id' => $listId, 'd' => 1, 'm' => $mode))->getUrl(); ?>" data-role="button" data-theme="e" data-mini="true" data-inline="true">
       <?php echo $this->_('delete'); ?></a>
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a> 
</div>

<script type="text/javascript">
    $('#select-avail option').each(function(index, option) {
        if (!option.text) {
            option.text = allPos[option.value].name;
        }
    })
</script>


<?php if ($showAvailabilityFor && $showAvailabilityFor != 'online'): ?>
<script type="text/javascript">
    $(document).on('pageinit', function () {
        //Add the proper POS on top of the list and make it selected
        createCurrentlySelected('<?php echo $showAvailabilityFor; ?>');
    });

</script>
<?php endif; ?>