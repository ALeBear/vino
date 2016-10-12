<?php

require __DIR__ . '/../vendor/autoload.php';

use horses\Kernel;

$_SERVER['REQUEST_URI'] = '/defaulter/signature-watchman';
$_SERVER['HTTP_HOST'] = 'vino.pouch.name';
Kernel::factory()->run(__DIR__ . '/..', array('doctrine', 'auth', 'locale', '\\vino\\saq\\HorsesPlugin'));
