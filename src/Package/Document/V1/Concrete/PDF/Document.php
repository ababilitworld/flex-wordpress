<?php
namespace Ababilithub\FlexWordpress\Package\Document\V1\Concrete\PDF;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Document\V1\Base\Document as BaseDocument,
};

if ( ! class_exists( __NAMESPACE__.'\Document' ) ) 
{
    class Document extends BaseDocument
    {
        public function save(): bool 
        {
            // PDF-specific save logic
            return true;
        }
        
        public function delete(): bool 
        {
            // PDF-specific delete logic
            return true;
        }
    }
}
