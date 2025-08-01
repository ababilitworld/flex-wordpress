<?php 
namespace Ababilithub\FlexWordpress\Package\Migration\V1\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Migration\V1\Contract\Migration as MigrationContract
};

abstract class Migration implements MigrationContract
{
    abstract public function up(): void;
    abstract public function down(): void;
    
    protected function runQuery(string $sql): void 
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}