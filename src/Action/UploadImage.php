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

use Osynapsy\Action\AbstractAction;
use Osynapsy\Http\Response\JsonOsynapsy;
use Osynapsy\Helper\Upload;

/**
 * Description of CropTrait
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class UploadImage extends AbstractAction
{
    public function execute(JsonOsynapsy $Response, $componentId, $repoPath = '/upload')
    {
        try {
            $filename = (new Upload($componentId, $repoPath))->save();
            $Response->js(sprintf("document.getElementById('%s').value = '%s'", $componentId, $filename));
            $Response->js(sprintf("Osynapsy.refreshComponents(['%s_box'], function(){ BclImageBox.init(); });", $componentId));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
