<?php
namespace Ababilithub\FlexWordpress\Package\Document\V1\Service;

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
        private $factory;
        private $repository;
    
        public function __construct(DocumentRepository $repository) 
        {
            $this->repository = $repository;
        }
        
        public function createDocument(array $data): DocumentContract 
        {
            $document = DocumentFactory::create($data['type'], $data);
            
            if (!$this->repository->save($document)) 
            {
                throw new \RuntimeException('Failed to save document');
            }
            
            return $document;
        }
        
        public function getDocument(int $id): ?DocumentContract 
        {
            return $this->repository->find($id);
        }
    }
}
