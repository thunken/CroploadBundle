<?php

namespace Thunken\CroploadBundle\Naming;

use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Oneup\UploaderBundle\Uploader\Naming\UniqidNamer;

class DateChunkNamer extends UniqidNamer implements NamerInterface
{
    public function name(FileInterface $file)
    {
        return sprintf(
            '%s/%s.%s',
            $this->getPath(),
            sha1($file->getBasename()),
            $file->getExtension()
        );
    }

    protected function getPath()
    {
        // Building a time related path YYYY/MM/DD to avoid FS number limits
        $date = new \DateTime();
        return
            $date->format('Y') . '/' .
            $date->format('m') . '/' .
            $date->format('d') . '/';
    }
}
