<div style="float: left; position: relative;">
    <img src="<?php echo $wine->getImage(); ?>"/>
</div>
<div style="float: left; position: relative;">
    <a href="<?php echo $this->router->buildRoute('lists/addwine', array('c' => $wine->getCode()))->getUrl(); ?>" data-role="button" data-inline="true">
        <?php echo $this->_('add_to_list'); ?></a>
    <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode()))->getUrl(); ?>" data-role="button" data-inline="true">
        <?php echo $this->_('availability'); ?></a>
    <p></p>
    <?php echo $this->_('price', $wine->getPrix(), $wine->getFormat()); ?><br/>
    <?php echo $this->_('nature', $wine->getNature(), $wine->getCouleur(), $wine->getPourcentage()); ?><br/>
    <?php echo $this->_('sold_by', $wine->getFournisseur()); ?><br/>
</div>
