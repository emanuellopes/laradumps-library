<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use LaraDumps\LaraDumpsLibrary\Actions\Table;
use League\Config\ConfigurationInterface;

final class TablePayload extends Payload
{
    public function __construct(
        ConfigurationInterface $config,
        private mixed $data = [],
        private string $name = '',
    ) {
        if (empty($this->name)) {
            $this->name = 'Table';
        }
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'table';
    }

    public function content(): array
    {
        return (new Table($this->data, $this->name))->make();
    }
}
