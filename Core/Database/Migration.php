<?php

namespace Core\Database;

abstract class Migration
{
    abstract public function up(): void;
    abstract public function down(): void;

    public function __construct()
    {
        $this->up();
    }

    public function __invoke()
    {
        $this->up();
    }
}
