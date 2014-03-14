<form method="get" id="frm" action="<?php echo $formUrl; ?>">
    <input type="hidden" name="orderBy" id="orderBy" value="<?php echo $currentOrderBy; ?>"/>
    <table>
        <tr>
        <td><input type="text" placeholder="<?php echo $this->_('search_name_producer'); ?>" name="search" data-min="true" value="<?php echo $currentSearch; ?>" style="width: 300px"/></td>
        <td>
            <div data-role="controlgroup" data-type="horizontal">
            <select name="dt" class="allow-wrap">
                <?php $selected = !$currentDate ? ' selected="selected"' : ''; ?>
                <option value=""<?php echo $selected; ?>><?php echo $this->_('arrival_date'); ?></option>
                <?php foreach ($allDates as $aDate): ?>
                    <?php $selected = $aDate == $currentDate ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $aDate->format('Y-m-d'); ?>"<?php echo $selected; ?>><?php echo $aDate->format('Y-m-d'); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="country" data-min="true" class="allow-wrap">
                <?php $selected = !$currentCountry ? ' selected="selected"' : ''; ?>
                <option value=""<?php echo $selected; ?>><?php echo $this->_('country'); ?></option>
                <?php foreach ($allCountries as $aCountry): ?>
                    <?php $selected = $aCountry == $currentCountry ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $aCountry; ?>"<?php echo $selected; ?>><?php echo $aCountry; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="color" data-min="true" class="allow-wrap">
                <?php $selected = !$currentColor ? ' selected="selected"' : ''; ?>
                <option value=""<?php echo $selected; ?>><?php echo $this->_('color'); ?></option>
                <?php foreach ($allColors as $aColor): ?>
                    <?php $selected = $aColor == $currentColor ? ' selected="selected"' : ''; ?>
                    <option value="<?php echo $aColor; ?>"<?php echo $selected; ?>><?php echo $aColor; ?></option>
                <?php endforeach; ?>
            </select>
            </div>
        </td>
        <td><button data-min="true"><?php echo $this->_('search'); ?></button></td>
        <?php if ($hasUser): ?>
            <td>|</button></td>
            <td><a data-role="button" data-theme="e" href="<?php echo $watchlistUrl; ?>"><?php echo $this->_('view_watchlist'); ?></a></td>
        <?php endif; ?>
        </tr>
    </table>
</form>

<?php
    if (!is_array($arrivals)) {
        echo sprintf('<div style="padding: 10px;">%s</div>', $this->_('enter_criterias'));
    } elseif(!count($arrivals)) {
        echo sprintf('<div style="padding: 10px;">%s</div>', $this->_('no_results'));
    } else { ?>
<?php if ($hasUser): ?>
<form method="post" id="frmAddToWatchlist" action="<?php echo $formUrl; ?>?action=atw" data-ajax="false">
<div data-role="controlgroup" data-type="horizontal">
    <button style="ui-btn-inline"><?php echo $this->_('add_remove_to_watchlist'); ?></button>
</div>
<input type="hidden" name="action" value="atw"/>
<?php endif; ?>
<table data-role="table" data-mode="columntoggle" id="arrivals" class="table-stripe" data-column-btn-text="<?php echo $this->_('columns'); ?>">
    <thead>
    <tr>
        <?php if ($hasUser): ?>
        <th>&nbsp;</th>
        <?php endif; ?>
        <th><?php echo $this->_('type'); ?></th>
        <th><?php displayTH('country', $this->_('country'), $currentOrderBy); ?></th>
        <th><?php displayTH('name', $this->_('name'), $currentOrderBy); ?></th>
        <th><?php displayTH('producer', $this->_('producer'), $currentOrderBy); ?></th>
        <th data-priority="5"><?php echo $this->_('importer'); ?></th>
        <th><?php displayTH('price', $this->_('price'), $currentOrderBy); ?></th>
        <th><?php displayTH('arrivalDate', $this->_('arrival_date'), $currentOrderBy); ?></th>
        <th><?php echo $this->_('saq_code'); ?></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($arrivals as $arrival): ?>
        <?php /** @var \vino\saq\Arrival $arrival */ ?>
        <tr style="border-top: 1px solid black;<?php if ($hasUser && in_array($arrival->getId(), $watchlistIds)) echo ' background-color: lightblue;'; ?>">
            <?php if ($hasUser): ?>
            <td>
                <input type="checkbox" name="watchlist-add-<?php echo $arrival->getId(); ?>" style="width: 15px; height: 15px; left: 0px; top: 10px;"/>
            </td>
            <?php endif; ?>
            <td align="center">
                <a href="#popup-details-<?php echo $arrival->getSaqCode(); ?>" data-rel="popup">
                    <img src="/images/<?php echo $arrival->getVignette(); ?>.png" width="20px"/></a>
            </td>
            <td><?php echo $arrival->getCountry(); ?></td>
            <td><?php echo $arrival->getName(); ?> (<?php echo $arrival->getVintage() ? $arrival->getVintage() : 'NM'; ?>)</td>
            <td><?php echo $arrival->getProducer(); ?></td>
            <td><?php echo $arrival->getImporter(); ?></td>
            <td>$<?php echo $arrival->getPrice(); ?></td>
            <td><?php echo $arrival->getArrivalDate()->format('Y-m-d'); ?></td>
            <td>
                <?php if ($hasUser): ?>
                    <a href="<?php echo str_replace('SAQCODE', $arrival->getSaqCode(), $detailsUrl); ?>"><?php echo $arrival->getSaqCode(); ?></a>
                <?php else: ?>
                    <?php echo $arrival->getSaqCode(); ?>
                <?php endif; ?>
                <a href="http://www.saq.com/webapp/wcs/stores/servlet/ProductDisplay?storeId=20002&productId=<?php echo $arrival->getSaqCode(); ?>" target="_blank"><img src="/images/saq.png" border="0" width="20"/></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php if ($hasUser): ?>
</form>
<?php endif; ?>
<?php } ?>


<?php if (is_array($arrivals)): ?>
    <?php foreach ($arrivals as $arrival): ?>
    <div data-role="popup" id="popup-details-<?php echo $arrival->getSaqCode(); ?>" class="ui-content" style="text-align: center; width: 200px;">
        <?php echo sprintf('%s ml %s', $arrival->getMilliliters(), str_replace('_', ' ', (strpos($arrival->getVignette(), '-') ? substr($arrival->getVignette(), 0, strpos($arrival->getVignette(), '-')) : $arrival->getVignette()))); ?>
        <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
function displayTH($name, $translation, $currentOrderBy) {
    $arrow = $astyle = '';
    $orderByToSet = sprintf('%s-ASC', $name);
    if ($currentOrderBy == $name . '-ASC') {
        $orderByToSet = sprintf('%s-DESC', $name);
        $arrow = ' <strong>&darr;</strong>';
        $astyle = ' style="font-size: 1.4em;"';
    }
    if ($currentOrderBy == $name . '-DESC') {
        $arrow = ' <strong>&uarr;</strong>';
        $astyle = ' style="font-size: 1.4em;"';
    }

    echo sprintf('<a href="#" onclick="$(\'#orderBy\').val(\'%s\'); $(\'#frm\').submit(); return true;"%s>%s</a>%s', $orderByToSet, $astyle, $translation, $arrow);
}
?>