<?php
namespace Ababilithub\FlexELand\Package\Plugin\Route\V1\Concrete\Land\Deed\List;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Route\V1\Base\Route as BaseRoute
};

use const Ababilithub\{
    FlexELand\PLUGIN_PRE_HYPH
};

class Route extends BaseRoute
{
    public function init(): void
    {
        $this->set_slug(PLUGIN_PRE_HYPH.'/deed-list');
        $this->set_route_slug(PLUGIN_PRE_HYPH.'_deed_list');
        $this->set_template_type('content');
        $this->set_template_part($this->get_deed_list_content());
        
        // Add custom query vars for pagination/filtering
        $this->add_query_var('deed_page');
        $this->add_query_var('deed_type');
        
        // Flush rewrite rules on first activation
        $this->enable_rewrite_flush();
    }

    protected function get_deed_list_content(): string
    {
        // Get any query vars
        $page = get_query_var('deed_page', 1);
        $type = get_query_var('deed_type', 'all');
        
        // Process request and prepare data
        $data = $this->get_deed_list_data($page, $type);
        
        // Render template
        ob_start();
        ?>
        <div class="deed-list-container">
            <h1>Land Deeds</h1>
            
            <?php $this->render_filters($type); ?>
            
            <div class="deed-list">
                <?php foreach ($data['deeds'] as $deed): ?>
                    <div class="deed-card">
                        <h3><?= esc_html($deed['title']) ?></h3>
                        <p>Type: <?= esc_html($deed['type']) ?></p>
                        <p>Location: <?= esc_html($deed['location']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php $this->render_pagination($page, $data['total_pages']); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function get_deed_list_data(int $page, string $type): array
    {
        // In a real implementation, this would query your database
        return [
            'deeds' => [
                [
                    'title' => 'Sample Deed 1',
                    'type' => 'Residential',
                    'location' => 'Downtown'
                ],
                [
                    'title' => 'Sample Deed 2', 
                    'type' => 'Commercial',
                    'location' => 'Uptown'
                ]
            ],
            'total_pages' => 3
        ];
    }

    protected function render_filters(string $currentType): void
    {
        $types = ['all' => 'All Types', 'residential' => 'Residential', 'commercial' => 'Commercial'];
        ?>
        <div class="deed-filters">
            <form method="get" action="">
                <select name="deed_type">
                    <?php foreach ($types as $value => $label): ?>
                        <option value="<?= esc_attr($value) ?>" <?= selected($currentType, $value) ?>>
                            <?= esc_html($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <?php
    }

    protected function render_pagination(int $currentPage, int $totalPages): void
    {
        if ($totalPages <= 1) return;
        ?>
        <div class="deed-pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?deed_page=<?= $i ?>" class="<?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php
    }
}