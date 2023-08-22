<?php

namespace LaraDumps\LaraDumpsLibrary\Config;

use Nette\Schema\{Expect, Schema};

class AppConfigSchema implements ConfigInterface
{
    public function getSchema(): Schema
    {
        return Expect::structure([
            'DS_APP_HOST'                  => Expect::string()->required(),
            'DS_APP_PORT'                  => Expect::int()->default(9191),
            'DS_FILE_HANDLER'              => Expect::string(),
            'DS_PROJECT_PATH'              => Expect::string(),
            'DS_AUTO_CLEAR_ON_PAGE_RELOAD' => Expect::bool()->default(false),
            'DS_AUTO_INVOKE_APP'           => Expect::bool()->default(false),
            'DS_SEND_COLOR_IN_SCREEN'      => Expect::bool()->default(false),
            'DS_SLEEP'                     => Expect::bool()->default(false),
            'DS_PREFERRED_IDE'             => Expect::string()->default(''),
            'DS_RUNNING_IN_TESTS'          => Expect::bool()->default(false),
        ]);
    }

    public function getKey(): string
    {
        return 'app';
    }
}
