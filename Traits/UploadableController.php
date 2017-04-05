<?php

namespace Thunken\CroploadBundle\Traits;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 *
 * Use this trait with whichever controller you need to handle the file modifications according to the cropper coordinates
 * Note the following form parameters names:
 * - cropOffsetX
 * - cropOffsetY
 * - cropWidth
 * - cropHeight
 *
 * Class UploadableController
 * @package Thunken\CroploadBundle\Traits
 */
trait UploadableController
{
    protected function handleIllustration($webPath, $parameters)
    {
        $container = $this->container;

        if ($parameters['cropOffsetX'] < 0) {
            $parameters['cropOffsetX'] = 0;
        }

        if ($parameters['cropOffsetY'] < 0) {
            $parameters['cropOffsetY'] = 0;
        }

        if (($parameters['cropWidth'] <= 0) || ($parameters['cropHeight'] <= 0)) {
            return false;
        }

        /** @var Imagine $imagine */
        $imagine = $container->get('liip_imagine');
        $image = $imagine->open($webPath);
        $image
            ->crop(
                new Point($parameters['cropOffsetX'], $parameters['cropOffsetY']),
                new Box($parameters['cropWidth'], $parameters['cropHeight']))
            ->save($webPath);

        return true;
    }
}
