<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

use LaraDumps\LaraDumpsLibrary\Support\Dumper;
use League\Config\ConfigurationInterface;

class TableV2Payload extends Payload
{
    public function __construct(
        readonly ConfigurationInterface $config,
        protected array $values,
        protected string $headerStyle = '',
        protected string $label = 'Table',
    ) {
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'table_v2';
    }

    public function content(): array
    {
        $values = array_map(static function ($value) {
            return Dumper::dump($value);
        }, $this->values);

        return [
            'values'      => $values,
            'headerStyle' => $this->headerStyle,
            'label'       => $this->label,
        ];
    }
}
