<ul data-role="listview" data-filter="true" data-inset="true" data-split-icon="<?php echo !isset($mode) || $mode == $this->MODE_VIEW ? 'grid' : 'delete'; ?>">
    <?php foreach ($wines as $wine): ?>
    <?php $price = $wine->hasPrixReduit() ? sprintf('<span style="color:red">%s</span> <strike>%s</strike>', $wine->getPrix(), $wine->getPrix(true)) : $wine->getPrix(); ?>
    <li>
        <a class="allow-wrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $wine->getCode(), 'f' => $from))->getUrl(); ?>">
            <img src="/images/<?php echo $wine->getVignette(); ?>.png" class="ui-li-icon"/>
            <?php echo $wine->__toString(); ?> <span class="listDetails"> - $<?php echo $price; ?></span></a>
        <?php if (!isset($mode) || $mode == $this->MODE_VIEW): ?>
        <a href="#" onclick="window.location = '<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode(), 'f' => $from))->getUrl(); ?>';">
            <?php echo $this->_('availability'); ?></a>
        <?php else: ?>
        <a href="<?php echo $this->router->buildRoute('lists/contents', array('id' => $list->getId(), 'c' => $wine->getCode(), 'm' => $mode))->getUrl(); ?>">
            <?php echo $this->_('remove'); ?></a>
        <?php endif; ?>
        <?php if (isset($availabilities[$wine->getCode()])): ?>
        <span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $availabilities[$wine->getCode()]; ?></span>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
</ul>
