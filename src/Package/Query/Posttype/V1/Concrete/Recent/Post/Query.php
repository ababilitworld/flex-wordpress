<?php 
namespace Ababilithub\FlexWordpress\Package\Query\Posttype\V1\Concrete\Recent\Post;

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

use Ababilithub\{
    FlexWordpress\Package\Query\Posttype\V1\Base\Query as BaseQuery
};

class Query extends BaseQuery 
{
    public function init(array $data = []): static
    {
        // Default to recent published posts
        $this->set_post_type('post')
             ->set_post_status('publish')
             ->set_order_by('date', 'DESC');
        
        if (!empty($data)) {
            $this->apply_data($data);
        }
        
        return $this;
    }
    
    protected function apply_data(array $data): void
    {
        if (isset($data['category'])) {
            $this->filter_by_category($data['category']);
        }
        
        if (isset($data['tag'])) {
            $this->filter_by_tag($data['tag']);
        }
        
        if (isset($data['author'])) {
            $this->filter_by_author($data['author']);
        }
    }
    
    public function filter_by_category($category): static
    {
        return $this->add_tax_query('category', $category);
    }
    
    public function filter_by_tag($tag): static
    {
        return $this->add_tax_query('post_tag', $tag);
    }
    
    public function filter_by_author($author): static
    {
        $this->args['author'] = (int) $author;
        return $this;
    }
    
    public function exclude_sticky_posts(): static
    {
        $this->args['ignore_sticky_posts'] = true;
        return $this;
    }
}