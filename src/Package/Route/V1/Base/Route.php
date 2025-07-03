<?php
namespace Ababilithub\FlexWordpress\Package\Route\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V1\Contract\Route as RouteContract
};

abstract class Route implements RouteContract
{
    protected string $slug = '';
    protected string $routeSlug = '';
    protected string $templateType = 'content';
    protected $templatePart = '';
    protected bool $rewriteFlush = false;
    protected array $queryVars = [];

    public function __construct()
    {
        $this->init();
    }

    abstract public function init(): void;
    public function register(): void
    {
        // Register rewrite rule
        add_action('init', function() 
            {
                add_rewrite_rule(
                    $this->get_slug().'/?$', 
                    'index.php?'.$this->get_route_slug().'=1', 
                    'top'
                );
                
                // Add custom query vars
                foreach ($this->queryVars as $var) 
                {
                    add_rewrite_tag("%{$var}%", '([^&]+)');
                }
                
                if ($this->rewriteFlush) 
                {
                    flush_rewrite_rules(false);
                    $this->rewriteFlush = false;
                }
            }
        );

        // Register query vars
        add_filter('query_vars', function($vars) {
            $vars[] = $this->get_route_slug();
            return array_merge($vars, $this->queryVars);
        });

        // Handle template
        add_filter('template_include', [$this, 'handle_template']);
    }

    public function handle_template($template)
    {
        if (get_query_var($this->get_route_slug())) 
        {
            switch ($this->get_template_type()) 
            {
                case 'file':
                    return $this->handle_file_template();
                case 'html':
                    return $this->handle_html_template();
                case 'content':
                    return $this->handle_content_template($template);
                case 'callback':
                    return $this->handle_callback_template();
                default:
                    return $template;
            }
        }
        return $template;
    }

    protected function handle_file_template()
    {
        if (is_string($this->templatePart)) 
        {
            $template_path = locate_template($this->templatePart);
            if ($template_path) 
            {
                return $template_path;
            }
            if (file_exists($this->templatePart)) 
            {
                return $this->templatePart;
            }
        }
        return get_template_directory() . '/index.php';
    }

    protected function handle_html_template()
    {
        if (is_string($this->templatePart)) 
        {
            echo $this->templatePart;
            exit;
        }
        return get_template_directory() . '/index.php';
    }

    protected function handle_content_template($template)
    {
        if (is_string($this->templatePart)) 
        {
            add_filter('the_content', fn() => $this->templatePart);
        }
        return $template;
    }

    protected function handle_callback_template()
    {
        if (is_callable($this->templatePart)) 
        {
            call_user_func($this->templatePart);
            exit;
        }
        return get_template_directory() . '/index.php';
    }

    // Setters
    protected function set_slug(string $slug): void
    {
        $this->slug = $slug;
    }

    protected function set_route_slug(string $routeSlug): void
    {
        $this->routeSlug = $routeSlug;
    }

    protected function set_template_type(string $templateType): void
    {
        $this->templateType = $templateType;
    }

    protected function set_template_part($templatePart): void
    {
        $this->templatePart = $templatePart;
    }

    protected function add_query_var(string $var): void
    {
        $this->queryVars[] = $var;
    }

    protected function enable_rewrite_flush(): void
    {
        $this->rewriteFlush = true;
    }

    // Getters
    public function get_slug(): string
    {
        return $this->slug;
    }

    public function get_route_slug(): string
    {
        return $this->routeSlug;
    }

    public function get_template_type(): string
    {
        return $this->templateType;
    }

    public function get_template_part()
    {
        return $this->templatePart;
    }
}