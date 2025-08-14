<?php
namespace Ababilithub\FlexPhp\Package\ColorScheme\V1\Base;

use Ababilithub\{
    FlexPhp\Package\ColorScheme\V1\Contract\ColorScheme as ColorSchemeContract,
};

abstract class ColorScheme implements ColorSchemeContract
{
    protected string $name;
    protected string $type;
    protected string $primary_color;
    protected string $primary_dark_color;
    protected string $secondary_color;
    protected string $background_color;
    protected string $text_color;
    protected array $additional_colors = [];
    protected bool $isDarkMode = false;
       
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

        $this->validate_color('primary_color', $this->primary_color);
        $this->validate_color('primary_dark_color', $this->primary_dark_color);
        $this->validate_color('secondary_color', $this->secondary_color);
        $this->validate_color('background_color', $this->background_color);
        $this->validate_color('text_color', $this->text_color);

        foreach ($this->additional_colors as $name => $color) {
            $this->validate_color($name, $color);
        }

        if (!($this->is_accessible())) 
        {
            throw new \RuntimeException('Color Scheme is not Accessible! Please change text and background colors to make the scheme accessible!!!');
        }
    }

    protected function validate_color(string $property, string $color): void
    {
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) 
        {
            throw new \InvalidArgumentException(
                sprintf('%s must be a valid hex color, "%s" given', $property, $color)
            );
        }
    }

    public function calculate_contrast_ratio(): float
    {
        $textLuminance = $this->calculate_luminance($this->text_color);
        $bgLuminance = $this->calculate_luminance($this->background_color);
        return (max($textLuminance, $bgLuminance) + 0.05) / (min($textLuminance, $bgLuminance) + 0.05);
    }

    protected function calculate_luminance(string $hexColor): float
    {
        $r = hexdec(substr($hexColor, 1, 2)) / 255;
        $g = hexdec(substr($hexColor, 3, 2)) / 255;
        $b = hexdec(substr($hexColor, 5, 2)) / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : (($r + 0.055) / 1.055) ** 2.4;
        $g = $g <= 0.03928 ? $g / 12.92 : (($g + 0.055) / 1.055) ** 2.4;
        $b = $b <= 0.03928 ? $b / 12.92 : (($b + 0.055) / 1.055) ** 2.4;

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    public function is_accessible(): bool
    {
        return $this->calculate_contrast_ratio() >= 4.5;
    }

    public function toCssVariables(): array
    {
        return [
            '--scheme-name' => $this->getName(),
            '--scheme-type' => $this->getType(),
            '--primary-color' => $this->getPrimaryColor(),
            '--secondary-color' => $this->getSecondaryColor(),
            '--background-color' => $this->getBackgroundColor(),
            '--text-color' => $this->getTextColor(),            
        ];
    }

    // Common getters and setters
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getType(): string { return $this->type; }
    public function setType(string $type): void { $this->type = $type; }
    public function getPrimaryColor(): string { return $this->primary_color; }
    public function setPrimaryColor(string $color): void { $this->primary_color = $color; }
    public function getPrimaryDarkColor(): string { return $this->primary_dark_color; }
    public function setPrimaryDarkColor(string $color): void { $this->primary_dark_color = $color; }
    public function getSecondaryColor(): string { return $this->secondary_color; }
    public function setSecondaryColor(string $color): void { $this->secondary_color = $color; }
    public function getBackgroundColor(): string { return $this->background_color; }
    public function setBackgroundColor(string $color): void { $this->background_color = $color; }
    public function getTextColor(): string { return $this->text_color; }
    public function setTextColor(string $color): void { $this->text_color = $color; }
    public function isDarkMode(): bool { return $this->isDarkMode; }
    public function setIsDarkMode(bool $mode): void { $this->isDarkMode = $mode; }
    public function getAdditionalColors(): array { return $this->additional_colors; }
    public function setAdditionalColors(array $colors): void { $this->additional_colors = $colors; }
    
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'primary_color' => $this->getPrimaryColor(),
            'primary_dark_color' => $this->getPrimaryDarkColor(),
            'secondary_color' => $this->getSecondaryColor(),
            'background_color' => $this->getBackgroundColor(),
            'text_color' => $this->getTextColor(),
            'is_dark_mode' => $this->isDarkMode(),
            'additional_colors' => $this->getAdditionalColors(),
            'is_accessible' => $this->is_accessible(),
            'contrast_ratio' => $this->calculate_contrast_ratio(),
            'css_variables' => $this->toCssVariables()
        ];
    }

    protected function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) 
        {
            if (property_exists($this, $key)) 
            {
                $this->{$key} = $value;
            }
        }
    }
}