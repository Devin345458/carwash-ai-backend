<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\File;
use App\Model\Table\FilesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Files Controller
 *
 * @property FilesTable $Files
 * @method File[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class FilesController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['thumbnail', 'image']);
    }

    public function media()
    {
        $data = $this->getRequest()->getQueryParams();
        $media = $this->Files->find()->find('search')->where(['created_by_id' => $this->Authentication->getUser()->id]);
        $media = $media->order(['created' => 'desc']);
        $media = $this->paginate($media, ['limit' => $data['pcount']]);
        $this->set(compact('media'));
    }

    public function file($id)
    {
        $file = $this->Files->find()->where(['id' => $id])->firstOrFail();
        $this->set(['file' => $file]);
    }

    public function save()
    {
        $data = $this->getRequest()->getData();
        if (!$data['id']) {
            throw new RecordNotFoundException('No File ID');
        }
        $media = $this->Files->get($data['id']);
        if (!$media) {
            throw new RecordNotFoundException(sprintf('Unable to find File with ID %s', $data['id']));
        }
        $media->company_id = $this->Authentication->getUser()->company_id;
        if (!$this->Files->save($media)) {
            throw new ValidationException($media);
        }
        $this->set(['message' => 'The files information has been updated.']);
    }

    /**
     * Delete a file
     *
     * @return void
     */
    public function delete()
    {
        $items = $this->getRequest()->getData('items');
        if (!$items) {
            throw new NotFoundException();
        }
        $medias = $this->Files->find()->where(['id in' => $items]);
        if (!$medias->count()) {
            throw new RecordNotFoundException('Unable to find files requested');
        }
        $this->Files->deleteMany($medias);
        $this->set(['message' => 'The selected file(s) have been deleted.']);
    }

    /**
     * Upload a file
     *
     * @return void
     */
    public function upload()
    {
        $file = $this->getRequest()->getData('file');
        $this->Files->getBehavior('MyUpload')->setConfig('name.path', $this->Authentication->getIdentityData('id'));
        $dims = getimagesize($file->getStream()->getMetadata('uri'));
        $media = $this->Files->newEntity([
            'name' => $file,
            'width' => $dims[0],
            'height' => $dims[1],
            'collection_name' => 'images'
        ]);

        if (!$this->Files->save($media)) {
            throw new ValidationException($media);
        }
        $this->set([
            'message' => 'The ' . $media->name . ' file has been uploaded.',
            'file_name' => $media->name,
            'file' => $media,
        ]);
    }

    public function thumbnail($id) {
        /** @var File $file */
        $file = $this->Files->find()->where(['id' => $id])->firstOrFail();
        $this->redirect($file->responsive_images['thumbnail']);
    }

    public function image($id, $size = 'thumbnail') {
        /** @var File $file */
        $file = $this->Files->find()->where(['id' => $id])->firstOrFail();
        $this->redirect($file->responsive_images['thumbnail']);
    }
}
