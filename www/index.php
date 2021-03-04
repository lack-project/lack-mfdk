<?php

namespace App;


use Brace\Core\BraceApp;
use Brace\UiKit\CoreUi\CoreUiPage;
use Lack\Mfdk\MfdkModule;

require __DIR__ . "/../vendor/autoload.php";

$app = new BraceApp();
$app->addModule(new MfdkModule(
    __DIR__ . "/app-config.yaml",
    __DIR__ . "/manifest.yaml"
));


$app->run();
