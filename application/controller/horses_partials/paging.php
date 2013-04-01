<?php if ($pages > 1): ?>
<div data-role="fieldcontain">
    <div style="display: inline-block;"><?php echo $this->_('pages') . $this->_('colon'); ?>&nbsp;</div>
    <div data-role="controlgroup" data-type="horizontal" style="display: inline-block; width:80%;">
        <?php for ($i = 0; $i < $pages; $i++): ?>
        <a href="<?php echo str_replace('xxXXxx', $i, $pagingUrlTemplate); ?>" data-role="button"<?php if ($i == $currentPage) echo ' onclick="return false;" class="ui-btn-active"'; ?> rel="external">
            <?php echo $i + 1; ?></a>
        <?php endfor; ?>
    </div>
</div>
<?php endif; ?>
