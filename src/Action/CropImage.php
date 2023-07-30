<?php

/*
 * This file is part of the Osynapsy package.
 *
 * (c) Pietro Celeste <p.celeste@osynapsy.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osynapsy\Bcl4\ImageBox\Action;

use Osynapsy\ImageProcessing\Image;
use Osynapsy\Action\AbstractAction;
use Osynapsy\Http\Response\JsonOsynapsy;

/**
 * Description of CropTrait
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class CropImage extends AbstractAction
{
    public function execute(JsonOsynapsy $Response, $imageUrl, $cropData, $resizeData, $jsOnSuccess)
    {
        try {
            $documentRoot = $_SERVER['DOCUMENT_ROOT'];
            $filename = $this->getFilepathFromUrl($imageUrl);
            $imageHandler = new Image($documentRoot.$filename);
            $this->crop($imageHandler, $cropData);
            if (!empty($resizeData)) {
                $this->resize($imageHandler, $resizeData);
            }
            $newFilename = $this->buildFilename($filename);
            $imageHandler->save($documentRoot.$newFilename);
            $Response->js(sprintf(base64_decode($jsOnSuccess), $newFilename));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function getFilepathFromUrl($imageUrl)
    {
        $urlPart = parse_url($imageUrl);
        return urldecode($urlPart['path']);
    }

    protected function crop($imageHandler, $cropData)
    {
        list($cropWidth, $cropHeight, $cropX, $cropY) = explode(',', base64_decode($cropData));
        $imageHandler->crop($cropX, $cropY, $cropWidth, $cropHeight);
    }

    protected function resize($imageHandler, $resizeData)
    {
        list($newWidth, $newHeight) = explode(',', base64_decode($resizeData));
        $imageHandler->resize($newWidth, $newHeight);
    }

    protected function buildFilename($original)
    {
        $pathinfo = pathinfo($original);
        $path = $pathinfo['dirname'];
        $filename = $pathinfo['filename'];
        $extension = $pathinfo['extension'];
        return $path . '/' . $filename . '.crop.' . $extension;
    }
}
