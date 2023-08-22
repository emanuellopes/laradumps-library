<?php

namespace LaraDumps\LaraDumpsLibrary\Config;

use Nette\Schema\Schema;

interface ConfigInterface
{
    public function getSchema(): Schema;

    public function getKey(): string;
}
