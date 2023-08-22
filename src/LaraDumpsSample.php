<?php

namespace LaraDumps\LaraDumpsLibrary;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class LaraDumpsSample extends AbstractLaraDumps
{
    public function loadConfig(): void
    {
        try {
            $data = (array)Yaml::parseFile(dirname(__DIR__) . '/sample-config.yaml');
            $this->loadConfigFile($data);
        } catch (ParseException $e) {
            error_log($e->getMessage());
        }
    }
}
