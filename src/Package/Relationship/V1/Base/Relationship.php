<?php 
namespace Ababilithub\FlexWordpress\Package\Relationship\V1\Base;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Relationship\V1\Contract\Relationship as RelationshipContract
};

// Models/BaseRelationship.php
abstract class Relationship implements RelationshipContract 
{
    protected $attributes = [];

    public function __construct(array $attributes = []) 
    {
        $this->attributes = $attributes;
    }

    public function getSourceId(): int 
    {
        return (int) $this->attributes['source_id'];
    }

    public function getSourceType(): string 
    {
        return (string) $this->attributes['source_type'];
    }

    public function getTargetId(): int 
    {
        return (int) $this->attributes['target_id'];
    }

    public function getTargetType(): string 
    {
        return (string) $this->attributes['target_type'];
    }

    public function getRelationName(): string 
    {
        return (string) $this->attributes['relation_name'];
    }

    public function getRelationMeta(): array 
    {
        return json_decode($this->attributes['relation_meta'] ?? '[]', true) ?: [];
    }
}

