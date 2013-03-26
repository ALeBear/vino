<form method="get">
<input type="text" name="q" value="<?php echo $query; ?>"/>
<input type="submit" value='<?php echo $this->_('proceed'); ?>'/>
</form>
<br/>
<?php if (count($products)): ?>
    <ul data-role="listview" data-inset="true">
    <?php foreach ($products as $product): ?>
        <li>
            <a class="nowrap" href="<?php echo $this->router->buildRoute('search/wine', array('c' => $product->getCode()))->getUrl(); ?>">
            <img src="/images/<?php echo $product->getVignette(); ?>.png" class="ui-li-icon"/>
            <?php echo $product->__toString(); ?></a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
