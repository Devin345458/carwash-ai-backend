<?php

namespace App\Model\Entity\Traits;

use Aws\S3\S3Client;
use Cake\Core\Configure;
use Cake\Log\Log;

trait UploadTrait
{
    protected function _getS3url()
    {
        if ($this->isNew()) {
            return null;
        }
        if ($this->get('photo') && is_scalar($this->get('photo'))) {
            $credentials = Configure::read('aws-s3-secret');
            $client = S3Client::factory(
                [
                'credentials' => [
                    'key'    => $credentials['awsAccessKey'],
                    'secret' => $credentials['awsSecretKey'],
                ],
                'region' => 'us-east-1',
                'version' => 'latest',
                ]
            );
            $cmd = $client->getCommand(
                'GetObject',
                [
                'Bucket' => Configure::read('bucket'),
                'Key' => $this->_registryAlias .  '/' . $this->get('photo'),
                ]
            );

            $request = $client->createPresignedRequest($cmd, '+1 hour');

            return (string)$request->getUri();
        } else {
            return null;
        }
    }

    protected function _getThumbnail()
    {
        if ($this->isNew()) {
            return null;
        }
        if ($this->get('photo') && is_scalar($this->get('photo'))) {
            $credentials = Configure::read('aws-s3-secret');
            $client = S3Client::factory(
                [
                'credentials' => [
                    'key'    => $credentials['awsAccessKey'],
                    'secret' => $credentials['awsSecretKey'],
                ],
                'region' => 'us-east-1',
                'version' => 'latest',
                ]
            );
            $cmd = $client->getCommand(
                'GetObject',
                [
                'Bucket' => Configure::read('bucket'),
                'Key' => $this->_registryAlias . '/thumbnail-' . $this->get('photo'),
                ]
            );

            $request = $client->createPresignedRequest($cmd, '+1 hour');

            return (string)$request->getUri();
        } else {
            return null;
        }
    }
}
