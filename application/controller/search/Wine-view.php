<table border="0">
<tr>
    <td valign="top">
        <img src="<?php echo $wine->getImage(); ?>"/>
    </td>
    <td valign="top">
        <a href="<?php echo $this->router->buildRoute('search/availability', array('c' => $wine->getCode(), 'f' => $from))->getUrl(); ?>" data-role="button" data-inline="true" data-mini="true" style="float: right" rel="external">
            <?php echo $this->_('availability'); ?></a>
        <br/>
        <?php if ($wine->hasPrixReduit()): ?>
        <?php echo $this->_('price_reduced', $wine->getPrix(), $wine->getPrix(true), $wine->getFormat()); ?><br/>
        <?php else: ?>
        <?php echo $this->_('price', $wine->getPrix(), $wine->getFormat()); ?><br/>
        <?php endif; ?>
        <?php echo $this->_('nature', $wine->getCategorie(), $wine->getPourcentage()); ?>&#176;<br/>
        <?php echo $wine->getRegion(); ?><br/>
        <?php if ($wine->getCepage()): ?>
        <?php echo $wine->getCepage(); ?><br/>
        <?php endif; ?>
        <?php echo $this->_('sold_by', $wine->getFournisseur()); ?><br/>
    </td>
</tr>
</table>

<?php if (count($lists)): ?>
<hr/>
<fieldset data-role="controlgroup" data-type="horizontal">
<select name="select-list" id="select-list" data-mini="true" data-inline="true" class="allow-wrap">
<?php foreach ($lists as $list): ?>
    <option value="<?php echo $list->getId(); ?>"><?php echo $list->__toString() . ($list->contains($wine->getCode()) ? ' *' : ''); ?></option>
<?php endforeach; ?>
</select>
<a href="#" id="add-to-list" baseurl="<?php echo $this->router->buildRoute('lists/addwine', array('c' => $wine->getCode(), 'f' => $from, 'l' => ''))->getUrl(); ?>" data-role="button" data-inline="true" data-mini="true" rel="external">
<?php echo $this->_('add_to_list'); ?></a>
</fieldset>
<?php endif; ?>

<hr/>
<?php if ($averageAppreciation): ?>
<div>
<?php echo sprintf('%s%s %s', $this->_('appreciation'), $this->_('colon'), $averageAppreciation); ?>
<?php if ($myAppreciation) echo sprintf(' (%s%s %s)', $this->_('me'), $this->_('colon'), $myAppreciation); ?>
</div>
<?php endif; ?>
<h3>
    <?php echo $this->_('notes');?>
    <a href="#popup-edit" data-rel="popup" data-role="button" data-inline="true" data-mini="true">
        <?php echo $this->_('edit'); ?></a>
</h3>

<?php foreach ($notes as $note): ?>
<div>
<?php echo sprintf('<strong>%s</strong>, %s %s <i>%s %s</i>', $note->getUser()->__toString(), $this->_('for_vintage'), $note->getVintage(), $this->_('on_date'), $note->getDate()); ?>
<?php if ($note->getAppreciation()) echo sprintf(' (%s %s)', $note->getAppreciation(), $this->_('points')); ?>
<?php echo sprintf('%s<br/>%s<hr/>', $this->_('colon'), nl2br($note->getText())); ?>
</div>
<?php endforeach; ?>

<div data-role="popup" id="popup-edit" class="ui-content" style="text-align: center; width: 300px;">
    <form method="post" data-ajax="false" action="<?php echo $editFormUrl; ?>">
        <label for="appreciation"><?php echo $this->_('appreciation'); ?></label>
        <input type="range" name="appreciation" id="appreciation" value="<?php echo $myNote->getAppreciation();?>" min="0" max="100"/>
        <label for="note"><?php echo $this->_('note'); ?></label>
        <textarea name="note" id="note"><?php echo $myNote->getText();?></textarea>
        <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
    </form>
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a> 
</div>
