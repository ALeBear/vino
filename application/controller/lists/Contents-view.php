<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div><br/>
<?php endif; ?>
<ul data-role="listview" data-filter="true" data-inset="true" data-split-icon="<?php echo $mode == 'view' ? 'grid' : 'delete'; ?>">
    <?php foreach ($wines as $wine): ?>
    <li>
        <a class="allow-wrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $wine->getCode(), 'f' => 'l-' . $listId))->getUrl(); ?>">
            <img src="/images/<?php echo $wine->getVignette(); ?>.png" class="ui-li-icon"/>
            <?php echo $wine->__toString(); ?> <span class="listDetails"> - $<?php echo $wine->getPrix(); ?></span></a>
        <?php if ($mode == 'view'): ?>
        <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode(), 'f' => 'l-' . $listId))->getUrl(); ?>" rel="external">
            <?php echo $this->_('availability'); ?></a>
        <?php else: ?>
        <a href="<?php echo $this->router->buildRoute('lists/contents', array('id' => $listId, 'c' => $wine->getCode(), 'm' => $mode))->getUrl(); ?>">
            <?php echo $this->_('remove'); ?></a>
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
