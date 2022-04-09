<?php
namespace App\File\Writer;

use Josegonzalez\Upload\File\Writer\DefaultWriter;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;

class AppWriter extends DefaultWriter
{
    /**
     * @throws FileNotFoundException
     * @throws FileExistsException
     */
    public function writeFile(FilesystemInterface $filesystem, $file, $path): bool
    {
        $stream = @fopen($file, 'r');
        $config = ['ContentType' => $this->data->getClientMediaType()];
        if ($stream === false) {
            return false;
        }

        $success = false;
        $tempPath = $path . '.temp';
        $this->deletePath($filesystem, $tempPath);
        if ($filesystem->writeStream($tempPath, $stream, $config)) {
            $this->deletePath($filesystem, $path);
            $success = $filesystem->rename($tempPath, $path);
        }
        $this->deletePath($filesystem, $tempPath);
        is_resource($stream) && fclose($stream);

        return $success;
    }
}