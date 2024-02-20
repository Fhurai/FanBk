<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddSeriesViews extends AbstractMigration
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
        // Vue Fanfictions
        $queryBuilder = $this->execute("CREATE OR REPLACE VIEW series_search AS(
            SELECT
                series.id,
                series.nom,
                series.description,
                f.classement,
                series.note,
                series.evaluation,
                series.creation_date,
                series.update_date,
                f.nom AS fanfiction,
                a.nom AS auteur
            FROM
                series
            INNER JOIN series_fanfictions sf ON
                sf.series = series.id
            INNER JOIN fanfictions f ON
                f.id = sf.fanfiction
            INNER JOIN auteurs a ON
                a.id = f.auteur
        );");

        //Vue Fandoms
        $queryBuilder = $this->execute("CREATE OR REPLACE VIEW series_fandoms AS(
            SELECT DISTINCT
                fd.nom AS fandom_nom,
                fd.id AS fandom_id,
                series.id AS series_id
            FROM
                series
            INNER JOIN series_fanfictions sf ON
                sf.series = series.id
            INNER JOIN fanfictions f ON
                f.id = sf.fanfiction
            INNER JOIN fanfictions_fandoms ff ON
                ff.fanfiction = f.id
            INNER JOIN fandoms fd ON
                fd.id = ff.fandom
        );");

        //Vue Relations
        $queryBuilder = $this->execute("CREATE OR REPLACE VIEW series_relations AS(
            SELECT DISTINCT
                r.nom AS relation_nom,
                r.id AS relation_id,
                series.id AS series_id
            FROM
                series
            INNER JOIN series_fanfictions sf ON
                sf.series = series.id
            INNER JOIN fanfictions f ON
                f.id = sf.fanfiction
            INNER JOIN fanfictions_relations fr ON
                fr.fanfiction = f.id
            INNER JOIN relations r ON
                r.id = fr.relation
        );");

        //Vue Personnages
        $queryBuilder = $this->execute("CREATE OR REPLACE VIEW series_personnages AS(
            SELECT DISTINCT
                p.nom AS personnage_nom,
                p.id AS personnage_id,
                series.id AS series_id
            FROM
                series
            INNER JOIN series_fanfictions sf ON
                sf.series = series.id
            INNER JOIN fanfictions f ON
                f.id = sf.fanfiction
            INNER JOIN fanfictions_personnages fp ON
                fp.fanfiction = f.id
            INNER JOIN personnages p ON
                p.id = fp.personnage
        );");

        //Vue Tags
        $queryBuilder = $this->execute("CREATE OR REPLACE VIEW series_tags AS(
            SELECT DISTINCT
                t.nom AS tag_nom,
                t.id AS tag_id,
                series.id AS series_id
            FROM
                series
            INNER JOIN series_fanfictions sf ON
                sf.series = series.id
            INNER JOIN fanfictions f ON
                f.id = sf.fanfiction
            INNER JOIN fanfictions_tags ft ON
                ft.fanfiction = f.id
            INNER JOIN tags t ON
                t.id = ft.tag
        );");
    }
}
