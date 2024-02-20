<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddTags extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $tags = $this->table("tags");

        $tags
            ->addColumn("nom","string", ["default"=> false,"null"=> false, "limit" => 50])
            ->addColumn("description","text", ["default"=> null, "null"=> true])
            ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
            ->create();
    }
}
