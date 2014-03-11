<select name="select-date" id="select-date" data-min="true" class="allow-wrap" onChange="window.location = '<?php echo $currentUrl; ?>?a=' + $('#select-date').val();">
<?php foreach ($allDates as $aDate): ?>
    <option value="<?php echo $aDate->format('Y-m-d'); ?>"><?php echo $aDate->format('Y-m-d'); ?></option>
<?php endforeach; ?>
</select>

<table data-role="table" data-mode="columntoggle" id="arrivals">
    <thead>
    <tr>
        <th>Name</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($arrivals as $arrival): ?>
    <?php /** @var \vino\saq\Arrival $arrival */ ?>
    <tr>
        <td><?php echo $arrival->getName(); ?></td>
        <td>$<?php echo $arrival->getPrice(); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>