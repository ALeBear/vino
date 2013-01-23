<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>404 ways to leave your lover / 404 façons de quitter son amant(e)</title>
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
</head>
<body>
<h1>Oops</h1>
<h2><span style="font-size: 1.5em;">404</span> ways to leave your lover / <span style="font-size: 1.5em;">404</span> façons de quitter son amant(e)</h2>
<?php
    if (isset($_SERVER['ENV']) && $_SERVER['ENV'] != 'prod') {
        echo '<div style="border: 3px solid red; padding: 6px;">';
        echo sprintf('<strong>%s</strong><br />In %s line %s<br /><br />', $e->getMessage(), $e->getFile(), $e->getLine());
        foreach ($e->getTrace() as $line) {
            echo sprintf('<div style="border: 1px solid black; padding: 4px;">%s line %s', $line['file'], $line['line']);
            if (isset($line['class'])) {
                echo sprintf(' (%s%s%s())', $line['class'], $line['type'], $line['function']);
            }
            echo '</div>';
        }
        echo '</div>';
    }
?>
</body>
</html>