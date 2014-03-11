<select name="select-date" id="select-date" data-min="true" class="allow-wrap" onChange="window.location = '?dt=' + $('#select-date').val();">
<?php foreach ($allDates as $aDate): ?>
    <?php $selected = $aDate == $currentDate ? ' selected="selected"' : ''; ?>
    <option value="<?php echo $aDate->format('Y-m-d'); ?>"<?php echo $selected; ?>><?php echo $aDate->format('Y-m-d'); ?></option>
<?php endforeach; ?>
</select>

<table data-role="table" data-mode="columntoggle" id="arrivals">
    <thead>
    <tr>
        <th><?php echo $this->_('Type'); ?></th>
        <th><?php echo $this->_('Country'); ?></th>
        <th><?php echo $this->_('Name'); ?></th>
        <th data-priority="3"><?php echo $this->_('Producer'); ?></th>
        <th data-priority="3"><?php echo $this->_('SAQ Code'); ?></th>
        <th data-priority="5"><?php echo $this->_('Importer'); ?></th>
        <th data-priority="6"><?php echo $this->_('Region'); ?></th>
        <th><?php echo $this->_('Price'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($arrivals as $arrival): ?>
    <?php /** @var \vino\saq\Arrival $arrival */ ?>
    <tr>
        <td align="center">
            <a href="#popup-details-<?php echo $arrival->getSaqCode(); ?>" data-rel="popup" data-role="button" data-inline="true" data-mini="true">
                <img src="/images/<?php echo $arrival->getVignette(); ?>.png" width="20px"/></a>
        </td>
        <td><?php echo $arrival->getCountry(); ?></td>
        <td><?php echo $arrival->getName(); ?> (<?php echo $arrival->getVintage(); ?>)</td>
        <td><?php echo $arrival->getProducer(); ?></td>
        <td><?php echo $arrival->getSaqCode(); ?></td>
        <td><?php echo $arrival->getImporter(); ?></td>
        <td><?php echo $arrival->getRegion(); ?></td>
        <td>$<?php echo $arrival->getPrice(); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<?php foreach ($arrivals as $arrival): ?>
<div data-role="popup" id="popup-details-<?php echo $arrival->getSaqCode(); ?>" class="ui-content" style="text-align: center; width: 200px;">
    <?php echo sprintf('%s ml %s', $arrival->getMilliliters(), str_replace('_', ' ', (strpos($arrival->getVignette(), '-') ? substr($arrival->getVignette(), 0, strpos($arrival->getVignette(), '-')) : $arrival->getVignette()))); ?>
    <a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_('cancel'); ?></a>
</div>
<?php endforeach; ?>
