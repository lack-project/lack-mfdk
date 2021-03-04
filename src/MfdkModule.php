<?php


namespace Lack\Mfdk;


use Brace\Assets\AssetsMiddleware;
use Brace\Assets\AssetsModule;
use Brace\Core\Base\BraceAbstractMiddleware;
use Brace\Core\Base\ExceptionHandlerMiddleware;
use Brace\Core\Base\JsonReturnFormatter;
use Brace\Core\Base\NotFoundMiddleware;
use Brace\Core\BraceApp;
use Brace\Core\BraceModule;
use Brace\Mod\Request\Zend\BraceRequestLaminasModule;
use Brace\Router\RouterDispatchMiddleware;
use Brace\Router\RouterEvalMiddleware;
use Brace\Router\RouterModule;
use Brace\UiKit\Base\Element\Button;
use Brace\UiKit\Base\Template\UiKitPageReturnFormatter;
use Brace\UiKit\CoreUi\CoreUiConfig;
use Brace\UiKit\CoreUi\CoreUiModule;
use Brace\UiKit\CoreUi\CoreUiPage;
use Lack\Mfdk\Type\T_AppConfig;
use Lack\Mfdk\Type\T_Manifest;
use Phore\Di\Container\Producer\DiService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfdkModule implements BraceModule
{

    public function __construct(
        private ?string $appConfigFile = null,
        private ?string $localManifestFile = null
    ){}

    public function register(BraceApp $app)
    {

        $app->define("appConfig", new DiService(function () {
            return phore_hydrate(phore_file($this->appConfigFile)->get_yaml(), T_AppConfig::class);
        }));

        $app->define("manifests", new DiService(function (T_AppConfig $appConfig){
            $manifests = [];
            foreach ($appConfig->load as $load) {
                $manifestUrl = $load->baseUrl . "/manifest.yaml";
                $manifest = phore_hydrate(phore_yaml_decode(phore_http_request($manifestUrl)->send()->getBody()), T_Manifest::class);
                assert($manifest instanceof T_Manifest);
                $manifest->baseUrl = $load->baseUrl;
                $manifests[] = $manifest;
            }
            return $manifests;
        }));


        $app->define("feConfig", new DiService(function (array $manifests) {
            $feConfig = [
                "routes" => []
            ];
            foreach ($manifests as $manifest) {
                assert($manifest instanceof T_Manifest);
                foreach ($manifest->public_routes as $curRoute) {
                    $route = [
                        "route" => $curRoute->route,
                        "target" => $manifest->baseUrl . $curRoute->target
                    ];
                    $feConfig["routes"][] = $route;
                }
            }
            return $feConfig;
        }));

        $app->addModule(new BraceRequestLaminasModule());
        $app->addModule(new RouterModule());
        $app->addModule(new AssetsModule());
        $app->addModule(new CoreUiModule());

        $app->assets->virtual("/assets/js/plugins.js")->addFile(__DIR__ . "/../lib-js/mf.js");

        $app->define("coreUiConfig", new DiService(function (array $manifests) {
            $cuic = new CoreUiConfig();

            foreach ($manifests as $manifest) {
                assert($manifest instanceof T_Manifest);
                foreach ($manifest->naviLeft as $nav)
                    $cuic->sideNav->addElement(new Button($nav->text, $nav->icon, $nav->href));
                foreach ($manifest->naviTop as $nav)
                    $cuic->topNav->addElement( new Button($nav->text, $nav->icon, $nav->href));
                foreach ($manifest->naviAccount as $nav)
                    $cuic->accountPopup->addElement(new Button($nav->text, $nav->icon, $nav->href));
            }
            return $cuic;
        }));



        $app->setPipe([
            new ExceptionHandlerMiddleware(),
            new AssetsMiddleware(["/assets/"]),
            new RouterEvalMiddleware(),
            new RouterDispatchMiddleware([
                new UiKitPageReturnFormatter($app, "coreUiConfig"),
                new JsonReturnFormatter($app)
            ]),
            new class extends BraceAbstractMiddleware {

                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    $page = CoreUiPage::createCoreUiPage()->loadPHP(__DIR__ . "/../tpl/mfdk.tpl.php", $this->app->feConfig);
                    $uiKitFormattter = new UiKitPageReturnFormatter($this->app, "coreUiConfig");
                    return $uiKitFormattter->transform($page);
                }
            }
        ]);

        if ($this->localManifestFile !== null) {
            $manifest = phore_hydrate(phore_file($this->localManifestFile)->get_yaml(), T_Manifest::class);
            assert ($manifest instanceof T_Manifest);

            $dirname = phore_file($this->localManifestFile)->getDirname();

            foreach ($manifest->public_routes as $route) {
                $page = CoreUiPage::createEmptyPage();
                foreach ($route->files as $file)
                    $page->loadHtml($dirname->withRelativePath($file));

                $app->router->on("GET@{$route->target}", fn() => $page);
            }

            foreach ($manifest->private_routes as $route) {
                $page = CoreUiPage::createEmptyPage();
                foreach ($route->files as $file)
                    $page->loadHtml($dirname->withRelativePath($file));
                $app->router->on("GET@{$route->target}", fn() => $page);
            }
        }


    }
}