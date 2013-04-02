<?php foreach ($closest as $pos): ?>
<option value="<?php echo $pos['id']; ?>"><?php echo sprintf('%02.2fKm - %s', $pos['dist'], $pos['name']); ?></option>
<?php endforeach; ?>
