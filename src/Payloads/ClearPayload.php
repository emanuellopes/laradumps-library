<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

final class ClearPayload extends Payload
{
    public function type(): string
    {
        return 'clear';
    }

    /** @return array<string> */
    public function content(): array
    {
        return [];
    }

    public function customHandle(): array
    {
        return [];
    }
}
