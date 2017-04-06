<?php

namespace Thunken\Tests\Naming;

use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Symfony\Component\HttpFoundation\File\File;
use Thunken\CroploadBundle\Naming\DateChunkNamer;
use PHPUnit\Framework\TestCase;

class DateChunkNamerTest extends TestCase
{

    /**
     * Assert that the namer returns a path containing a date as yyyy/mm/dd/<file_name_hash>.<file_extension>
     * example: 2017/04/06/464e752669a8ff9da31702619c0bc6565e27f9b5.png
     */
    public function testName()
    {
        $namer = new DateChunkNamer();
        $file = new File( __DIR__ . '/../Fixtures/Images/Screenshot from 2017-04-06 14-51-47.png');
        $extension = $file->getExtension();
        $result = $namer->name(new FilesystemFile($file));

        $expression = '/^[\d]{4}\/[\d]{2}\/[\d]{2}\/[\w\W\d]{40}\.' . $extension . '$/';
        $this->assertRegExp($expression, $result);
    }
}