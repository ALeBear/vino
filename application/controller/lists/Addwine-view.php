<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<h4><?php echo $wine->__toString(); ?></h4>
<form method="post" action="?c=<?php echo $code; ?>">
    <select name="list" id="list">
        <?php foreach ($lists as $list): ?>
        <option value="<?php echo $list->getId(); ?>"><?php echo $list->__toString(); ?></option>
        <?php endforeach; ?>
    </select>
    <label for="appreciation"><?php echo $this->_('appreciation'); ?></label>
    <input type="range" name="appreciation" id="appreciation" value="<?php echo $wine->getAppreciation();?>" min="0" max="100"/>
    <label for="note"><?php echo $this->_('note'); ?></label>
    <textarea name="note" id="note"><?php echo $wine->getNote();?></textarea>
    <input type="submit" value="<?php echo $this->_('proceed'); ?>"/><br/>
</form>
