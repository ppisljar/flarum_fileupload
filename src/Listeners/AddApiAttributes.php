<?php 
/*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fileupload\Listeners;

use Flarum\Events\RegisterApiRoutes;
use Flarum\Api\Actions\Forum;
use Flarum\Api\Actions\Discussions;

class AddApiAttributes
{
    public function subscribe($events)
    {

        $events->listen(RegisterApiRoutes::class, [$this, 'addRoutes']);
    }

    public function addRoutes(RegisterApiRoutes $event)
    {
        $event->post('/upload', 'fileupload.upload', 'fileupload\Api\UploadAction');
        //$event->post('/tags/order', 'tags.order', 'Flarum\Tags\Api\OrderAction');
        //$event->patch('/tags/{id}', 'tags.update', 'Flarum\Tags\Api\UpdateAction');
        //$event->delete('/tags/{id}', 'tags.delete', 'Flarum\Tags\Api\DeleteAction');
    }
}
