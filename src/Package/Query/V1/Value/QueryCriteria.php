<?php

namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Value;

use InvalidArgumentException;
final class QueryCriteria
{
    private ?MetaGroup $meta_group = null;

    private ?TaxonomyGroup $taxonomy_group = null;

    /**
     * @var array<string, mixed>
     */
    private array $arguments = [];

    public static function make(): self
    {
        return new self();
    }

    public function status(string|array $status): self
    {
        $this->arguments['post_status'] = $status;

        return $this;
    }

    public function search(string $keyword): self
    {
        $this->arguments['s'] = sanitize_text_field($keyword);

        return $this;
    }

    public function author(int $author_id): self
    {
        $this->arguments['author'] = absint($author_id);

        return $this;
    }

    /**
     * @param array<int, int> $post_ids
     */
    public function include(array $post_ids): self
    {
        $this->arguments['post__in'] = array_values(
            array_filter(array_map('absint', $post_ids))
        );

        return $this;
    }

    /**
     * @param array<int, int> $post_ids
     */
    public function exclude(array $post_ids): self
    {
        $this->arguments['post__not_in'] = array_values(
            array_filter(array_map('absint', $post_ids))
        );

        return $this;
    }

    public function parent(int $parent_id): self
    {
        $this->arguments['post_parent'] = absint($parent_id);

        return $this;
    }

    public function page(int $page): self
    {
        $this->arguments['paged'] = max(1, $page);

        return $this;
    }

    public function per_page(int $per_page): self
    {
        $this->arguments['posts_per_page'] = max(1, min(100, $per_page));

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->arguments['offset'] = max(0, $offset);

        return $this;
    }

    public function order_by(
        string|array $order_by,
        string $order = 'DESC'
    ): self {
        $allowed_order_by = [
            'none',
            'ID',
            'author',
            'title',
            'name',
            'type',
            'date',
            'modified',
            'parent',
            'rand',
            'comment_count',
            'relevance',
            'menu_order',
            'meta_value',
            'meta_value_num',
            'post__in',
        ];

        if (is_string($order_by) && !in_array($order_by, $allowed_order_by, true)) {
            throw new InvalidArgumentException(
                sprintf('Unsupported order-by value "%s".', $order_by)
            );
        }

        $order = strtoupper($order);

        if (!in_array($order, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException('Order must be ASC or DESC.');
        }

        $this->arguments['orderby'] = $order_by;
        $this->arguments['order'] = $order;

        return $this;
    }

    public function order_by_meta(
        string $meta_key,
        string $order = 'ASC',
        bool $numeric = false
    ): self {
        $this->arguments['meta_key'] = sanitize_key($meta_key);
        $this->arguments['orderby'] = $numeric
            ? 'meta_value_num'
            : 'meta_value';

        $this->arguments['order'] = strtoupper($order) === 'DESC'
            ? 'DESC'
            : 'ASC';

        return $this;
    }

    public function date_range(
        ?string $after = null,
        ?string $before = null,
        bool $inclusive = true
    ): self {
        $date_query = [
            'inclusive' => $inclusive,
        ];

        if ($after !== null) {
            $date_query['after'] = $after;
        }

        if ($before !== null) {
            $date_query['before'] = $before;
        }

        $this->arguments['date_query'] = [$date_query];

        return $this;
    }

    public function meta(MetaGroup $group): self
    {
        $this->meta_group = $group;

        return $this;
    }

    /**
     * @param callable(MetaGroup): void $callback
     */
    public function where_meta(
        callable $callback,
        string $relation = 'AND'
    ): self {
        $group = MetaGroup::make($relation);

        $callback($group);

        return $this->meta($group);
    }

    public function taxonomy(TaxonomyGroup $group): self
    {
        $this->taxonomy_group = $group;

        return $this;
    }

    /**
     * @param callable(TaxonomyGroup): void $callback
     */
    public function where_taxonomy(
        callable $callback,
        string $relation = 'AND'
    ): self {
        $group = TaxonomyGroup::make($relation);

        $callback($group);

        return $this->taxonomy($group);
    }

    public function ignore_sticky_posts(bool $ignore = true): self
    {
        $this->arguments['ignore_sticky_posts'] = $ignore;

        return $this;
    }

    public function suppress_filters(bool $suppress = false): self
    {
        $this->arguments['suppress_filters'] = $suppress;

        return $this;
    }

    public function no_found_rows(bool $disable_count = true): self
    {
        $this->arguments['no_found_rows'] = $disable_count;

        return $this;
    }

    public function fields(string $fields): self
    {
        if (!in_array($fields, ['all', 'ids', 'id=>parent'], true)) {
            throw new InvalidArgumentException(
                'Fields must be all, ids, or id=>parent.'
            );
        }

        $this->arguments['fields'] = $fields;

        return $this;
    }

    /**
     * Add a low-level WP_Query argument.
     *
     * Use this only for arguments not directly covered by the fluent methods.
     */
    public function argument(string $key, mixed $value): self
    {
        $this->arguments[$key] = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function to_array(): array
    {
        $arguments = $this->arguments;

        if ($this->meta_group !== null && !$this->meta_group->is_empty()) {
            $arguments['meta_query'] = $this->meta_group->to_array();
        }

        if (
            $this->taxonomy_group !== null
            && !$this->taxonomy_group->is_empty()
        ) {
            $arguments['tax_query'] = $this->taxonomy_group->to_array();
        }

        return $arguments;
    }
}