<div style="float: left; position: relative;">
    <img src="<?php echo $wine->getSaqWine()->getImage(); ?>"/>
</div>
<div style="float: left; position: relative;">
    <a href="<?php echo $this->router->buildRoute('lists/addwine', array('c' => $wine->getCode()))->getUrl(); ?>" data-role="button" data-inline="true">
        <?php echo $this->_('add_to_list'); ?></a>
    <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode()))->getUrl(); ?>" data-role="button" data-inline="true">
        <?php echo $this->_('availability'); ?></a>
    <p></p>
    <?php echo $this->_('price', $wine->getSaqWine()->getPrix(), $wine->getSaqWine()->getFormat()); ?><br/>
    <?php echo $this->_('nature', $wine->getSaqWine()->getNature(), $wine->getSaqWine()->getCouleur(), $wine->getSaqWine()->getPourcentage()); ?><br/>
    <?php echo $wine->getSaqWine()->getRegion(); ?><br/>
    <?php if ($wine->getSaqWine()->getCepage()): ?>
    <?php echo $wine->getSaqWine()->getCepage(); ?><br/>
    <?php endif; ?>
    <?php echo $this->_('sold_by', $wine->getSaqWine()->getFournisseur()); ?><br/>
    <hr/>
    <h3>
        <?php echo $this->_('personal_infos');?>
        <a href="<?php echo $this->router->buildRoute('search/WineEdit', array('c' => $wine->getCode()))->getUrl(); ?>" data-rel="dialog" data-role="button" data-inline="true">
        <?php echo $this->_('edit'); ?></a>
    </h3>
    <?php if ($wine->getAppreciation()): ?>
    <div>
    <?php echo sprintf('%s%s %s', $this->_('appreciation'), $this->_('colon'), $wine->getAppreciation()); ?>
    </div>
    <?php endif; ?>
    <?php if ($wine->getNote()): ?>
    <div>
    <?php echo sprintf('%s%s <br/>%s', $this->_('note'), $this->_('colon'), $wine->getNote()); ?>
    </div>
    <?php endif; ?>
</div>
