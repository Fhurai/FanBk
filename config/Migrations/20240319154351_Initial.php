<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        $this->table('auteurs')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('fandoms')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('fanfictions')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('auteur', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('classement', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('langage', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('note', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('evaluation', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'auteur',
                ],
                [
                    'name' => 'fanfictions_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'langage',
                ],
                [
                    'name' => 'fanfictions_ibfk_2',
                ]
            )
            ->create();

        $this->table('fanfictions_fandoms', ['id' => false, 'primary_key' => ['fandom', 'fanfiction']])
            ->addColumn('fandom', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('fanfiction', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'fandom',
                ],
                [
                    'name' => 'fanfictions_fandoms_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'fanfiction',
                ],
                [
                    'name' => 'fanfictions_fandoms_ibfk_2',
                ]
            )
            ->create();

        $this->table('fanfictions_liens')
            ->addColumn('lien', 'string', [
                'default' => '0',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('fanfiction', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'fanfiction',
                ],
                [
                    'name' => 'fanfictions_liens_ibfk_1',
                ]
            )
            ->create();

        $this->table('fanfictions_personnages', ['id' => false, 'primary_key' => ['personnage', 'fanfiction']])
            ->addColumn('personnage', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('fanfiction', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'personnage',
                ],
                [
                    'name' => 'fanfictions_personnages_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'fanfiction',
                ],
                [
                    'name' => 'fanfictions_personnages_ibfk_2',
                ]
            )
            ->create();

        $this->table('fanfictions_relations', ['id' => false, 'primary_key' => ['relation', 'fanfiction']])
            ->addColumn('relation', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('fanfiction', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'fanfiction',
                ],
                [
                    'name' => 'fanfictions_relations_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'relation',
                ],
                [
                    'name' => 'fanfictions_relations_ibfk_2',
                ]
            )
            ->create();

        $this->table('fanfictions_tags', ['id' => false, 'primary_key' => ['tag', 'fanfiction']])
            ->addColumn('tag', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('fanfiction', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'tag',
                ],
                [
                    'name' => 'fanfictions_tags_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'fanfiction',
                ],
                [
                    'name' => 'fanfictions_tags_ibfk_2',
                ]
            )
            ->create();

        $this->table('langages')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('abbreviation', 'string', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('personnages')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('fandom', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'fandom',
                ],
                [
                    'name' => 'personnages_ibfk_1',
                ]
            )
            ->create();

        $this->table('relations')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('relations_personnages', ['id' => false, 'primary_key' => ['personnage', 'relation']])
            ->addColumn('personnage', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('relation', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'personnage',
                ],
                [
                    'name' => 'relations_personnages_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'relation',
                ],
                [
                    'name' => 'relations_personnages_ibfk_2',
                ]
            )
            ->create();

        $this->table('series')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('note', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('evaluation', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('series_fandoms', ['id' => false])
            ->addColumn('fandom_nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('fandom_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('series_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('series_fanfictions', ['id' => false, 'primary_key' => ['fanfiction', 'series']])
            ->addColumn('fanfiction', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('series', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('ordre', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'series',
                ],
                [
                    'name' => 'series_fanfictions_ibfk_1',
                ]
            )
            ->addIndex(
                [
                    'fanfiction',
                ],
                [
                    'name' => 'series_fanfictions_ibfk_2',
                ]
            )
            ->create();

        $this->table('series_personnages', ['id' => false])
            ->addColumn('personnage_nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('personnage_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('series_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('series_relations', ['id' => false])
            ->addColumn('relation_nom', 'string', [
                'default' => '0',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('relation_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('series_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('series_search', ['id' => false])
            ->addColumn('id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('classement', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('note', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('evaluation', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('fanfiction', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('auteur', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->create();

        $this->table('series_tags', ['id' => false])
            ->addColumn('tag_nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('tag_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('series_id', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('tags')
            ->addColumn('nom', 'string', [
                'default' => '0',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('users')
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('is_admin', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('birthday', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('creation_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('update_date', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('suppression_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('fanfictions')
            ->addForeignKey(
                'auteur',
                'auteurs',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_ibfk_1'
                ]
            )
            ->addForeignKey(
                'langage',
                'langages',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_ibfk_2'
                ]
            )
            ->update();

        $this->table('fanfictions_fandoms')
            ->addForeignKey(
                'fandom',
                'fandoms',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_fandoms_ibfk_1'
                ]
            )
            ->addForeignKey(
                'fanfiction',
                'fanfictions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_fandoms_ibfk_2'
                ]
            )
            ->update();

        $this->table('fanfictions_liens')
            ->addForeignKey(
                'fanfiction',
                'fanfictions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_liens_ibfk_1'
                ]
            )
            ->update();

        $this->table('fanfictions_personnages')
            ->addForeignKey(
                'personnage',
                'personnages',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_personnages_ibfk_1'
                ]
            )
            ->addForeignKey(
                'fanfiction',
                'fanfictions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_personnages_ibfk_2'
                ]
            )
            ->update();

        $this->table('fanfictions_relations')
            ->addForeignKey(
                'fanfiction',
                'fanfictions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_relations_ibfk_1'
                ]
            )
            ->addForeignKey(
                'relation',
                'relations',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_relations_ibfk_2'
                ]
            )
            ->update();

        $this->table('fanfictions_tags')
            ->addForeignKey(
                'tag',
                'tags',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_tags_ibfk_1'
                ]
            )
            ->addForeignKey(
                'fanfiction',
                'fanfictions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'fanfictions_tags_ibfk_2'
                ]
            )
            ->update();

        $this->table('personnages')
            ->addForeignKey(
                'fandom',
                'fandoms',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'personnages_ibfk_1'
                ]
            )
            ->update();

        $this->table('relations_personnages')
            ->addForeignKey(
                'personnage',
                'personnages',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'relations_personnages_ibfk_1'
                ]
            )
            ->addForeignKey(
                'relation',
                'relations',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'relations_personnages_ibfk_2'
                ]
            )
            ->update();

        $this->table('series_fanfictions')
            ->addForeignKey(
                'series',
                'series',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'series_fanfictions_ibfk_1'
                ]
            )
            ->addForeignKey(
                'fanfiction',
                'fanfictions',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'RESTRICT',
                    'constraint' => 'series_fanfictions_ibfk_2'
                ]
            )
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down(): void
    {
        $this->table('fanfictions')
            ->dropForeignKey(
                'auteur'
            )
            ->dropForeignKey(
                'langage'
            )->save();

        $this->table('fanfictions_fandoms')
            ->dropForeignKey(
                'fandom'
            )
            ->dropForeignKey(
                'fanfiction'
            )->save();

        $this->table('fanfictions_liens')
            ->dropForeignKey(
                'fanfiction'
            )->save();

        $this->table('fanfictions_personnages')
            ->dropForeignKey(
                'personnage'
            )
            ->dropForeignKey(
                'fanfiction'
            )->save();

        $this->table('fanfictions_relations')
            ->dropForeignKey(
                'fanfiction'
            )
            ->dropForeignKey(
                'relation'
            )->save();

        $this->table('fanfictions_tags')
            ->dropForeignKey(
                'tag'
            )
            ->dropForeignKey(
                'fanfiction'
            )->save();

        $this->table('personnages')
            ->dropForeignKey(
                'fandom'
            )->save();

        $this->table('relations_personnages')
            ->dropForeignKey(
                'personnage'
            )
            ->dropForeignKey(
                'relation'
            )->save();

        $this->table('series_fanfictions')
            ->dropForeignKey(
                'series'
            )
            ->dropForeignKey(
                'fanfiction'
            )->save();

        $this->table('auteurs')->drop()->save();
        $this->table('fandoms')->drop()->save();
        $this->table('fanfictions')->drop()->save();
        $this->table('fanfictions_fandoms')->drop()->save();
        $this->table('fanfictions_liens')->drop()->save();
        $this->table('fanfictions_personnages')->drop()->save();
        $this->table('fanfictions_relations')->drop()->save();
        $this->table('fanfictions_tags')->drop()->save();
        $this->table('langages')->drop()->save();
        $this->table('personnages')->drop()->save();
        $this->table('relations')->drop()->save();
        $this->table('relations_personnages')->drop()->save();
        $this->table('series')->drop()->save();
        $this->table('series_fandoms')->drop()->save();
        $this->table('series_fanfictions')->drop()->save();
        $this->table('series_personnages')->drop()->save();
        $this->table('series_relations')->drop()->save();
        $this->table('series_search')->drop()->save();
        $this->table('series_tags')->drop()->save();
        $this->table('tags')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
