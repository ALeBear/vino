<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<ul data-role="listview" data-filter="true" data-inset="true" data-split-icon="delete">
    <?php foreach ($list->getWines() as $wine): ?>
    <li>
        
        <a class="nowrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $wine->getCode()))->getUrl(); ?>">
            <img src="/images/<?php echo $wine->getVignette(); ?>.png" class="ui-li-icon"/>
            <?php echo $wine->__toString(); ?></a>
        <a href="<?php echo $this->router->buildRoute('lists/contents', array('id' => $list->getId(), 'c' => $wine->getCode()))->getUrl(); ?>">[<?php echo $this->_('remove'); ?>]</a>
    </li>
    <?php endforeach; ?>
</ul>
