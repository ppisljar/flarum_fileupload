<?php

namespace fileupload\Listeners;

use Flarum\Events\BuildClientView;
use Flarum\Events\RegisterLocales;
use Illuminate\Contracts\Events\Dispatcher;

class AddClientAssets
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(RegisterLocales::class, [$this, 'addLocale']);
        $events->listen(BuildClientView::class, [$this, 'addAssets']);
    }

    public function addLocale(RegisterLocales $event)
    {
        $event->addTranslations('en', __DIR__.'/../../locale/en.yml');
    }

    public function addAssets(BuildClientView $event)
    {
        $event->forumAssets([
            __DIR__.'/../../js/forum/dist/extension.js',
            __DIR__.'/../../less/forum/extension.less',
        ]);

        $event->forumBootstrapper('fileupload/main');

        $event->forumTranslations([
            'fileupload.uploadfailed',
            'fileupload.invalidtype',
            'fileupload.uploadnewfile',
        ]);

        $event->adminAssets([
            __DIR__.'/../../js/admin/dist/extension.js',
            __DIR__.'/../../less/admin/extension.less',
        ]);

        $event->adminBootstrapper('fileupload/main');

        $event->adminTranslations([]);
    }
}
