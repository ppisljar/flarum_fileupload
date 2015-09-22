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
    public $serializer = 'Flarum\Api\Serializers\UserSerializer';

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

        $file = $request->http->getUploadedFiles()['file'];
        $id = $request->get('id');
        $user = $request->actor;

        // check if this type of file is allowed
        $ext = explode('.', $file->getClientFilename());
        $ext = strtolower(end($ext));
        if (($allowedTypes && !in_array($ext, $allowedTypes)) || ($blockedTypes && in_array($ext, $blockedTypes))) {
            throw new RuntimeException("Filetype not allowed");
        }

        // generate correct directory
        // unique id is generated then folder structure is build based on it
        // upload/first_2_letters/second_2_letters/last_2_letters
        // to avoid the need to save in database to preserve filename
        $uid = uniqid();
        $uid = array($uid[0].$uid[1], $uid[2].$uid[3], $uid[11].$uid[12]);
        $currentPath = $uploadDir;
        foreach ($uid as $dir) {
            $currentPath .= "$dir/";
            if (!is_dir($currentPath)) {
                mkdir($currentPath);
                chmod($currentPath, 775);
            }
        }

        $file->moveTo($currentPath.$file->getClientFilename());

        $currentPath = str_replace($uploadDir, "", $currentPath);
        $response  = "http://futurebox.tech/upload/".$currentPath.$file->getClientFilename();

        // send output (must be updated)
        return $response;

    }
}
