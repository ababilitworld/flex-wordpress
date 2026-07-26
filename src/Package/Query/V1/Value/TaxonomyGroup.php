<?php

namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Value;

use InvalidArgumentException;

final class TaxonomyGroup
{
    /**
     * @var array<int, TaxonomyCondition|self>
     */
    private array $conditions = [];

    public function __construct(
        private readonly string $relation = 'AND'
    ) {
        $relation = strtoupper($this->relation);

        if (!in_array($relation, ['AND', 'OR'], true)) {
            throw new InvalidArgumentException(
                'Taxonomy relation must be either AND or OR.'
            );
        }
    }

    public static function make(string $relation = 'AND'): self
    {
        return new self($relation);
    }

    public function add(TaxonomyCondition|self $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @param array<int, int|string> $terms
     */
    public function where(
        string $taxonomy,
        array $terms = [],
        string $field = 'term_id',
        string $operator = 'IN',
        bool $include_children = true
    ): self {
        return $this->add(
            new TaxonomyCondition(
                taxonomy: $taxonomy,
                terms: $terms,
                field: $field,
                operator: $operator,
                include_children: $include_children
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