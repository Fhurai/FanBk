<?php

declare(strict_types=1);

use Cake\I18n\FrozenDate;
use Migrations\AbstractMigration;

class AddUsersTable extends AbstractMigration
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
        $users = $this->table("users");

        if (!$users->exists()) {
            $users
                ->addColumn("username", "string", ["limit" => 50])
                ->addColumn("password", "string", ["limit" => 255])
                ->addColumn("email", "string", ["limit" => 255])
                ->addColumn("is_admin", "boolean", ["default" => false])
                ->addColumn("birthday", "datetime", ["null" =>  true, "default" => null])
                ->addColumn("creation_date", "datetime", ["default" => "CURRENT_TIMESTAMP"])
                ->addColumn("update_date", "datetime", ["default" => "CURRENT_TIMESTAMP"])
                ->addColumn("suppression_date", "datetime", ["null" => true, "default" => null])
                ->create();
        }

        if ($users->exists()) {
            $row = [
                "username" => "Admin",
                "password" => "$2y$10\$R3VHp4v5P1okSgInzICW8u/jUcbfufXzbiKqfegVW6gljo0iuwla6",
                "email" => "kulu57@live.com",
                "is_admin" => true,
                "birthday" => new FrozenDate("2024-01-29 23:30:30", "Europe/Paris"),
                "creation_date" => new FrozenDate("2024-01-29 23:30:30", "Europe/Paris"),
                "update_date" => new FrozenDate("2024-01-29 23:30:30", "Europe/Paris")
            ];

            $users->insert($row)->saveData();
        }
    }
}
