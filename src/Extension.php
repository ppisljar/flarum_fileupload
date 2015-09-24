<?php

namespace fileupload;

use Flarum\Support\Extension as BaseExtension;
use Illuminate\Events\Dispatcher;

class Extension extends BaseExtension
{
    public function listen(Dispatcher $events)
    {
        $events->subscribe('fileupload\Listeners\AddClientAssets');
        $events->subscribe('fileupload\Listeners\AddApiAttributes');
    }
}
