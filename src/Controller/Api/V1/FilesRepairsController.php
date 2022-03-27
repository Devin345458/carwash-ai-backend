<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\EquipmentsFile;
use App\Model\Table\EquipmentsFilesTable;
use App\Model\Table\FilesRepairsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\Query;

/**
 * EquipmentsFiles Controller
 *
 * @property FilesRepairsTable $FilesRepairs
 *
 * @method FilesRepairsTable[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilesRepairsController extends AppController
{
    public function media()
    {
        $media = $this->FilesRepairs->Files->find()->find('search')->matching('Repairs', function (Query $query) {
            return $query->where(['Repairs.id' => $this->getRequest()->getQuery('repair_id')]);
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
        $medias = $this->FilesRepairs->Files->find()->where(['id in' => $items]);
        if (!$medias->count()) {
            throw new RecordNotFoundException('Unable to find files requested');
        }
        $this->FilesRepairs->Files->deleteMany($medias);

        $repairFiles = $this->FilesRepairs->find()->where([
            'file_id in' => $items,
            'repair_id' => $this->getRequest()->getData('repair_id')
        ]);
        $this->FilesRepairs->deleteMany($repairFiles);


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
        $this->FilesRepairs->Files->getBehavior('MyUpload')->setConfig('name.path', 'repair/' . $this->getRequest()->getData('repair_id'));
        $dims = getimagesize($file->getStream()->getMetadata('uri'));
        $media = $this->FilesRepairs->Files->newEntity([
            'name' => $file,
            'width' => $dims[0],
            'height' => $dims[1],
            'collection_name' => 'images'
        ]);

        if (!$this->FilesRepairs->Files->save($media)) {
            throw new ValidationException($media);
        }

        $count = $this->FilesRepairs->find()
            ->where(['repair_id' => $this->getRequest()->getData('repair_id')])
            ->count();

        $this->FilesRepairs->save($this->FilesRepairs->newEntity([
            'file_id' => $media->id,
            'cover' => $count === 0,
            'repair_id' => $this->getRequest()->getData('repair_id')
        ]));

        $this->set([
            'message' => 'The ' . $media->name . ' file has been uploaded.',
            'file_name' => $media->name,
            'file' => $media,
        ]);
    }
}
