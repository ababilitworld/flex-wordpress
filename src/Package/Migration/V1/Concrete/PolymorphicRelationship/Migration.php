<?php 
namespace Ababilithub\FlexWordpress\Package\Migration\V1\Concrete\PolymorphicRelationship;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Migration\V1\Base\Migration as BaseMigration
};

class Migration extends BaseMigration 
{
    public function up(): void 
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wp_eloquent_relationships';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            source_id bigint(20) NOT NULL,
            source_type varchar(100) NOT NULL,
            target_id bigint(20) NOT NULL,
            target_type varchar(100) NOT NULL,
            relation_name varchar(100) NOT NULL,
            relation_meta text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_relationship (source_id,source_type,target_id,target_type,relation_name),
            KEY source_index (source_id,source_type,relation_name),
            KEY target_index (target_id,target_type,relation_name),
            KEY relation_index (relation_name)
        ) ENGINE=InnoDB $charset_collate;";
        
        $this->runQuery($sql);
    }
    
    public function down(): void 
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wp_eloquent_relationships';
        $this->runQuery("DROP TABLE IF EXISTS $table");
    }
}