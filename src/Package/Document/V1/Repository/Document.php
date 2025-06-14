<?php
namespace Ababilithub\FlexWordpress\Package\Document\V1\Repository;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Document\V1\Contract\Document as DocumentContract,
    FlexWordpress\Package\Document\V1\Factory\Document as DocumentFactory,
    FlexWordpress\Package\Document\V1\Repository\Document as DocumentRepository,
};

if ( ! class_exists( __NAMESPACE__.'\Document' ) ) 
{
    class Document
    {
        private $db;
    
        public function __construct() 
        {
            global $wpdb;
            $this->db = $wpdb;
        }
        
        public function find(int $id): ?DocumentContract 
        {
            $document = $this->db->get_row(
                $this->db->prepare("SELECT * FROM " . Constants::TABLE_DOCUMENTS . " WHERE id = %d", $id)
            );
            
            if (!$document) {
                return null;
            }
            
            return DocumentFactory::create($document->type, (array)$document);
        }
        
        public function save(DocumentContract $document): bool 
        {
            // Implementation for saving to database
        }
    }
}
