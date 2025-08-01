<?php 
namespace Ababilithub\FlexWordpress\Package\Relationship\V1\Contract;

interface Relationship
{
    public function getSourceId(): int;
    public function getSourceType(): string;
    public function getTargetId(): int;
    public function getTargetType(): string;
    public function getRelationName(): string;
    public function getRelationMeta(): array;
}