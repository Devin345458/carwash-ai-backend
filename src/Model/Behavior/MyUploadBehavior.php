<?php
/**
 * Created by PhpStorm.
 * User: Devinhollister-graham
 * Date: 2019-02-02
 * Time: 15:38
 */

namespace App\Model\Behavior;

use Aws\S3\S3Client;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Database\Type;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use Josegonzalez\Upload\Model\Behavior\UploadBehavior;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class MyUploadBehavior extends UploadBehavior
{
    /**
     * Initialize hook
     *
     * @param  array $config The config for this behavior.
     * @return void
     */
    public function initialize(array $config): void
    {

        $client = S3Client::factory([
            'credentials' => [
                'key'    => env('S3_KEY'),
                'secret' =>env('S3_SECRET'),
            ],
            'use_path_style_endpoint' => true,
            'endpoint' => env('S3_URL'),
            'region' => 'us-east-1',
            'version' => 'latest',
        ]);
        $adapter = new AwsS3Adapter(
            $client,
            env('S3_BUCKET')
        );

        $default_settings =  [
            'path' => '',
            // Ensure the default filesystem writer writes using
            // our S3 adapter
            'filesystem' => [
                'adapter' => $adapter,
            ],
            'nameCallback' => function ($table, $entity, $file, $field, $settings) {
                $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                return strtolower(str_replace('.' . $extension, '', $file->getClientFilename())) . '_' . rand() . '.' . $extension;
            },
            // This can also be in a class that implements
            // the TransformerInterface or any callable type.
            'transformer' => function (RepositoryInterface $table, EntityInterface $entity, $file, $field, $settings, $fileName) {
                $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($file->getStream()->getMetadata('uri'));
                $results = [];
                $results[$file->getStream()->getMetadata('uri')] = str_replace('.' . $extension, '', $fileName) . '.' . $extension;

                if ($entity->type === 'files') return $results;
                foreach ($entity->dimensions as $name => $dims) {
                    // Store the thumbnail in a temporary file
                    $tmp = tempnam(sys_get_temp_dir(), $name) . '.' . $extension;
                    // Use the Imagine library to DO THE THING
                    $size = new Box($dims['width'], $dims['height']);
                    $mode = ImageInterface::THUMBNAIL_INSET;
                    $imagine = new Imagine();

                    // Save that modified file to our temp file
                    $imagine = $imagine->open($file->getStream()->getMetadata('uri'));
                    if ($extension !== 'svg') {
                        $imagine = $imagine->thumbnail($size, $mode);
                    }
                    $imagine->save($tmp);
                    $optimizerChain->optimize($tmp);
                    $results[$tmp] =  str_replace('.' . $extension, '', $fileName) . '_' . $name . '.' . $extension;
                    // Now return the original *and* the thumbnail
                }

                return $results;
            },
            'restoreValueOnFailure' => true,
            'deleteCallback' => function ($path, $entity, $field, $settings) {
                // When deleting the entity, both the original and the thumbnail will be removed
                // when keepFilesOnDelete is set to false
                $results = [$path . '/' . $entity->{$field}];
                foreach ($entity->dimensions as $name => $dims) {
                    $results[] = $path . '/' . $entity->{$field} . '_' . $name;
                }

                return $results;
            },
            'keepFilesOnDelete' => false,
        ];

        $configs = [];

        foreach ($config as $field => $settings) {
            if (is_int($field)) {
                $configs[$settings] = [];
            } else {
                $configs[$field] = array_merge($default_settings, $settings);
            }
        }

        $this->setConfig($configs);
        $this->setConfig('className', null);

        Type::map('upload.file', 'Josegonzalez\Upload\Database\Type\FileType');
        $schema = Cache::read('UploadSchema' . $this->_table->getAlias());
        if (!$schema) {
            $schema = $this->_table->getSchema();
            foreach (array_keys($this->getConfig()) as $field) {
                $schema->setColumnType($field, 'upload.file');
            }
            Cache::write('UploadSchema' . $this->_table->getAlias(), $schema);
        }
        $this->_table->setSchema($schema);
    }
}
