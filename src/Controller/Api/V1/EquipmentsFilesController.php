<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\EquipmentsFile;
use App\Model\Table\EquipmentsFilesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\Query;

/**
 * EquipmentsFiles Controller
 *
 * @property EquipmentsFilesTable $EquipmentsFiles
 *
 * @method EquipmentsFile[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class EquipmentsFilesController extends AppController
{
    public function media()
    {
        $media = $this->EquipmentsFiles->Files->find()->find('search')->matching('Equipments', function (Query $query) {
            return $query->where(['Equipments.id' => $this->getRequest()->getQuery('equipment_id')]);
        });
        $media = $media->order(['Files.created' => 'desc']);
        $media = $this->paginate($media, ['limit' => $this->getRequest()->getQuery('pcount')]);
        $media = $media->toArray();
        $this->set(compact('media'));
    }

    /**
     * Delete a file from a equipment
     *
     * @return void
     */
    public function delete()
    {
        $items = $this->getRequest()->getData('items');
        if (!$items) {
            throw new NotFoundException();
        }
        $medias = $this->EquipmentsFiles->Files->find()->where(['id in' => $items]);
        if (!$medias->count()) {
            throw new RecordNotFoundException('Unable to find files requested');
        }
        $this->EquipmentsFiles->Files->deleteMany($medias);

        $equipmentFiles = $this->EquipmentsFiles->find()->where([
            'file_id in' => $items,
            'equipment_id' => $this->getRequest()->getData('equipment_id')
        ]);
        $this->EquipmentsFiles->deleteMany($equipmentFiles);


        $this->set(['message' => 'The selected file(s) have been deleted.']);
    }

    /**
     * Upload a file to a piece of equipment
     *
     * @return void
     */
    public function upload()
    {
        $file = $this->getRequest()->getData('file');
        $this->EquipmentsFiles->Files->getBehavior('MyUpload')->setConfig('name.path', 'equipment/' . $this->getRequest()->getData('equipment_id'));
        $dims = getimagesize($file->getStream()->getMetadata('uri'));
        $media = $this->EquipmentsFiles->Files->newEntity([
            'name' => $file,
            'width' => $dims[0],
            'height' => $dims[1],
            'collection_name' => 'images'
        ]);

        if (!$this->EquipmentsFiles->Files->save($media)) {
            throw new ValidationException($media);
        }

        $this->EquipmentsFiles->save($this->EquipmentsFiles->newEntity([
            'file_id' => $media->id,
            'equipment_id' => $this->getRequest()->getData('equipment_id')
        ]));

        $this->set([
            'message' => 'The ' . $media->name . ' file has been uploaded.',
            'file_name' => $media->name,
            'file' => $media,
        ]);
    }
}
