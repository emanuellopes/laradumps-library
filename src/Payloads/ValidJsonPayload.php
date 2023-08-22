<?php

namespace LaraDumps\LaraDumpsLibrary\Payloads;

final class ValidJsonPayload extends Payload
{
    public function type(): string
    {
        return 'json_validate';
    }
}
