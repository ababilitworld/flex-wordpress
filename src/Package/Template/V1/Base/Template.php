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
}