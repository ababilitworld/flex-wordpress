<?php
namespace Ababilithub\FlexWordpress\Package\Query\Taxonomy\V1\Concrete\Default;

use Ababilithub\{
    FlexWordpress\Package\Query\Taxonomy\V1\Base\Query as BaseQuery
};

class Query extends BaseQuery
{
    public function init(array $data = []): static
    {
        $this->set_custom_args($data);   
        return $this;
    }
}