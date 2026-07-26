<?php
namespace Ababilithub\FlexWordpress\Package\Template\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\Template\V1\Contract\Template as TemplateContract
};

abstract class Template implements TemplateContract
{
    protected array $config = [];

    protected array $default_config = [];

    protected string $asset_base_url = '';

    protected string $asset_base_prefix = '';

    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []): static;

    abstract public function render(array $data = []): string;

    public function set_config(array $config = []): static
    {
        $this->config = array_replace_recursive(
            $this->default_config,
            $config
        );

        return $this;
    }

    public function get_config(): array
    {
        return $this->config;
    }

    public function set_default_config(array $default_config = []): static
    {
        $this->default_config = $default_config;

        return $this;
    }

    public function get_default_config(): array
    {
        return $this->default_config;
    }

    protected function get_config_value( string $key, mixed $default = null ): mixed 
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function resolve_config(array $data): array
    {
        $config = isset($data['config']) && is_array($data['config'])
            ? $data['config']
            : $data;

        return array_replace_recursive($this->config, $config);
    }

    /**
     * @param mixed $attributes
     */
    protected function build_classes(
        string $type,
        string $size,
        string $color,
        mixed $attributes
    ): string {
        $classes = sprintf(
            'faih-pagination type-%s size-%s color-%s',
            sanitize_html_class($type),
            sanitize_html_class($size),
            sanitize_html_class($color)
        );

        foreach ((array) $attributes as $attribute) {
            if (!is_scalar($attribute)) {
                continue;
            }

            $attribute = sanitize_html_class((string) $attribute);

            if ($attribute !== '') {
                $classes .= ' attribute-' . $attribute;
            }
        }

        return $classes;
    }
}