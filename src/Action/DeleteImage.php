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

/**
 * Description of CropTrait
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class DeleteImage extends AbstractAction
{
    public function execute(JsonOsynapsy $Response, $componentId, $filename)
    {
        try {
            $fullFilePath = $_SERVER['DOCUMENT_ROOT'].$filename;
            if (!is_file($fullFilePath)) {
                throw new \Exception(sprintf('Il file %s non esiste. Impossibile eliminarlo', $filename));
            }
            @unlink($fullFilePath);
            $Response->js(sprintf("document.getElementById('%s').value = ''", $componentId));
            $Response->js(sprintf("document.getElementById('_%s').value = ''", $componentId));
            $Response->js(sprintf("Osynapsy.refreshComponents(['%s_box'], function() { BclImageBox.init(); });", $componentId));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
