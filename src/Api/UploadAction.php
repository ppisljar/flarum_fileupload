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
use Flarum\Core\Settings\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use League\Flysystem\Exception;
use RuntimeException;
use Tobscure\JsonApi\Document;

class UploadAction extends SerializeResourceAction
{
    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * {@inheritdoc}
     */
    public $serializer = 'Flarum\Api\Serializers\UserSerializer';

    /**
     * {@inheritdoc}
     */
    public $include = [];

    /**
     * {@inheritdoc}
     */
    public $link = [];

    /**
     * {@inheritdoc}
     */
    public $limitMax = 50;

    /**
     * {@inheritdoc}
     */
    public $limit = 20;

    /**
     * {@inheritdoc}
     */
    public $sortFields = [];

    /**
     * {@inheritdoc}
     */
    public $sort;

    protected $settings;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus, SettingsRepository $settings)
    {
        $this->bus = $bus;
        $this->settings = $settings;
    }

    /**
     * @param JsonApiRequest $request
     * @param Document       $document
     *
     * @return \Flarum\Core\Users\User
     */
    protected function data(JsonApiRequest $request, Document $document)
    {
        // TODO: update settings namespace

        // should be loaded from config ... upload folder relative to the flarum base path
        $uploadDir = '/upload/';

        // list of allowed filetypes (empty if all are allowed)
        $allowedTypes = array_filter(explode(',', $this->settings->get('flamure.fileupload.allowed')));

        // list of blocked filetypes (empty if none are blocked)
        $blockedTypes = array_filter(explode(',', $this->settings->get('flamure.fileupload.blocked')));

        // end of config

        $uploadDir = getcwd().$uploadDir;

        $file = $request->http->getUploadedFiles()['file'];
        $id = $request->get('id');
        $user = $request->actor;

        // check if this type of file is allowed
        $ext = explode('.', $file->getClientFilename());
        $ext = strtolower(end($ext));
        if ((count($allowedTypes) > 0 && !in_array($ext, $allowedTypes)) || in_array($ext, $blockedTypes)) {
            throw new RuntimeException('Filetype not allowed');
        }

        // generate correct directory
        // unique id is generated then folder structure is build based on it
        // upload/first_2_letters/second_2_letters/last_2_letters
        // to avoid the need to save in database to preserve filename
        // add _ to begining to make sure its valid folder name
        $uid = uniqid();
        $uid = ['_'.$uid[0].$uid[1], '_'.$uid[2].$uid[3], '_'.$uid[11].$uid[12]];
        $currentPath = $uploadDir;
        if (!is_dir($currentPath)) {
            mkdir($currentPath, 0777, true);
        }

        foreach ($uid as $dir) {
            $currentPath .= "$dir/";
            if (!is_dir($currentPath)) {
                mkdir($currentPath, 0777, true);
            }
        }

        try {
            $file->moveTo($currentPath.$file->getClientFilename());
            $currentPath = str_replace($uploadDir, '', $currentPath);
            $response = '/upload/'.$currentPath.$file->getClientFilename();

            // send output (must be updated)
            return $response;
        } catch (Exception $e) {
            throw new RuntimeException('Upload failed');
        }
    }
}
