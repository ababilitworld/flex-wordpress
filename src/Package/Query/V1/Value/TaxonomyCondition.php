<?php

declare(strict_types=1);

namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Value;

use InvalidArgumentException;

defined('ABSPATH') || exit;

final class TaxonomyCondition
{
    private const ALLOWED_OPERATORS = [
        'IN',
        'NOT IN',
        'AND',
        'EXISTS',
        'NOT EXISTS',
    ];

    private const ALLOWED_FIELDS = [
        'term_id',
        'name',
        'slug',
        'term_taxonomy_id',
    ];

    /**
     * @param array<int, int|string> $terms
     */
    public function __construct(
        private readonly string $taxonomy,
        private readonly array $terms = [],
        private readonly string $field = 'term_id',
        private readonly string $operator = 'IN',
        private readonly bool $include_children = true
    ) {
        if ($this->taxonomy === '') {
            throw new InvalidArgumentException('Taxonomy cannot be empty.');
        }

        if (!in_array($this->field, self::ALLOWED_FIELDS, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported taxonomy field "%s".', $this->field)
            );
        }

        if (!in_array(strtoupper($this->operator), self::ALLOWED_OPERATORS, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported taxonomy operator "%s".', $this->operator)
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function to_array(): array
    {
        $operator = strtoupper($this->operator);

        $condition = [
            'taxonomy'         => $this->taxonomy,
            'field'            => $this->field,
            'operator'         => $operator,
            'include_children' => $this->include_children,
        ];

        if (!in_array($operator, ['EXISTS', 'NOT EXISTS'], true)) {
            $condition['terms'] = $this->terms;
        }

        return $condition;
    }
}