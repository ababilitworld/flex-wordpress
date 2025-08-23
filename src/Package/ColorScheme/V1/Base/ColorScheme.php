<?php
namespace Ababilithub\FlexWordpress\Package\ColorScheme\V1\Base;

use Ababilithub\{
    FlexWordpress\Package\ColorScheme\V1\Contract\ColorScheme as ColorSchemeContract,
    FlexWordpress\Package\ColorScheme\V1\Mixin\ColorScheme as ColorSchemeMixin,
};

abstract class ColorScheme implements ColorSchemeContract
{
    use ColorSchemeMixin;
    protected string $name;
    protected string $type;
    protected string $primary_color;
    protected string $primary_dark_color;
    protected string $secondary_color;
    protected string $background_color;
    protected string $text_color;
    protected array $additional_colors = [];
    protected bool $is_dark_mode = false;
       
    public function __construct(array $data = [])
    {
        $this->init($data);
    }

    abstract public function init(array $data = []):static;

    public function register(): void
    {

    }

    public function validate(): void
    {
        if (empty($this->name)) 
        {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        $this->validate_color_array($this->get_colors());

        if(count($this->additional_colors))
        {
            $this->validate_color_array($this->additional_colors);
        }

        if (!($this->has_sufficient_contrast_ratio())) 
        {
            throw new \RuntimeException('Color Scheme is not Accessible! Please change text and background colors to make the scheme accessible!!!');
        }
    }

    

    public function get_colors(): array
    {
        return [
            'primary-color' => $this->primary_color,
            'primary-dark-color' => $this->primary_dark_color,
            'secondary-color' => $this->secondary_color,
            'background-color' => $this->background_color,
            'text-color' => $this->text_color,            
        ];
    }

    // Common logic for all schemes
    public function generate_css(): string 
    {
        $colors = $this->get_colors();
        $css = ":root {";
        foreach ($colors as $key => $value) 
        {
            $css .= "--{$key}: {$value};";
        }
        $css .= "}";
        return $css;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'primary_color' => $this->primary_color,
            'primary_dark_color' => $this->primary_dark_color,
            'secondary_color' => $this->secondary_color,
            'background_color' => $this->background_color,
            'text_color' => $this->text_color,
            'is_dark_mode' => $this->is_dark_mode,
            'additional_colors' => $this->additional_colors,
            'is_accessible' => $this->is_accessible(),
            'contrast_ratio' => $this->calculate_contrast_ratio(),
            'css' => $this->generate_css()
        ];
    }
}