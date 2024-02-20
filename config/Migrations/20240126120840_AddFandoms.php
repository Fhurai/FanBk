<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddFandoms extends AbstractMigration
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
        $fandoms = $this->table("fandoms");

        $fandoms
            ->addColumn("nom","string", ["default"=> false,"null"=> false, "limit" => 50])
            ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
            ->create();
    }
}
