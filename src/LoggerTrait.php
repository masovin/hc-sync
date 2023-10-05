<?php

namespace HcSync;

trait LoggerTrait
{
    public function info($message = null, $event = null)
    {
        echo "[INFO] $event $message \n";
    }

    public function success($message = null, $event = null)
    {
        echo "[SUCCESS] $event $message \n";
    }

    public function warning($message = null, $event = null)
    {
        echo "[WARNING] $event $message \n";
    }
    public function error($message = null, $event = null)
    {
        echo "[ERROR] $event $message \n";
    }
}
