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
    }
}

// Facade
class Route
{
    public static function get(string $uri, array $action): void
    {
        RouteManager::getInstance()->get($uri, $action);
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

    public function get(string $uri, array $action): void
    {
        $this->routes[$uri] = ['method' => 'GET', 'action' => $action];
    }

    public function registerRoutes(): void
    {
        foreach ($this->routes as $uri => $route) {
            add_rewrite_rule($uri, 'index.php?route_handler=' . $uri, 'top');
        }
        add_action('template_redirect', fn() => RouteHandler::dispatch());
    }
}

// Dispatcher
class RouteHandler
{
    public static function dispatch(): void
    {
        global $wp;
        $uri = $wp->request;
        $route = RouteManager::getInstance()->routes[$uri] ?? null;

        if ($route) {
            [$controller, $method] = $route['action'];
            $controllerInstance = Factory::make($controller);
            echo $controllerInstance->$method();
            exit;
        }
    }
}

// Abstract Base
abstract class Controller
{
    abstract public function handle(): string;
}

// Concrete Controller
class HomeController extends Controller
{
    public function handle(): string
    {
        return '<h1>Welcome to Home Route</h1>';
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

// Example Route
Route::get('home', [HomeController::class, 'handle']);
