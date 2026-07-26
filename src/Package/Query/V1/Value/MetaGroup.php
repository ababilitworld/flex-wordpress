<?php

namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Value;

use InvalidArgumentException;
final class MetaGroup
{
    /**
     * @var array<int, MetaCondition|self>
     */
    private array $conditions = [];

    public function __construct(
        private readonly string $relation = 'AND'
    ) {
        $relation = strtoupper($this->relation);

        if (!in_array($relation, ['AND', 'OR'], true)) {
            throw new InvalidArgumentException(
                'Meta relation must be either AND or OR.'
            );
        }
    }

    public static function make(string $relation = 'AND'): self
    {
        return new self($relation);
    }

    public function add(MetaCondition|self $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @param scalar|array<int, scalar>|null $value
     */
    public function where(
        string $key,
        mixed $value = null,
        string $compare = '=',
        string $type = 'CHAR'
    ): self {
        return $this->add(
            new MetaCondition(
                key: $key,
                value: $value,
                compare: $compare,
                type: $type
            )
        );
    }

    /**
     * @param callable(self): void $callback
     */
    public function group(string $relation, callable $callback): self
    {
        $group = new self($relation);

        $callback($group);

        return $this->add($group);
    }

    public function is_empty(): bool
    {
        return $this->conditions === [];
    }

    /**
     * @return array<int|string, mixed>
     */
    public function to_array(): array
    {
        if ($this->is_empty()) {
            return [];
        }

        $query = [
            'relation' => strtoupper($this->relation),
        ];

        foreach ($this->conditions as $condition) {
            $query[] = $condition->to_array();
        }

        return $query;
    }
}