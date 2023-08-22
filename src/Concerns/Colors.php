<?php

namespace LaraDumps\LaraDumpsLibrary\Concerns;

use LaraDumps\LaraDumpsLibrary\AbstractLaraDumps;

trait Colors
{
    public function danger(): AbstractLaraDumps
    {
        //        if ($this->configuration->get('DS_SEND_COLOR_IN_SCREEN')) {
        //            return $this->toScreen('danger');
        //        }

        return $this->color('red');
    }

    public function dark(): AbstractLaraDumps
    {
        return $this->color('black');
    }

    public function warning(): AbstractLaraDumps
    {
        //        if ($this->configuration->get('DS_SEND_COLOR_IN_SCREEN')) {
        //            return $this->toScreen('warning');
        //        }

        return $this->color('orange');
    }

    public function success(): AbstractLaraDumps
    {
        //        if ($this->configuration->get('DS_SEND_COLOR_IN_SCREEN')) {
        //            return $this->toScreen('success');
        //        }

        return $this->color('green');
    }

    public function info(): AbstractLaraDumps
    {
        //        if ($this->configuration->get('DS_SEND_COLOR_IN_SCREEN')) {
        //            return $this->toScreen('info');
        //        }

        return $this->color('blue');
    }
}
