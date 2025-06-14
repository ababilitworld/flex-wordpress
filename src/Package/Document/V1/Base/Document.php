<?php
namespace Ababilithub\FlexWordpress\Package\Document\V1\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Document\V1\Contract\Document as DocumentContract,
};

if ( ! class_exists( __NAMESPACE__.'\Document' ) ) 
{
    abstract class Document implements DocumentContract
    {
        protected $id;
        protected $title;
        protected $type;
        protected $path;
        protected $meta = [];
        
        public function __construct(array $data = []) 
        {
            $this->id = $data['id'] ?? 0;
            $this->title = $data['title'] ?? '';
            $this->type = $data['type'] ?? '';
            $this->path = $data['path'] ?? '';
            $this->meta = $data['meta'] ?? [];
        }
        
        public function getId(): int 
        {
            return $this->id;
        }
        
        public function getTitle(): string 
        {
            return $this->title;
        }
        
        public function getType(): string 
        {
            return $this->type;
        }
        
        public function getPath(): string 
        {
            return $this->path;
        }
        
        public function getMeta(string $key): string 
        {
            return $this->meta[$key] ?? '';
        }
        
        public function setMeta(string $key, string $value): void 
        {
            $this->meta[$key] = $value;
        }
        
        abstract public function save(): bool;
        abstract public function delete(): bool;
    }
}
