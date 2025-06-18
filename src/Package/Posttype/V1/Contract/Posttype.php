<?php 
namespace Ababilithub\FlexWordpress\Package\Posttype\V1\Contract;

interface Posttype
{
    public function get_slug(): string;
    public function register_post_type(): void;
     
}