<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div><br/>
<?php endif; ?>
<ul data-role="listview" data-filter="true" data-inset="true" data-split-icon="<?php echo $mode == 'view' ? 'grid' : 'delete'; ?>">
    <?php foreach ($wines as $wine): ?>
    <li>
        <a class="nowrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $wine->getCode(), 'f' => 'l-' . $listId))->getUrl(); ?>">
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
