<form method="post" action="?c=<?php echo $code; ?>">
    <label for="appreciation"><?php echo $this->_('appreciation'); ?></label>
    <input type="range" name="appreciation" id="appreciation" value="<?php echo $wine->getAppreciation();?>" min="0" max="100"/>
    <label for="note"><?php echo $this->_('note'); ?></label>
    <textarea name="note" id="note"><?php echo $wine->getNote();?></textarea>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
</form>
