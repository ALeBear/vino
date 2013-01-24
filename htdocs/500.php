<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>500 ways to leave your lover / 500 façons de quitter son amant(e)</title>
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
</head>
<body>
<h1>Oops</h1>
<h2><span style="font-size: 1.5em;">500</span> ways to leave your lover / <span style="font-size: 1.5em;">500</span> façons de quitter son amant(e)</h2>
<?php
    if (isset($_SERVER['ENV']) && $_SERVER['ENV'] != 'prod') {
        echo '<div style="border: 3px solid red; padding: 6px;">';
        echo sprintf('<div style="font-size: 1.5em;">%s</div>', get_class($e));
        echo sprintf('<strong>%s</strong><br />In %s line %s<br /><br />', $e->getMessage(), $e->getFile(), $e->getLine());
        foreach ($e->getTrace() as $line) {
            echo '<div style="border: 1px solid black; padding: 4px;">';
            if (isset($line['class'])) {
                echo sprintf('<strong>%s%s%s()</strong>', $line['class'], $line['type'], $line['function']);
            } else {
                echo '<strong>Inner method</strong>';
            }
            if (isset($line['file'])) {
                echo sprintf(' in %s line %s', $line['file'], $line['line']);
            }
            echo '</div>';
        }
        echo '</div>';
    }
?>
</body>
</html>