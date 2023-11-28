<?php 

class Routes
{
    private array|Route $routes;

    public function setItems(array $routes) : void
    {
        foreach($routes as $uri => $data) {
            $route = new Route(
                $uri, 
                $data[0], 
                $data[1], 
                $data[2]
            );
            $this->routes[] = $route;
        }
    }

    public function getRoute(string $path, string $method) : null|Route
    {
        foreach ($this->routes as $route) {
            if ($route->getPath() == $path && $route->getMethod() == $method) {
                return $route;
            }
        }
        return null;
    }
}

class Route 
{
    private string $path;
    private string $method;
    private string $controller;
    private string $function;

    public function __construct(string $path, string $method, string $controller, string $function)
    {
        $this->path = $path;
        $this->method = $method;
        $this->controller = $controller;
        $this->function = $function;
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function getMethod() : string
    {
        return $this->method;
    }
    public function getController() : string
    {
        return $this->controller;
    }
    public function getFunction() : string
    {
        return $this->function;
    }
}

class Request
{
    private string $uri;
    private string $method;
    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function getUri() : string
    {
        return $this->uri;
    }
    public function getMethod() : string
    {
        return $this->method;
    }
}

class Controller
{

}

class HomeController extends Controller
{
    public function index() 
    {
        echo 'home page';
    }
}

class AboutController extends Controller
{
    public function index() 
    {
        echo 'about page';
    }
}

class Router
{
    private Routes $routes;
    private Request $request;

    private Controller $controller;

    function __construct(Routes $routes, Request $request) 
    {
        $this->routes = $routes;
        $this->request = $request;
    }

    public function run() : void
    {
        $uri = $this->request->getUri();
        $method = $this->request->getMethod();
        $route = $this->routes->getRoute($uri, $method);
        if ($route !== null) {
            $controller = $route->getController();
            $function = $route->getFunction();

            $this->controller = new $controller;
            $this->controller->{$function}();
        } else {
           echo '404';
        }
    }
}

$routes_file = require_once 'routes.php';
$routes = new Routes;
$routes->setItems($routes_file);

$request = new Request;

$router = new Router($routes, $request);
$router->run();

