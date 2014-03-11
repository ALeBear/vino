<?php if ($error): ?>
    <div style="color:red;"><?php echo $this->_($error); ?></div><br/>
<?php endif; ?>
<?php if (!is_null($importedCount)): ?>
    <div style="color:green;"><?php echo $importedCount; ?></div><br/>
<?php endif; ?>

<form method="post" data-ajax="false" enctype="multipart/form-data">
    <input type="hidden" name="process" value="1"/>
    <label><?php echo $this->_('csv_file'); ?> <input type="file" name="csvfile"/></label>
    <label><?php echo $this->_('date_arrival'); ?> <input type="date" name="date"/></label>
    <label><input type="checkbox" name="overwrite" class="custom" /> <?php echo $this->_('overwrite_existing'); ?></label>
    <input type="submit" value='<?php echo $this->_('submit'); ?>'/>
</form>
