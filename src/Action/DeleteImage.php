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

/**
 * Description of CropTrait
 *
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class DeleteImage extends AbstractAction
{
    public function execute($componentId, $filename)
    {
        try {
            $fullFilePath = $_SERVER['DOCUMENT_ROOT'].$filename;
            if (!is_file($fullFilePath)) {
                throw new \Exception(sprintf('Il file %s non esiste. Impossibile eliminarlo', $filename));
            }
            @unlink($fullFilePath);
            $this->getController()->js(sprintf("document.getElementById('%s').value = ''", $componentId));
            $this->getController()->js(sprintf("document.getElementById('_%s').value = ''", $componentId));
            $this->getController()->js(sprintf("Osynapsy.refreshComponents(['%s_box'], function() { BclImageBox.init(); });", $componentId));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
