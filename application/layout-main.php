<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $charset; ?>"/>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <?php echo $javascripts; ?>
    <?php echo $css; ?>
</head>
<body>
<div data-role="page" data-add-back-btn="true" data-back-btn-text="<?php echo $this->_('back') ;?>">
    <div data-role="header">
        <h1><?php echo $title; ?></h1>
        <?php if (isset($headerButton) && is_array($headerButton)): ?>
            <a href="<?php echo $headerButton['url']; ?>" data-role="button"<?php if (isset($headerButton['icon'])) echo sprintf(' data-icon="%s"', $headerButton['icon']);?> class="ui-btn-right">
            <?php echo $headerButton['text']; ?></a>
        <?php endif; ?>
    </div>
    <div data-role="content"><?php echo $content; ?></div>
</div>
</body>
</html>