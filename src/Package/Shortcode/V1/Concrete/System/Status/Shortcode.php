<?php
namespace Ababilithub\FlexWordpress\Package\Shortcode\V1\Concrete\System\Status;

(defined('ABSPATH') && defined('WPINC')) || exit();

use Ababilithub\{
    FlexWordpress\Package\Shortcode\V1\Base\Shortcode as BaseShortcode
};

class Shortcode extends BaseShortcode
{
    private const PLUGIN_PRE_UNDS = 'ababilithub_server';
    private $is_windows;

    public function init(): void
    {
        $this->is_windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        $this->set_tag('ababilithub-server-capacity-status'); 

        $this->set_default_attributes([
            'show_memory' => 'true',
            'show_users' => 'true',
            'refresh_interval' => '60',
            'style' => 'card' // options: card, simple, table
        ]);

        $this->init_hooks();
    }

    public function init_hooks(): void
    {
        add_action(self::PLUGIN_PRE_UNDS.'_system_status_before_render', [$this, 'before_render']);
        add_action(self::PLUGIN_PRE_UNDS.'_system_status_after_render', [$this, 'after_render']);
    }

    public function render(array $attributes): string
    {
        $this->set_attributes($attributes);
        $params = $this->get_attributes();
        
        // Convert string booleans to actual booleans
        $params['show_memory'] = filter_var($params['show_memory'], FILTER_VALIDATE_BOOLEAN);
        $params['show_users'] = filter_var($params['show_users'], FILTER_VALIDATE_BOOLEAN);
        $params['refresh_interval'] = absint($params['refresh_interval']);

        $data = $this->get_system_status_data();

        ob_start();
        
        do_action(self::PLUGIN_PRE_UNDS.'_system_status_before_render', $data);
        
        switch($params['style']) {
            case 'simple':
                echo $this->render_simple_status($data, $params);
                break;
            case 'table':
                echo $this->render_table_status($data, $params);
                break;
            case 'card':
            default:
                echo $this->render_card_status($data, $params);
        }
        
        do_action(self::PLUGIN_PRE_UNDS.'_system_status_after_render', $data);
        
        return ob_get_clean();
    }

    private function get_system_status_data(): array
    {
        $memory_used = memory_get_usage(true);
        $memory_peak = memory_get_peak_usage(true);
        $memory_limit = ini_get('memory_limit');
        $memory_limit_bytes = $this->return_bytes($memory_limit);

        return [
            'memory' => [
                'used' => $memory_used,
                'peak' => $memory_peak,
                'limit' => $memory_limit_bytes,
                'percent_used' => ($memory_used / $memory_limit_bytes) * 100
            ],
            'users' => [
                'current' => $this->estimate_concurrent_users(),
                'max' => $this->estimate_max_capacity(),
                'percent_used' => ($this->estimate_concurrent_users() / max(1, $this->estimate_max_capacity())) * 100
            ],
            'server' => [
                'load' => $this->get_server_load(),
                'php_version' => phpversion(),
                'os' => PHP_OS
            ],
            'timestamp' => current_time('mysql')
        ];
    }

    private function get_server_load(): array
    {
        if ($this->is_windows) {
            // Windows doesn't support sys_getloadavg()
            return [0, 0, 0]; // Return zeros or implement alternative for Windows
        }
        
        if (function_exists('sys_getloadavg')) {
            return sys_getloadavg();
        }
        
        return [0, 0, 0]; // Fallback if function doesn't exist
    }

    private function render_card_status(array $data, array $params): string
    {
        ob_start();
        ?>
        <div class="system-status-card" style="
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            max-width: 500px;
            margin: 0 auto;
        ">
            <h3 style="margin-top: 0; color: #23282d;">System Status</h3>
            
            <?php if ($params['show_users']) : ?>
            <div class="status-section" style="margin-bottom: 15px;">
                <h4 style="margin-bottom: 5px;">User Capacity</h4>
                <div class="progress-bar" style="
                    height: 20px;
                    background: #e9ecef;
                    border-radius: 4px;
                    margin-bottom: 5px;
                    overflow: hidden;
                ">
                    <div style="
                        height: 100%;
                        width: <?php echo esc_attr(min(100, $data['users']['percent_used'])); ?>%;
                        background: <?php echo ($data['users']['percent_used'] > 80) ? '#dc3545' : '#28a745'; ?>;
                        transition: width 0.3s ease;
                    "></div>
                </div>
                <p style="margin: 0;">
                    <?php echo esc_html($data['users']['current']); ?> active users (max estimated: <?php echo esc_html($data['users']['max']); ?>)
                </p>
            </div>
            <?php endif; ?>
            
            <?php if ($params['show_memory']) : ?>
            <div class="status-section" style="margin-bottom: 15px;">
                <h4 style="margin-bottom: 5px;">Memory Usage</h4>
                <div class="progress-bar" style="
                    height: 20px;
                    background: #e9ecef;
                    border-radius: 4px;
                    margin-bottom: 5px;
                    overflow: hidden;
                ">
                    <div style="
                        height: 100%;
                        width: <?php echo esc_attr(min(100, $data['memory']['percent_used'])); ?>%;
                        background: <?php echo ($data['memory']['percent_used'] > 80) ? '#dc3545' : '#007bff'; ?>;
                        transition: width 0.3s ease;
                    "></div>
                </div>
                <p style="margin: 0;">
                    <?php echo esc_html($this->format_bytes($data['memory']['used'])); ?> of <?php echo esc_html($this->format_bytes($data['memory']['limit'])); ?> used
                    (Peak: <?php echo esc_html($this->format_bytes($data['memory']['peak'])); ?>)
                </p>
            </div>
            <?php endif; ?>
            
            <div class="server-info" style="
                display: flex;
                justify-content: space-between;
                font-size: 0.9em;
                color: #6c757d;
            ">
                <span>PHP <?php echo esc_html($data['server']['php_version']); ?></span>
                <span>OS: <?php echo esc_html($data['server']['os']); ?></span>
                <?php if (!$this->is_windows) : ?>
                <span>Load: <?php echo esc_html(implode(', ', $data['server']['load'])); ?></span>
                <?php endif; ?>
                <span>Last updated: <?php echo esc_html($data['timestamp']); ?></span>
            </div>
        </div>
        
        <?php if ($params['refresh_interval'] > 0) : ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.location.reload();
            }, <?php echo (int)$params['refresh_interval'] * 1000; ?>);
        });
        </script>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    private function render_simple_status(array $data, array $params): string
    {
        ob_start();
        ?>
        <div class="system-status-simple">
            <?php if ($params['show_users']) : ?>
            <p>Users: <?php echo esc_html($data['users']['current']); ?>/<?php echo esc_html($data['users']['max']); ?></p>
            <?php endif; ?>
            
            <?php if ($params['show_memory']) : ?>
            <p>Memory: <?php echo esc_html($this->format_bytes($data['memory']['used'])); ?>/<?php echo esc_html($this->format_bytes($data['memory']['limit'])); ?></p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_table_status(array $data, array $params): string
    {
        ob_start();
        ?>
        <table class="system-status-table" style="
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        ">
            <?php if ($params['show_users']) : ?>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">Current Users</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6; text-align: right;">
                    <?php echo esc_html($data['users']['current']); ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">Max Capacity</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6; text-align: right;">
                    <?php echo esc_html($data['users']['max']); ?>
                </td>
            </tr>
            <?php endif; ?>
            
            <?php if ($params['show_memory']) : ?>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">Memory Used</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6; text-align: right;">
                    <?php echo esc_html($this->format_bytes($data['memory']['used'])); ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">Memory Limit</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6; text-align: right;">
                    <?php echo esc_html($this->format_bytes($data['memory']['limit'])); ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">Peak Memory</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6; text-align: right;">
                    <?php echo esc_html($this->format_bytes($data['memory']['peak'])); ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <?php
        return ob_get_clean();
    }

    public function before_render(array $data): void
    {
        // Can be used to add wrapper HTML or other pre-render logic
    }

    public function after_render(array $data): void
    {
        // Can be used to add closing HTML or other post-render logic
    }

    private function return_bytes(string $val): int
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        
        return $val;
    }

    private function format_bytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function estimate_concurrent_users(): int
    {
        if (function_exists('get_site_transient')) {
            $users = get_site_transient('active_users');
            if ($users) {
                return count($users);
            }
        }
        
        $count = 0;
        
        if (class_exists('WooCommerce')) {
            global $wpdb;
            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}woocommerce_sessions");
        }
        
        $logged_in = count_users();
        $count += $logged_in['total_users'];
        
        return max(1, $count);
    }

    private function estimate_max_capacity(): int
    {
        $memory_limit = $this->return_bytes(ini_get('memory_limit'));
        $memory_per_user = 2 * 1024 * 1024; // 2MB per user
        
        $memory_based = floor($memory_limit / $memory_per_user);
        
        if ($this->is_windows) {
            // Windows doesn't support load averages, so just use memory-based calculation
            return $memory_based;
        }
        
        $load = $this->get_server_load();
        $load_based = floor((1 - min(1, $load[0] / max(1, count($load)))) * 100);
        
        return min($memory_based, $load_based);
    }
}