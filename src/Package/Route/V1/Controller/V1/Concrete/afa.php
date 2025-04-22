<?php



// RouteSystem/Contracts/RouteInterface.php
namespace RouteSystem\Contracts;
interface RouteInterface {
    public function getUrl(): string;
    public function getLabel(): string;
    public function getCallback(): array|string;
    public function getCapability(): string;
}

// RouteSystem/DTO/RouteDTO.php
namespace RouteSystem\DTO;
class RouteDTO {
    public function __construct(
        public string $url,
        public string $label,
        public array|string $callback,
        public string $capability = 'read'
    ) {}
}

// RouteSystem/Models/RouteModel.php
namespace RouteSystem\Models;
use RouteSystem\Contracts\RouteInterface;
use RouteSystem\DTO\RouteDTO;
class RouteModel implements RouteInterface {
    public function __construct(protected RouteDTO $dto) {}
    public function getUrl(): string { return $this->dto->url; }
    public function getLabel(): string { return $this->dto->label; }
    public function getCallback(): array|string { return $this->dto->callback; }
    public function getCapability(): string { return $this->dto->capability; }
    public function toArray(): array {
        return [
            'url' => $this->getUrl(),
            'label' => $this->getLabel(),
            'callback' => $this->getCallback(),
            'capability' => $this->getCapability(),
        ];
    }
}

// RouteSystem/Repositories/RouteRepository.php
namespace RouteSystem\Repositories;
use RouteSystem\Models\RouteModel;
class RouteRepository {
    protected array $routes = [];
    public function add(RouteModel $route): void {
        $this->routes[$route->getUrl()] = $route;
    }
    public function get(string $url): ?RouteModel {
        return $this->routes[$url] ?? null;
    }
    public function all(): array {
        return $this->routes;
    }
}

// RouteSystem/Services/RouteService.php
namespace RouteSystem\Services;
use RouteSystem\Models\RouteModel;
use RouteSystem\Repositories\RouteRepository;
class RouteService {
    public function __construct(protected RouteRepository $repository) {}
    public function register(RouteModel $route): void {
        $this->repository->add($route);
    }
    public function all(): array {
        return $this->repository->all();
    }
    public function find(string $url): ?RouteModel {
        return $this->repository->get($url);
    }
}

// RouteSystem/Abstracts/AbstractRouteTool.php
namespace RouteSystem\Abstracts;
abstract class AbstractRouteTool {
    abstract public function handle(): void;
}

// RouteSystem/Factories/RouteFactory.php
namespace RouteSystem\Factories;
use RouteSystem\DTO\RouteDTO;
use RouteSystem\Models\RouteModel;
use RouteSystem\Abstracts\AbstractRouteTool;
class RouteFactory {
    protected static array $tools = [];
    public static function create(string $url, string $label, array|string $callback, string $capability = 'read'): RouteModel {
        return new RouteModel(new RouteDTO($url, $label, $callback, $capability));
    }
    public static function addTool(string $key, AbstractRouteTool $tool): void {
        self::$tools[$key] = $tool;
    }
    public static function getTool(string $key): ?AbstractRouteTool {
        return self::$tools[$key] ?? null;
    }
}

// RouteSystem/Facades/RouteFacade.php
namespace RouteSystem\Facades;
use RouteSystem\Traits\SingletonTrait;
use RouteSystem\Services\RouteService;
use RouteSystem\Repositories\RouteRepository;
class RouteFacade {
    use SingletonTrait;
    protected RouteService $service;
    protected function init(): void {
        $this->service = new RouteService(new RouteRepository());
    }
    public function getService(): RouteService {
        if (!isset($this->service)) $this->init();
        return $this->service;
    }
}

// RouteSystem/Controllers/RouteController.php
namespace RouteSystem\Controllers;
use RouteSystem\Facades\RouteFacade;
use RouteSystem\Traits\SingletonTrait;
class RouteController {
    use SingletonTrait;
    public function boot(): void {
        add_action('init', [$this, 'registerRewriteRules']);
        add_action('template_redirect', [$this, 'dispatch']);
    }
    public function registerRewriteRules(): void {
        foreach (RouteFacade::getInstance()->getService()->all() as $route) {
            add_rewrite_rule("^{$route->getUrl()}/?", 'index.php?custom_route=' . $route->getUrl(), 'top');
        }
        add_rewrite_tag('%custom_route%', '([^&]+)');
    }
    public function dispatch(): void {
        $url = get_query_var('custom_route');
        if (!$url) return;
        $route = RouteFacade::getInstance()->getService()->find($url);
        if (!$route || !current_user_can($route->getCapability())) {
            wp_die(__('Access denied'));
        }
        echo call_user_func($route->getCallback(), $route);
        exit;
    }
}



//example use
use RouteSystem\Factories\RouteFactory;
use RouteSystem\Facades\RouteFacade;
use RouteSystem\Controllers\RouteController;

add_action('plugins_loaded', function () {
    RouteFacade::getInstance()->getService()->register(
        RouteFactory::create('my-page', 'My Page', [MyController::class, 'view'], 'read')
    );
    RouteController::getInstance()->boot();
});


class MyController {
    public static function view($route) {
        // Include or render a template file here
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/my-page.php';
        return ob_get_clean();
    }
}
