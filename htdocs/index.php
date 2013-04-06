<?php

//Set this so in iOS screen app mode the session is not reset when switching apps
session_set_cookie_params(365 * 24 * 60 * 60);

require __DIR__ . '/../vendor/autoload.php';

use horses\Kernel;

Kernel::factory()->run(__DIR__ . '/..', array('doctrine', 'auth', 'locale', '\\vino\\saq\\HorsesPlugin'));
