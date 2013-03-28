<form method="get">
<input type="text" name="q" value="<?php echo $query; ?>"/>
<input type="submit" value='<?php echo $this->_('proceed'); ?>'/>
</form>
<br/>
<?php if (count($products)): ?>
    <ul data-role="listview" data-inset="true" data-split-icon="grid">
    <?php foreach ($products as $product): ?>
        <li>
            <a class="allow-wrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $product->getCode(), 'f' => $from))->getUrl(); ?>" rel="external">
            <img src="/images/<?php echo $product->getVignette(); ?>.png" class="ui-li-icon"/>
            <?php echo $product->__toString(); ?> <span class="listDetails"> - $<?php echo $product->getPrix(); ?></span></a>
            <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $product->getCode(), 'f' => $from))->getUrl(); ?>" rel="external">
            <?php echo $this->_('availability'); ?></a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
