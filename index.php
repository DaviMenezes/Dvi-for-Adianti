<?php

use App\Templates\ThemeLoader;

require_once 'init.php';

$themeLayout = new ThemeLoader($ini);
$themeLayout->appName(APPLICATION_NAME);

$themeLayout->load();
