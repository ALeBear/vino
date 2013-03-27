<form method="post" data-ajax="false">
    <label for="appreciation"><?php echo $this->_('appreciation'); ?></label>
    <input type="range" name="appreciation" id="appreciation" value="<?php echo $note->getAppreciation();?>" min="0" max="100"/>
    <label for="note"><?php echo $this->_('note'); ?></label>
    <textarea name="note" id="note"><?php echo $note->getText();?></textarea>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
</form>
