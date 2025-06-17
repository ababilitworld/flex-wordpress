<?php
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Base;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexPhp\Package\Utility\ArrayUtility\Utility as ArrayUtility,
    FlexWordpress\Package\Posttype\Contract\Posttype as PosttypeContract,
};

abstract class Posttype implements PosttypeContract
{    
    protected $posttype;
    protected $slug;
    protected $taxonomies = [];
    protected $labels = [];
    protected $args = [];
    protected array $metas = [];
    
    public function __construct()
    {
        $this->init();
    }

    abstract protected function init(): void;
    
    protected function init_hook(): void
    {
        add_action('init', [$this, 'register_post_type'], 31);
        add_action('init', [$this, 'process_metas'], 32);
    }

    protected function init_service(): void
    {
        // Can be overridden by child classes
    }

    protected function set_labels(array $labels): void
    {
        $this->labels = $labels;
    }

    protected function set_args(array $args): void
    {
        $this->args = $args;
    }

    protected function set_metas(array $metas): void
    {
        $this->metas = $metas;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function add_taxonomy(string $taxonomy_slug): self
    {
        if (!in_array($taxonomy_slug, $this->taxonomies, true)) 
        {
            $this->taxonomies[] = $taxonomy_slug;

            if (post_type_exists($this->slug) && taxonomy_exists($taxonomy_slug)) 
            {
                $object_taxonomies = get_object_taxonomies($this->slug, 'names');
                if (!in_array($taxonomy_slug, $object_taxonomies, true)) 
                {
                    register_taxonomy_for_object_type($taxonomy_slug, $this->slug);
                }
            }
        }

        return $this;
    }

    public function register_post_type(): void
    {
        register_post_type($this->slug, $this->args);
    }

    public function process_metas(): void
    {
        if (empty($this->metas)) return;
        
        foreach ($this->metas as $meta) 
        {
            $this->register_meta($meta);
        }
    }

    protected function generate_meta_data(
        string $key,
        string $type = 'string',
        string $description = '',
        bool $single = true,
        bool $show_in_rest = true,
        array $args = []
    ): array {
        return [
            'key' => $key,
            'type' => $type,
            'description' => $description,
            'single' => $single,
            'show_in_rest' => $show_in_rest,
            'args' => $args
        ];
    }

    public function add_meta(array $meta): void
    {
        if (!ArrayUtility::search_array($meta, $this->metas, 'key')) 
        {
            $this->metas[] = $meta;
        }
    }

    public function register_meta(array $meta): void
    {
        register_post_meta(
            $this->slug, 
            $meta['key'], 
            [
                'type' => $meta['type'] ?? 'string',
                'description' => $meta['description'] ?? '',
                'single' => $meta['single'] ?? true,
                'show_in_rest' => $meta['show_in_rest'] ?? true,
                'sanitize_callback' => $meta['sanitize_callback'] ?? null,
                'auth_callback' => $meta['auth_callback'] ?? null,
            ]
        );
    }

    public function register_metas(): void
    {
        foreach ($this->metas as $meta) 
        {
            register_post_meta(
                $this->slug,
                $meta['key'],
                [
                    'type' => $meta['type'] ?? 'string',
                    'description' => $meta['description'] ?? '',
                    'single' => $meta['single'] ?? true,
                    'show_in_rest' => $meta['show_in_rest'] ?? true,
                    'sanitize_callback' => $meta['sanitize_callback'] ?? null,
                    'auth_callback' => $meta['auth_callback'] ?? null,
                ]
            );
        }
    }
}