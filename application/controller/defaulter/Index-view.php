<h3><?php echo $this->_('search'); ?></a></h3>
<form method="get" action="<?php echo $searchUrl; ?>">
<input type="text" name="q" value=""/>
<input type="submit" value='<?php echo $this->_('proceed'); ?>'/>
</form>

<h3><?php echo $this->_('lists'); ?></a></h3>

<?php if (count($lists)): ?>
    <ul data-role="listview" data-inset="true">
    <?php foreach ($lists as $list): ?>
        <li>
            <a href="<?php echo str_replace('XXXX', $list->getId(), $listUrl); ?>">
            <?php echo $list->__toString(); ?>
            <span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $list->count(); ?></span>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<h3><?php echo $this->_('newlist'); ?></h3>
<?php if ($error): ?>
<div style="color:red;"><?php echo $this->_($error); ?></div>
<?php endif; ?>
<form method="post">
<input type="text" name="listname"/>
<input type="submit" value='<?php echo $this->_('create'); ?>'/>
</form>

<br/>
<a href="<?php echo $logoutUrl; ?>" data-role="button" data-icon="delete" data-theme="e">
    <?php echo $this->_('logout'); ?></a>
<a href="<?php echo $editAccountUrl; ?>" data-role="button">
    <?php echo $this->_('edit_account'); ?></a>
