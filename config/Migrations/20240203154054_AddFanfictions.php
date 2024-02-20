<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddFanfictions extends AbstractMigration
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
        $fanfictions = $this->table("fanfictions");

        if(!$fanfictions->exists())
        {
            $fanfictions
                ->addColumn("nom","string", ["default"=> false,"null"=> false, "limit" => 50])
                ->addColumn("auteur","integer", ["default"=> false,"null"=> false])
                ->addColumn("classement","integer", ["default"=> false,"null"=> false])
                ->addColumn("description","text", ["default"=> null, "null"=> true])
                ->addColumn("langage","integer", ["default"=> false,"null"=> false])
                ->addColumn("note","integer", ["default"=> null,"null"=> true])
                ->addColumn("evaluation","text", ["default"=> null,"null"=> true])
                ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                ->addForeignKey("auteur", "auteurs", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                ->addForeignKey("langage", "langages", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                ->create();

            $fanfictions_fandoms = $this->table("fanfictions_fandoms", ["id" => false]);

            if(!$fanfictions_fandoms->exists())
                $fanfictions_fandoms
                    ->addColumn("fandom", "integer", ["default" => false, "null" => false])
                    ->addColumn("fanfiction", "integer", ["default" => false, "null" => false])
                    ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                    ->addPrimaryKey(["fandom", "fanfiction"])
                    ->addForeignKey("fandom", "fandoms", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->addForeignKey("fanfiction", "fanfictions", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->create();

            $fanfictions_liens = $this->table("fanfictions_liens");

            if(!$fanfictions_liens->exists())
                $fanfictions_liens
                    ->addColumn("lien", "string", ["default" => false, "null" => false, "limit" => 255])
                    ->addColumn("fanfiction", "integer", ["default" => false, "null" => false])
                    ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                    ->addForeignKey("fanfiction", "fanfictions", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->create();

            $fanfictions_personnages = $this->table("fanfictions_personnages", ["id" => false]);

            if(!$fanfictions_personnages->exists())
                $fanfictions_personnages
                    ->addColumn("personnage", "integer", ["default" => false, "null" => false])
                    ->addColumn("fanfiction", "integer", ["default" => false, "null" => false])
                    ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                    ->addPrimaryKey(["personnage", "fanfiction"])
                    ->addForeignKey("personnage", "personnages", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->addForeignKey("fanfiction", "fanfictions", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->create();

            $fanfictions_relations = $this->table("fanfictions_relations", ["id" => false]);

            if(!$fanfictions_relations->exists())
                $fanfictions_relations
                    ->addColumn("relation", "integer", ["default" => false, "null" => false])
                    ->addColumn("fanfiction", "integer", ["default" => false, "null" => false])
                    ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                    ->addPrimaryKey(["fanfiction", "relation"])
                    ->addForeignKey("fanfiction", "fanfictions", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->addForeignKey("relation", "relations", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->create();

            $fanfictions_tags = $this->table("fanfictions_tags", ["id" => false]);

            if(!$fanfictions_tags->exists())
                $fanfictions_tags
                    ->addColumn("tag", "integer", ["default" => false, "null" => false])
                    ->addColumn("fanfiction", "integer", ["default" => false, "null" => false])
                    ->addColumn("creation_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("update_date","datetime", ["default"=> "CURRENT_TIMESTAMP"])
                    ->addColumn("suppression_date","datetime", ["null" => true, "default"=> null])
                    ->addPrimaryKey(["tag", "fanfiction"])
                    ->addForeignKey("tag", "tags", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->addForeignKey("fanfiction", "fanfictions", "id", ["update" => "CASCADE", "delete" => "RESTRICT"])
                    ->create();

        }
    }
}
