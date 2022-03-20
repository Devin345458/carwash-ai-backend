<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class RenameForPhotoIdToFileId extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('equipments');
        $table->renameColumn('photo_id', 'file_id');
        $table->update();

        $table = $this->table('suppliers');
        $table->renameColumn('photo_id', 'file_id');
        $table->update();

        $table = $this->table('items');
        $table->renameColumn('photo_id', 'file_id');
        $table->update();

        $table = $this->table('stores');
        $table->renameColumn('photo_id', 'file_id');
        $table->update();

        $table = $this->table('users');
        $table->renameColumn('photo_id', 'file_id');
        $table->update();

        $table = $this->table('photos_repairs');
        $table->rename('files_repairs');
        $table->update();
    }
}
