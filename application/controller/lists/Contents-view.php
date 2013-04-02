<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div><br/>
<?php endif; ?>

<?php if ($mode == $this->MODE_VIEW): ?>
<select name="select-avail" id="select-avail" data-min="true" class="allow-wrap" onChange="window.location = '<?php echo $currentUrl; ?>?a=' + $('#select-avail').val();">
   <option value="no"><?php echo $this->_('check_list_avail'); ?></option>
    <?php if ($showAvailabilityForPos): ?>
   <option value="current" selected="selected"><?php echo $showAvailabilityForPos->__toString(); ?></option>
   <?php endif; ?>
   <option value="online"<?php if ($showAvailabilityFor == 'online') echo ' selected="selected"'; ?>>SAQ.com</option>
   <optGroup label="<?php echo $this->_('favorites'); ?>" id="optgroupFavorites">
   <?php foreach ($favoritePos as $favPos): ?>
       <option value="<?php echo $favPos->getId(); ?>"><?php echo $favPos->__toString(); ?></option>
   <?php endforeach; ?>    
   </optGroup>
   <optGroup label="<?php echo $this->_('closest'); ?>" id="optgroupClosest">
   </optGroup>
</select>
    <?php if ($showAvailabilityFor && $showAvailabilityFor != 'online'): ?>
        <?php if (array_key_exists($showAvailabilityFor, $favoritePos)): ?>
            <a href="<?php echo str_replace('XXXX', $showAvailabilityFor, $favoritesRemoveUrl); ?>" data-role="button" data-mini="true" rel="external">
                <?php echo $this->_('remove_from_favorites'); ?></a><br/>
        <?php else: ?>
            <a href="<?php echo str_replace('XXXX', $showAvailabilityFor, $favoritesAddUrl); ?>" data-role="button" data-mini="true" rel="external">
                <?php echo $this->_('add_to_favorites'); ?></a><br/>
        <?php endif; ?>
    <?php else: ?>
        <br/>
    <?php endif; ?>
<?php endif; ?>

<?php include $this->getPartialFile('wines_listview'); ?>

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
    <a href="<?php echo $deleteListUrl; ?>" data-role="button" data-theme="e" data-mini="true" data-inline="true">
       <?php echo $this->_('delete'); ?></a>
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a> 
</div>

<script type="text/javascript">
$(document).on('pageinit', function () {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(geolocSuccess, geolocError);
    }

    function geolocSuccess(position)
    {
        $('#optgroupClosest').load('<?php echo $getClosestPosUrl; ?>?lat=' + position.coords.latitude + '&long=' + position.coords.longitude);
    }

    function geolocError(msg) {}

    //geoloc testing data (pos around SAQ 23214)
    geolocSuccess({coords: {latitude:"45.388563",longitude:"-73.568994"}});
});
</script>
