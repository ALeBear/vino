<form method="get">
<input type="text" name="q" value="<?php echo $query; ?>"/><br/>
<input type="submit" value='<?php echo $this->_('proceed'); ?>'/>
</form>
<br/>
<?php if (count($products)): ?>
    <ul data-role="listview" data-filter="true" data-inset="true">
    <?php foreach ($products as $product): ?>
        <li>
            <a href="<?php echo $this->router->buildRoute('search/wine', array('c' => $product->getCode()))->getUrl(); ?>">
            <?php echo $product->__toString(); ?></a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
