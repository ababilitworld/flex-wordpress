<?php
namespace Ababilithub\FlexWordpress\Package\Document\V1\Contract;

interface Document
{
    public function getId(): int;
    public function getTitle(): string;
    public function getType(): string;
    public function getPath(): string;
    public function getMeta(string $key): string;
    public function setMeta(string $key, string $value): void;
    public function save(): bool;
    public function delete(): bool;
}