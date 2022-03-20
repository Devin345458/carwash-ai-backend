<?php
namespace App\Model\Entity;

use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * Photo Entity
 *
 * @property int $id
 * @property string $name
 * @property int $size
 * @property string $type
 * @property int $width
 * @property int $height
 * @property string $dir
 * @property string $collection_name
 * @property string $s3_url
 * @property array $responsive_images
 */
class File extends Entity
{
    public $dimensions = [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
        ],
        'medium' => [
            'width' => 300,
            'height' => 300,
        ],
        'medium_large' => [
            'width' => 768,
            'height' => 768,
        ],
        'large' => [
            'width' => 1024,
            'height' => 1024,
        ],
    ];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => true,
        'name' => true,
        'size' => true,
        'type' => true,
        'width' => true,
        'height' => true,
        'folder' => true,
        'collection_name' => true,
    ];

    protected function _generateS3Connection(string $key)
    {
        $client = S3Client::factory(
            [
                'credentials' => [
                    'key'    => env('S3_KEY'),
                    'secret' => env('S3_SECRET'),
                ],
                'use_path_style_endpoint' => true,
                'endpoint' => env('S3_URL'),
                'region' => 'us-east-1',
                'version' => 'latest',
            ]
        );
        $cmd = $client->getCommand(
            'GetObject',
            [
                'Bucket' => env('S3_BUCKET'),
                'Key' => $key,
            ]
        );

        return $client->createPresignedRequest($cmd, '+1 hour');
    }

    /**
     * @return string|null
     */
    protected function _getS3url()
    {
        if ($this->isNew()) {
            return null;
        }
        if ($this->get('name') && is_scalar($this->get('name'))) {
            return env('S3_PUBLIC_URL') . '/' . env('S3_BUCKET') . '/' . $this->get('name');
        } else {
            return null;
        }
    }

    /**
     * @return array|null
     */
    protected function _getResponsiveImages()
    {
        if ($this->isNew()) {
            return null;
        }
        if ($this->get('name') && is_scalar($this->get('name'))) {
            $responsive = [];
            $extension = pathinfo($this->get('name'), PATHINFO_EXTENSION);
            foreach ($this->dimensions as $name => $dimensions) {
                try {
                    $responsive[$name] = env('S3_PUBLIC_URL') . '/' . env('S3_BUCKET') . '/' . $this->dir . '/' . str_replace('.' . $extension, '', $this->get('name')) . '_' . $name . '.' . $extension;
                } catch (\Exception $exception) {
                    $responsive[$name] = false;
                }
            }

            return $responsive;
        } else {
            return null;
        }
    }

    protected $_virtual = ['responsive_images'];
}
