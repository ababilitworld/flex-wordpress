<?php
namespace Ababilitworld\FlexWordpress\Package\Pagination\Contract;

interface Pagination 
{
    public function init($data);
    public function paginate();
    public function pagination_links();
    public function render();
}