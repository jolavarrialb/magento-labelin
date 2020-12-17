<?php

declare(strict_types=1);

namespace Labelin\PitneyBowesShipping\App\Response;

use Exception;
use InvalidArgumentException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;

class FileFactory extends \Magento\Framework\App\Response\Http\FileFactory
{
    /**
     * @inheridoc
     */
    public function create(
        $fileName,
        $content,
        $baseDir = DirectoryList::ROOT,
        $contentType = 'application/octet-stream',
        $contentLength = null
    ) {
        $dir = $this->_filesystem->getDirectoryWrite($baseDir);
        $isFile = false;
        $file = null;
        $fileContent = $this->getFileContent($content);
        if (is_array($content)) {
            if (!isset($content['type']) || !isset($content['value'])) {
                throw new InvalidArgumentException("Invalid arguments. Keys 'type' and 'value' are required.");
            }

            if ($content['type'] == 'filename') {
                $isFile = true;
                $file = $content['value'];
                if (!$dir->isFile($file)) {
                    throw new Exception((string)new \Magento\Framework\Phrase('File not found'));
                }

                $contentLength = $dir->stat($file)['size'];
            }
        }
        $this->_response->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true)
            ->setHeader('Content-Length', $contentLength === null ? strlen($fileContent) : $contentLength, true)
            ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"', true)
            ->setHeader('Last-Modified', date('r'), true);

        if ($content !== null) {
            $this->_response->sendHeaders();
            if ($isFile) {
                $stream = $dir->openFile($file, 'r');
                while (!$stream->eof()) {
                    echo $stream->read(1024);
                }
            } else {
                $dir->writeFile($fileName, $fileContent);
                $file = $fileName;
                $stream = $dir->openFile($fileName, 'r');
                while (!$stream->eof()) {
                    echo $stream->read(1024);
                }
            }

            $stream->close();
            flush();
            if (!empty($content['rm'])) {
                $dir->delete($file);
            }
        }

        return $this->_response;
    }

    /**
     * @param string|array $content
     * @return string|array
     */
    protected function getFileContent($content)
    {
        if (isset($content['type']) && $content['type'] === 'string') {
            return $content['value'];
        }

        return $content;
    }
}
