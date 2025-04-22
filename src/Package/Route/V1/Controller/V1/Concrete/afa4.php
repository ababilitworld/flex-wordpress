<?php
/*
 * Plugin Name: Advanced WordPress Router
 * Description: Class-based routing system with dynamic template handling
 * Version: 1.0
 */

class Router 
{
    protected $routes = [];
    protected $current_route;

    public function __construct() {
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'register_query_vars']);
        add_action('template_redirect', [$this, 'handle_route'], 1);
        add_filter('template_include', [$this, 'handle_template'], 99);
        register_activation_hook(__FILE__, [$this, 'flush_rewrite_rules']);
    }

    // Route registration method
    public function add_route(string $pattern, string $method, callable $callback) {
        $this->routes[] = [
            'pattern' => $pattern,
            'method' => $method,
            'callback' => $callback,
            'name' => 'custom_route_' . count($this->routes)
        ];

        return $this;
    }

    // Add rewrite rules
    public function add_rewrite_rules() {
        foreach ($this->routes as $route) {
            $regex = $this->pattern_to_regex($route['pattern']);
            add_rewrite_rule(
                $regex,
                'index.php?custom_route=' . $route['name'],
                'top'
            );
        }
    }

    // Register query variables
    public function register_query_vars($vars) {
        $vars[] = 'custom_route';
        return $vars;
    }

    // Handle route matching and callback execution
    public function handle_route() {
        global $wp;

        $route_name = get_query_var('custom_route');

        if (!$route_name) return;

        foreach ($this->routes as $route) {
            if ($route['name'] === $route_name) {
                $this->current_route = $route;
                $params = $this->extract_params($wp->request, $route['pattern']);
                
                // Verify HTTP method
                if ($_SERVER['REQUEST_METHOD'] !== strtoupper($route['method'])) {
                    wp_die('Method Not Allowed', 405);
                }

                // Execute callback
                call_user_func_array($route['callback'], $params);
                exit; // Prevent default WordPress processing
            }
        }

        // If no route matched but custom_route is set
        wp_die('Not Found', 404);
    }

    // Handle template loading
    public function handle_template($template) {
        if ($this->current_route) {
            $custom_template = locate_template('custom-routes/' . $this->current_route['name'] . '.php');
            return $custom_template ?: $template;
        }
        return $template;
    }

    // Helper: Convert route pattern to regex
    private function pattern_to_regex(string $pattern) {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '^' . str_replace('/', '\/', $regex) . '$';
    }

    // Helper: Extract parameters from URL
    private function extract_params(string $url, string $pattern) {
        $pattern = $this->pattern_to_regex($pattern);
        preg_match("#{$pattern}#", $url, $matches);
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    // Flush rewrite rules on activation
    public function flush_rewrite_rules() {
        $this->add_rewrite_rules();
        flush_rewrite_rules();
    }
}

// Initialize router
$router = new Router();

// Example controller class
class PageController {
    public function home() {
        // Set up data
        $data = ['title' => 'Home Page', 'content' => 'Welcome to our site!'];
        
        // Set template variables
        global $wp_query;
        $wp_query->set('route_data', $data);
        
        // Or render directly
        // $this->render('home', $data);
    }

    public function user_profile($id, $username) {
        $user = get_user_by('ID', $id);
        $data = [
            'title' => "{$user->display_name}'s Profile",
            'content' => "Username: {$username}"
        ];
        global $wp_query;
        $wp_query->set('route_data', $data);
    }

    private function render($template, $data = []) {
        extract($data);
        include locate_template("custom-routes/{$template}.php");
        exit;
    }
}

// Create controller instance
$controller = new PageController();

// Register routes
$router->add_route('/', 'GET', [$controller, 'home'])
       ->add_route('/user/{id}/{username}', 'GET', [$controller, 'user_profile']);

// Example template: theme/custom-routes/custom_route_0.php
/*
<?php
/**
 * Home Page Template
 *
 * @package YourTheme
 *

get_header();
global $wp_query;
$data = $wp_query->get('route_data');
?>

<main>
    <h1><?php echo esc_html($data['title']); ?></h1>
    <p><?php echo esc_html($data['content']); ?></p>
</main>

<?php
get_footer();
*/