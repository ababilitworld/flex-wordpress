<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Registrar;

use Ababilithub\FlexWordpress\Package\Route\V1\Base\Route as BaseRoute;

/**
 * Class Registrar
 * Handles WordPress hooks related to rewrite rules, query vars, and templates.
 */
class Route
{
    private BaseRoute $baseRoute;

    public function __construct(BaseRoute $baseRoute)
    {
        $this->baseRoute = $baseRoute;
    }

    /**
     * Registers WordPress hooks for URL rewriting and template handling.
     */
    public function register_hooks(): void
    {
        add_action('init', [$this, 'add_rewrite_rule']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_filter('template_include', [$this, 'add_template']);
    }

    /**
     * Add a custom rewrite rule for the baseRoute.
     */
    public function add_rewrite_rule(): void
    {
        add_rewrite_rule($this->baseRoute->get_slug().'/?$', 'index.php?'.$this->baseRoute->get_route_slug().'=1', 'top');
    }

    /**
     * Add custom query vars for handling baseRoute routing.
     */
    public function add_query_vars(array $query_vars): array
    {
        $query_vars[] = $this->baseRoute->get_slug();
        return $query_vars;
    }

    /**
     * Load a custom template when accessing the baseRoute.
     */
    public function add_template($template)
    {
        if (get_query_var($this->baseRoute->get_slug()) == 1) 
        {
            $template_type = $this->baseRoute->get_template_type();
            $template_part = $this->baseRoute->get_template_part();

            if($template_type == 'file')
            {
                // **Solution 1: Return a Template File if it Exists**
                if (is_string($template_part) && file_exists($template_part)) 
                {
                    return $template_part;
                }
            }
            else if($template_type == 'html')
            {
                // **Solution 2: Print HTML Directly and Exit**
                if (is_string($template_part)) 
                {
                    echo $template_part;
                    exit;
                }
            }
            else if($template_type == 'content')
            {
                // **Solution 3: Use WordPress Filter-Based Rendering**
                add_filter('the_content', function($content) use ($template_part) {
                    return $template_part;
                });

            }

            return get_template_directory() . '/index.php'; // Keep WP theme layout
        }

        return $template;
    }
}
