<?php

namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Value;

use InvalidArgumentException;

final class MetaCondition
{
    private const ALLOWED_COMPARISONS = [
        '=',
        '!=',
        '>',
        '>=',
        '<',
        '<=',
        'LIKE',
        'NOT LIKE',
        'IN',
        'NOT IN',
        'BETWEEN',
        'NOT BETWEEN',
        'EXISTS',
        'NOT EXISTS',
        'REGEXP',
        'NOT REGEXP',
        'RLIKE',
    ];

    private const ALLOWED_TYPES = [
        'NUMERIC',
        'BINARY',
        'CHAR',
        'DATE',
        'DATETIME',
        'DECIMAL',
        'SIGNED',
        'TIME',
        'UNSIGNED',
    ];

    /**
     * @param scalar|array<int, scalar>|null $value
     */
    public function __construct(
        private readonly string $key,
        private readonly mixed $value = null,
        private readonly string $compare = '=',
        private readonly string $type = 'CHAR',
        private readonly ?string $compare_key = null,
        private readonly ?string $type_key = null
    ) {
        if ($this->key === '') {
            throw new InvalidArgumentException('Meta key cannot be empty.');
        }

        if (!in_array(strtoupper($this->compare), self::ALLOWED_COMPARISONS, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported meta comparison "%s".', $this->compare)
            );
        }

        if (!in_array(strtoupper($this->type), self::ALLOWED_TYPES, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported meta type "%s".', $this->type)
            );
        }
    }

    /**
     * Convert the condition to WP_Query meta_query format.
     *
     * @return array<string, mixed>
     */
    public function to_array(): array
    {
        $compare = strtoupper($this->compare);

        $condition = [
            'key'     => $this->key,
            'compare' => $compare,
            'type'    => strtoupper($this->type),
        ];

        if (!in_array($compare, ['EXISTS', 'NOT EXISTS'], true)) {
            $condition['value'] = $this->value;
        }

        if ($this->compare_key !== null) {
            $condition['compare_key'] = strtoupper($this->compare_key);
        }

        if ($this->type_key !== null) {
            $condition['type_key'] = strtoupper($this->type_key);
        }

        return $condition;
    }
}