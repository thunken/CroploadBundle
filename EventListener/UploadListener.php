<?php

namespace Thunken\CroploadBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\File;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    /** @var string */
    private $webDir = null;

    /** @var string */
    private $uploadRootDir = null;

    public function __construct(ObjectManager $om, $webDir, $uploadRootDir)
    {
        $this->om = $om;
        $this->webDir = $webDir;
        $this->uploadRootDir = $uploadRootDir;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
        /** @var File $file */
        $file = $event->getFile();

        $fileName = $file->getFilename();
        $webPath = sprintf('%s/%s', $file->getPath(), $fileName);

        if (!substr($webPath, 0, strlen($this->uploadRootDir)) == $this->uploadRootDir) {
            $message = 'Is file web path misconfigured?';
            $message = sprintf(
                $message . ' %s should be found in the beginning of %s but wasn\'t',
                $this->uploadRootDir,
                $webPath
            );
            throw new UploadException($message);
        }

        $webPath = substr($webPath, strlen($this->uploadRootDir));
        $webPath = sprintf('%s/%s', $this->webDir, ltrim($webPath, '/'));

        $response['webPath'] = $webPath;
        $response['fileName'] = $fileName;
    }
}
