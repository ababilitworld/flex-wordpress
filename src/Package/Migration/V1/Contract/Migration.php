<?php 
namespace Ababilithub\FlexWordpress\Package\Migration\V1\Contract;

interface Migration
{
    public function up(): void;
    public function down(): void;
}