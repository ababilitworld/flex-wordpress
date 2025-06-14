<?php
namespace Ababilithub\FlexWordpress\Package\Document\V1\Factory;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Document\V1\Contract\Document as DocumentContract,
    FlexWordpress\Package\Factory\V1\Base\Factory as BaseFactory,
    FlexWordpress\Package\Document\V1\Repository\Document as DocumentRepository,
};

if ( ! class_exists( __NAMESPACE__.'\Document' ) ) 
{
    class Document extends BaseFactory
    {
        private $repository;

        public function __construct()
        {
            $this->repository = new DocumentRepository();
        }

        public static function create(string $type, array $data = []): DocumentContract 
        {
            switch ($type) 
            {
                case 'pdf':
                    return new PDFDocument($data);
                case 'word':
                    return new WordDocument($data);
                default:
                    throw new \InvalidArgumentException("Invalid document type: $type");
            }
        }
    }
}
