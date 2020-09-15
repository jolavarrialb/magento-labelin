<?php

declare(strict_types=1);

namespace Labelin\Sales\Model\Product\Option\Type\Artwork;

use Magento\Catalog\Model\Product\Exception as ProductException;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Type\File\ValidatorFile;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;

class ArtworkUploadValidator extends ValidatorFile
{
    protected const FORM_FILE_ARTWORK_ID = 'artwork';

    /** @var Random|mixed */
    protected $random;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Size $fileSize,
        \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory,
        \Magento\Framework\Validator\File\IsImage $isImageValidator,
        Random $random = null
    ) {
        $this->random = $random
            ?? ObjectManager::getInstance()->get(Random::class);
        parent::__construct($scopeConfig, $filesystem, $fileSize, $httpFactory, $isImageValidator, $random);
    }

    /**
     * @param DataObject $processingParams
     * @param Option $option
     * @return array
     */
    public function validate($processingParams, $option): array
    {
        $this->product = $processingParams->getProduct();
        $upload = $this->httpFactory->create();
        $file = static::FORM_FILE_ARTWORK_ID;
        try {
            $runValidation = $option->getIsRequire() || $upload->isUploaded($file);
            if (!$runValidation) {
                throw new \Magento\Framework\Validator\Exception(
                    __(
                        'The validation failed. '
                        . 'Make sure the required options are entered and the file is uploaded, then try again.'
                    )
                );
            }

            $fileInfo = $upload->getFileInfo($file)[$file];
            $fileInfo['title'] = $fileInfo['name'];
        } catch (\Magento\Framework\Validator\Exception $e) {
            throw $e;
        } catch (\Exception $e) {
            // when file exceeds the upload_max_filesize, $_FILES is empty
            if ($this->validateContentLength()) {
                $value = $this->fileSize->getMaxFileSizeInMb();
                throw new LocalizedException(
                    __(
                        "The file was too big and couldn't be uploaded. "
                        . "Use a file smaller than %1 MBs and try to upload again.",
                        $value
                    )
                );
            } else {
                throw new ProductException(__("The required option wasn't entered. Enter the option and try again."));
            }
        }

        /**
         * Option Validations
         */
        $upload = $this->buildImageValidator($upload, $option);

        /**
         * Upload process
         */
        $this->initFilesystem();
        $userValue = [];

        if ($upload->isUploaded($file) && $upload->isValid($file)) {
            $fileName = \Magento\MediaStorage\Model\File\Uploader::getCorrectFileName($fileInfo['name']);
            $dispersion = \Magento\MediaStorage\Model\File\Uploader::getDispersionPath($fileName);

            $filePath = $dispersion;

            $tmpDirectory = $this->filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
            $fileHash = md5($tmpDirectory->readFile($tmpDirectory->getRelativePath($fileInfo['tmp_name'])));
            $fileRandomName = $this->random->getRandomString(32);
            $filePath .= '/' . $fileRandomName;
            $fileFullPath = $this->mediaDirectory->getAbsolutePath($this->quotePath . $filePath);

            $upload->addFilter(new \Zend_Filter_File_Rename(['target' => $fileFullPath, 'overwrite' => true]));

            if ($this->product !== null) {
                $this->product->getTypeInstance()->addFileQueue(
                    [
                        'operation' => 'receive_uploaded_file',
                        'src_name' => $file,
                        'dst_name' => $fileFullPath,
                        'uploader' => $upload,
                        'option' => $this,
                    ]
                );
            }

            $_width = 0;
            $_height = 0;

            if ($tmpDirectory->isReadable($tmpDirectory->getRelativePath($fileInfo['tmp_name']))) {
                if (filesize($fileInfo['tmp_name'])) {
                    if ($this->isImageValidator->isValid($fileInfo['tmp_name'])) {
                        $imageSize = getimagesize($fileInfo['tmp_name']);
                    }
                } else {
                    throw new LocalizedException(__('The file is empty. Select another file and try again.'));
                }

                if (!empty($imageSize)) {
                    $_width = $imageSize[0];
                    $_height = $imageSize[1];
                }
            }

            $userValue = [
                'type' => $fileInfo['type'],
                'title' => $fileInfo['name'],
                'quote_path' => $this->quotePath . $filePath,
                'order_path' => $this->orderPath . $filePath,
                'fullpath' => $fileFullPath,
                'size' => $fileInfo['size'],
                'width' => $_width,
                'height' => $_height,
                'secret_key' => substr($fileHash, 0, 20),
            ];
        } elseif ($upload->getErrors()) {
            $errors = $this->getValidatorErrors($upload->getErrors(), $fileInfo, $option);

            if (count($errors) > 0) {
                throw new LocalizedException(__(implode("\n", $errors)));
            }
        } else {
            throw new LocalizedException(
                __("The product's required option(s) weren't entered. Make sure the options are entered and try again.")
            );
        }
        return $userValue;
    }
}
