<?php

require __DIR__ . '/../vendor/autoload.php';

use horses\Kernel;

Kernel::factory()->run(__DIR__ . '/..', array('doctrine', 'auth', 'locale', '\\vino\\saq\\HorsesPlugin'));
