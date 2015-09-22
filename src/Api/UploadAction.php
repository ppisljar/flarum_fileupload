<?php
/*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fileupload\Api;

use Flarum\Api\Actions\SerializeResourceAction;
use Flarum\Api\JsonApiRequest;
use Illuminate\Contracts\Bus\Dispatcher;
use League\Flysystem\Exception;
use Tobscure\JsonApi\Document;
use RuntimeException;

class UploadAction extends SerializeResourceAction
{
    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @inheritdoc
     */
    public $serializer = 'Flarum\Api\Serializers\Serializer';

    /**
     * @inheritdoc
     */
    public $include = [];

    /**
     * @inheritdoc
     */
    public $link = [];

    /**
     * @inheritdoc
     */
    public $limitMax = 50;

    /**
     * @inheritdoc
     */
    public $limit = 20;

    /**
     * @inheritdoc
     */
    public $sortFields = [];

    /**
     * @inheritdoc
     */
    public $sort;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     *
     *
     * @param JsonApiRequest $request
     * @param Document $document
     * @return \Flarum\Core\Users\User
     */
    protected function data(JsonApiRequest $request, Document $document)
    {
        // should be loaded from config
        $uploadDir = '/var/www/_futurebox/upload/';

        // list of allowed filetypes (null if all are allowed)
        $allowedTypes = null;

        // list of blocked filetypes (null if none are blocked)
        $blockedTypes = ['php', 'js', 'html', 'doc'];


        //$response = new stdClass();

        $file = $request->http->getUploadedFiles()['file'];
        $id = $request->get('id');
        $user = $request->actor;

        // check if this type of file is allowed
        $ext = explode('.', $file->getClientFilename());
        $ext = strtolower(end($ext));
        echo "ext: $ext";
        if (($allowedTypes && !in_array($ext, $allowedTypes)) || ($blockedTypes && in_array($ext, $blockedTypes))) {
            //$response->error = 'ERROR';
            return new RuntimeException("my new error");
        }

        // generate correct directory

        $file->moveTo($uploadDir.$file->getClientFilename());


        $response  = "http://futurebox.tech/upload/".$file->getClientFilename();

        // send output (must be updated)
        return $response;

    }
}
