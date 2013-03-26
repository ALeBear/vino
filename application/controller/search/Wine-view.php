<table border="0">
<tr>
    <td valign="top">
        <img src="<?php echo $wine->getImage(); ?>"/>
    </td>
    <td valign="top">
        <a href="<?php echo $this->router->buildRoute('lists/addwine', array('c' => $wine->getCode()))->getUrl(); ?>" data-role="button" data-inline="true">
            <?php echo $this->_('add_to_list'); ?></a>
        <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode()))->getUrl(); ?>" data-role="button" data-inline="true">
            <?php echo $this->_('availability'); ?></a>
        <p></p>
        <?php echo $this->_('price', $wine->getPrix(), $wine->getFormat()); ?><br/>
        <?php echo $this->_('nature', $wine->getCategorie(), $wine->getPourcentage()); ?>&#176;<br/>
        <?php echo $wine->getRegion(); ?><br/>
        <?php if ($wine->getCepage()): ?>
        <?php echo $wine->getCepage(); ?><br/>
        <?php endif; ?>
        <?php echo $this->_('sold_by', $wine->getFournisseur()); ?><br/>
    </td>
</tr>
</table>
<hr/>
<?php if ($averageAppreciation): ?>
<div>
<?php echo sprintf('%s%s %s', $this->_('appreciation'), $this->_('colon'), $averageAppreciation); ?>
<?php if ($myAppreciation) echo sprintf(' (%s%s %s)', $this->_('me'), $this->_('colon'), $myAppreciation); ?>
</div>
<?php endif; ?>
<h3>
    <?php echo $this->_('notes');?>
    <a href="<?php echo $this->router->buildRoute('search/WineEditNote', array('c' => $wine->getCode()))->getUrl(); ?>" data-rel="dialog" data-role="button" data-inline="true">
    <?php echo $this->_('edit'); ?></a>
</h3>
<?php foreach ($notes as $note): ?>
<div>
<?php echo sprintf('<strong>%s</strong>, %s %s', $note->getUser()->__toString(), $this->_('for_vintage'), $note->getVintage()); ?>
<?php if ($note->getAppreciation()) echo sprintf(' (%s %s)', $note->getAppreciation(), $this->_('points')); ?>
<?php echo sprintf('%s<br/>%s<hr/>', $this->_('colon'), nl2br($note->getText())); ?>
</div>
<?php endforeach; ?>
