<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $charset; ?>"/>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
    <link rel="stylesheet" href="<?php echo $urlPrefix; ?>/css/main.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
    <?php echo $css; ?>
</head>
<body>
<div data-role="page" data-theme="c" id="thepage">
    <?php echo $javascripts; ?>
    <div data-role="header">
        <?php if (isset($backUrl) && $backUrl): ?>
            <a href="<?php echo $backUrl; ?>" data-role="button" data-icon="arrow-l" class="ui-btn-left" rel="external">
            <?php echo $this->_('back'); ?></a>
        <?php endif; ?>
        <h1><?php echo $this->_('main_title'); ?></h1>
        <?php if (isset($headerButton) && is_array($headerButton)): ?>
            <a href="<?php echo $headerButton['url']; ?>" data-role="button"<?php if (isset($headerButton['icon'])) echo sprintf(' data-icon="%s"', $headerButton['icon']);?> class="ui-btn-right">
            <?php echo $headerButton['text']; ?></a>
        <?php endif; ?>
    </div>
    <?php if ($title): ?><div class="vinotitle"><?php echo $title; ?></div><?php endif; ?>
    <div data-role="content"><?php echo $content; ?></div>
</div>
</body>
</html>