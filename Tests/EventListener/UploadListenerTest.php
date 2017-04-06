<?php

namespace Thunken\Tests\Naming;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Oneup\UploaderBundle\Uploader\Response\EmptyResponse;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Thunken\CroploadBundle\EventListener\UploadListener;

class UploadListenerTest extends TestCase
{
    public function testOnUpload()
    {
        $file = new File( __DIR__ . '/../Fixtures/Images/Screenshot from 2017-04-06 14-51-47.png');

        $event = new PostPersistEvent($file, new EmptyResponse(), new Request(), "", []);

        $om = $this
            ->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $listener = new UploadListener($om, '/tmp/web', '/tmp/');
        $listener->onUpload($event);

        $response = $event->getResponse();
        $this->assertEquals(true, isset($response['webPath']) &&  !empty($response['webPath']));
        $this->assertEquals(true, isset($response['fileName']) &&  !empty($response['fileName']));
    }
}