<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddPersonnagesAndRelations extends AbstractMigration
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

        /**
         * Partie Personnages
         */

        $personnages = $this->table("personnages");

        if(!$personnages->exists())
            $personnages
                ->addColumn("nom","string", ["default"=> false,"null"=> false, "limit" => 50])
                ->addColumn("fandom","integer", ["default"=> false, "null"=> false])
                ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                ->addForeignKey("fandom", "fandoms", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                ->create();


        /***
         * Partie relations
         */
        $relations = $this->table("relations");

        if(!$relations->exists())
            $relations
                ->addColumn("nom","string", ["default"=> false,"null"=> false, "limit" => 255])
                ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                ->create();

        $relations_personnages = $this->table("relations_personnages", ["id" => false]);

        if(!$relations_personnages->exists())
            $relations_personnages
                ->addColumn("personnage", "integer", ["default" => false, "null" => false])
                ->addColumn("relation", "integer", ["default" => false, "null" => false])
                ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                ->addPrimaryKey(["personnage", "relation"])
                ->addForeignKey("personnage", "personnages", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                ->addForeignKey("relation", "relations", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                ->create();
    }
}
