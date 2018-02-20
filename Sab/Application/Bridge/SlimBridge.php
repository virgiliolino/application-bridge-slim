<?php
namespace Sab\Application\Bridge;

/*use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Bridge\ApplicationBridge\Route;
use Bridge\ApplicationBridge\CommandHandler;
*/

/**
 * Bridge between a SlimApplication and a Standard Application defined in ApplicationBrdige
 */
class SlimBridge extends ApplicationBridge {
    private $app;

    public function __construct(App $app) {
        $this->app = $app;
    }

    /**
     * Register a new route with the given verbs.
     *
     * @param  string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return void
     */

    public function addRoute(Route $route) {
        if (!$route->isValid()) {
            //perhaps some log?
            return;
        }

        $routeValue = $route->getAction()->getValue();
        $this->app->map(
            [strtolower($route->getMethod()->getValue())],
            $route->getUri()->getValue(),
            function (
                RequestInterface $request,
                ResponseInterface $response,
                $params
            ) use ($routeValue) {
                $handler = $this->get($routeValue);
                return $handler->execute($request, $response, $params);
            }
        );
    }
}