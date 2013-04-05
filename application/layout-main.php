<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $charset; ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!--<link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png"/> Faire taille 144x144 -->
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
    <link rel="stylesheet" href="/css/main.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
    <?php echo $css; ?>
</head>
<body>
<div data-role="page" data-theme="c" id="thepage" data-title="<?php echo $this->_('app_name'); ?> - <?php echo $title; ?>">
    <?php echo $javascripts; ?>
    <div data-role="header">
        <?php if (isset($backUrl) && $backUrl): ?>
            <a href="<?php echo $backUrl; ?>" data-role="button" data-icon="arrow-l" class="ui-btn-left" rel="external">
            <?php echo $this->_('back'); ?></a>
        <?php endif; ?>
        <h1 style="margin: 0;">
            <a style="margin: 0.2em;" href="<?php echo $homeUrl; ?>" data-role="button" data-icon="home" data-inline="true" data-min="true">
                <?php echo $this->_('main_title'); ?></a></h1>
        <?php if (isset($headerButton) && is_array($headerButton)): ?>
            <a href="<?php echo $headerButton['url']; ?>" data-role="button"<?php if (isset($headerButton['icon'])) echo sprintf(' data-icon="%s"', $headerButton['icon']);?> class="ui-btn-right" rel="external">
            <?php echo $headerButton['text']; ?></a>
        <?php endif; ?>
    </div>
    <?php if ($title): ?><div class="vinotitle"><?php echo $title; ?></div><?php endif; ?>
    <div data-role="content"><?php echo $content; ?></div>
</div>
</body>
</html>