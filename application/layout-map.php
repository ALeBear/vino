<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo $charset; ?>"/>
    <title><?php echo $this->_('app_name'); ?> - <?php echo $this->_('availability'); ?></title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png"/>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <link rel="stylesheet" href="/css/main.css" />
    <?php echo $javascripts; ?>
    <?php echo $css; ?>
    <script type="text/javascript">
    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(45.5036, -73.5696),
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        infowindow = new google.maps.InfoWindow({maxWidth: 200});
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(geolocSuccess, geolocError);
        }
        hereMarker = null;
        <?php if (count($availabilities)): ?>

        var posImage = new google.maps.MarkerImage(
            '/images/saq.png',
            null,
            null,
            new google.maps.Point(0, 0),
            new google.maps.Size(17, 17)
        );

            <?php foreach ($availabilities as $index => $avail): ?>
                <?php $aPos = $avail->getPos(); ?>

        var posMarker<?php echo $index; ?> = new google.maps.Marker({
                position: new google.maps.LatLng(<?php echo $aPos->getLat(); ?>, <?php echo $aPos->getLong(); ?>),
                map: map,
                icon: posImage,
                flat: true,
                title: '<?php echo str_replace("'", "\'",$this->_('title_pos', $avail->getQuantity(), $aPos->getAddress(), $aPos->getType())); ?>',
                visible: true
        });
        <?php
        $isFavorite = in_array($aPos->getId(), $favoriteIds);
        $favoritesAddUrlPos = str_replace('ACTION', $isFavorite ? 'r' : 'a', $favoritesAddUrl);
        $isWatching = in_array($aPos->getId(), $watchingIds);
        $watchingAddUrlPos = str_replace('ACTION', $isWatching ? 'r' : 'a', $watchingAddUrl);
        $favoritesAction = $isFavorite ? $this->_('remove_pos') : $this->_('add_pos');
        $watchingAction = $isWatching ? $this->_('remove_pos') : $this->_('add_pos');
        ?>
        google.maps.event.addListener(posMarker<?php echo $index; ?>, 'click', function () {
            infowindow.setContent('<?php echo str_replace("'", "\'", $this->_('text_pos', $avail->getQuantity(), $aPos->getAddress(), $aPos->getType(), $favoritesAddUrlPos, $aPos->getId(), $favoritesAction, $watchingAddUrlPos, $aPos->getId(), $watchingAction));?>');
            infowindow.setPosition(posMarker<?php echo $index; ?>.getPosition()); 
            infowindow.open(map);
        });

            <?php endforeach; ?>
        <?php endif; ?>
    }

    function geolocSuccess(position)
    {
        var center = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        if (!hereMarker) {
            map.setCenter(center);

            var image = new google.maps.MarkerImage(
                '/images/bluedot_retina.png',
                null,
                null,
                new google.maps.Point(0, 0),
                new google.maps.Size(17, 17)
            );

            hereMarker = new google.maps.Marker({
                position: center,
                map: map,
                optimized: false,
                icon: image,
                flat: true,
                title: '---',
                visible: true
            });
        } else {
            hereMarker.setPosition(center);
        }
    }

    function geolocError(msg) {}
    </script>
</head>
<body onload="initialize()">
<div data-role="page" id="map_page" data-title="<?php echo $this->_('app_name'); ?> - <?php echo $title; ?>" class="selectable">
    <div data-role="header">
        <a href="<?php echo $backUrl; ?>" data-role="button" data-icon="arrow-l" class="ui-btn-left">
        <?php echo $this->_('back'); ?></a>
        <h1 style="margin: 0;">
            <a style="margin: 0.2em;" href="<?php echo $homeUrl; ?>" data-role="button" data-icon="home" data-inline="true" data-min="true">
                <?php echo $this->_('main_title'); ?></a>
        </h1>
    </div>
    <div data-role="content" id="map_canvas"><?php echo $content; ?></div>
    <div style="position: absolute; top: 50px; left: 10px; background-color: white; padding: 10px; font-weight: bold; margin-right: 10px;">
        <?php echo $title; ?></div>
</body>
</html>
