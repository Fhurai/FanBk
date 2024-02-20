<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddSeries extends AbstractMigration
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
        $series = $this->table("series");

        $series
            ->addColumn("nom", "string", ["default"=> false,"null"=> false, "limit" => 100])
            ->addColumn("description", "text", ["default"=> null, "null"=> true])
            ->addColumn("note","integer", ["default"=> null,"null"=> true])
            ->addColumn("evaluation","text", ["default"=> null,"null"=> true])
            ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
            ->create();

        $series_fanfictions = $this->table("series_fanfictions", ["id" => false]);

        $series_fanfictions
            ->addColumn("fanfiction", "integer", ["default" => false, "null" => false])
            ->addColumn("series", "integer", ["default" => false, "null" => false])
            ->addColumn("order", "integer", ["default" => false, "null" => false])
            ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
            ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
            ->addPrimaryKey(["series", "fanfiction"])
            ->addForeignKey("series", "series", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
            ->addForeignKey("fanfiction", "fanfictions", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
            ->create();
        
    }
}
