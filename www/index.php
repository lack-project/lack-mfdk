<?php

namespace App;


use Brace\Core\BraceApp;
use Brace\UiKit\CoreUi\CoreUiPage;
use Lack\Mfdk\MfdkModule;

require __DIR__ . "/../vendor/autoload.php";

$app = new BraceApp();
$app->addModule(new MfdkModule(__DIR__ . "/app-config.yaml"));

$app->router->on("GET@/app/test", fn() => CoreUiPage::createEmptyPage()->loadHtml(__DIR__ . "/page/test.html"));
$app->router->on("GET@/app/page2", fn() => CoreUiPage::createEmptyPage()->loadHtml(__DIR__ . "/page/page2.html"));

$app->run();
