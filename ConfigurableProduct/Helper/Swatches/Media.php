<?php

declare(strict_types=1);

namespace Labelin\ConfigurableProduct\Helper\Swatches;

use Magento\Swatches\Helper\Media as MagentoMedia;
use Magento\Swatches\Model\Swatch;

class Media extends MagentoMedia
{
    /**
     * @inheritDoc
     */
    public function getSwatchAttributeImage($swatchType, $file): string
    {
        $generationPath = $swatchType . '/' . $this->getFolderNameSize($swatchType) . $file;

        if ($swatchType === Swatch::SWATCH_IMAGE_NAME) {
            $generationPath = $file;
        }

        $absoluteImagePath = $this->mediaDirectory
            ->getAbsolutePath($this->getSwatchMediaPath() . '/' . $generationPath);

        if (!$this->getFileValidator()->isValid($absoluteImagePath)) {
            try {
                $this->generateSwatchVariations($file);
            } catch (\Exception $e) {
                return '';
            }
        }

        return $this->getSwatchMediaUrl() . '/' . $generationPath;
    }

    protected function getFileValidator(): \Zend_Validate_File_Exists
    {
        return new \Zend_Validate_File_Exists();
    }
}
