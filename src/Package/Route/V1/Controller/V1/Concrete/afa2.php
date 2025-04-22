<?php
/**
 * Plugin Name: WP Route Manager by AbabilItWorld
 * Description: Laravel-style route management for WordPress using OOP SOLID principles.
 * Version: 1.0.0
 * Author: AbabilItWorld
 */

namespace AbabilItWorld\WpRouteManager;

require_once __DIR__ . '/vendor/autoload.php';

// Singleton App
class App
{
    private static ?App $instance = null;
    private RouteManager $routeManager;

    private function __construct()
    {
        $this->routeManager = RouteManager::getInstance();
    }

    public static function getInstance(): App
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function boot(): void
    {
        add_action('init', fn() => $this->routeManager->registerRoutes());
        add_action('template_redirect', fn() => RouteHandler::dispatch());
    }
}

// Facade
class Route
{
    public static function get(string $uri, array $action): void
    {
        RouteManager::getInstance()->register('GET', $uri, $action);
    }

    public static function post(string $uri, array $action): void
    {
        RouteManager::getInstance()->register('POST', $uri, $action);
    }
}

// Singleton Manager
class RouteManager
{
    private static ?RouteManager $instance = null;
    private array $routes = [];

    private function __construct() {}

    public static function getInstance(): RouteManager
    {
        return self::$instance ??= new self();
    }

    public function register(string $method, string $uri, array $action): void
    {
        $this->routes[$uri] = compact('method', 'action');
    }

    public function registerRoutes(): void
    {
        foreach ($this->routes as $uri => $route) {
            add_rewrite_rule('^' . $uri . '$', 'index.php?custom_route=' . $uri, 'top');
        }

        add_rewrite_tag('%custom_route%', '([^&]+)');
        flush_rewrite_rules(false);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}

// Dispatcher
class RouteHandler
{
    public static function dispatch(): void
    {
        $uri = get_query_var('custom_route');
        $route = RouteManager::getInstance()->getRoutes()[$uri] ?? null;

        if ($route && strtoupper($_SERVER['REQUEST_METHOD']) === $route['method']) {
            [$controllerClass, $method] = $route['action'];
            $controller = Factory::make($controllerClass);
            echo $controller->$method();
            exit;
        }
    }
}

// Abstract Controller
abstract class Controller
{
    protected ServiceInterface $service;

    public function __construct()
    {
        $this->service = $this->createService();
    }

    abstract protected function createService(): ServiceInterface;
}

// Controller Implementation
class HomeController extends Controller
{
    protected function createService(): ServiceInterface
    {
        return new HomeService(new HomeRepository());
    }

    public function handle(): string
    {
        $dto = $this->service->getWelcomeMessage();
        return '<h1>' . esc_html($dto->getMessage()) . '</h1>';
    }
}

// Abstract Service Interface
interface ServiceInterface
{
    public function getWelcomeMessage(): MessageDTO;
}

// Service Implementation
class HomeService implements ServiceInterface
{
    private RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getWelcomeMessage(): MessageDTO
    {
        $message = $this->repository->fetchMessage();
        return new MessageDTO($message);
    }
}

// Abstract Repository Interface
interface RepositoryInterface
{
    public function fetchMessage(): string;
}

// Repository Implementation
class HomeRepository implements RepositoryInterface
{
    public function fetchMessage(): string
    {
        return 'Welcome to WP Laravel-style Routing!';
    }
}

// DTO
class MessageDTO
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}

// Factory
class Factory
{
    public static function make(string $class): object
    {
        return new $class();
    }
}

// Model (Optional Example)
class RouteModel
{
    public string $method;
    public string $uri;
    public array $action;

    public function __construct(string $method, string $uri, array $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }
}

// Register Example Route
Route::get('home', [HomeController::class, 'handle']);

// Boot the app
App::getInstance()->boot();
