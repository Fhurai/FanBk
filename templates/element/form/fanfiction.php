<?php
/**
 * @var \App\Model\Entity\Fanfiction $fanfiction
 * @var string $action
 * @var array $parametres
 */
use Cake\I18n\FrozenTime;

?>
<?= $this->Form->create($fanfiction) ?>
    <fieldset>
        <legend><?= __('Ajouter une fanfiction') ?></legend>
        <?php
            echo $this->Form->control('nom', ["required" => true]);
            
            echo "<div class='allow-new-closed'>" . $this->Form->control('auteur', ["div" => ["class" => "allow-new"], "label" => "Auteur ➕"]) . 
            $this->Form->control("auteur-new", ["label" => "*New", "placeholder" => "Nouvel auteur"]) . "</div>";
            
            echo $this->Form->control('classement', ["options" => $parametres["Classement"], "required" => true]);
            
            echo $this->Form->control('description', ["required" => true]);
            
            echo "<div class='allow-new-closed'>".$this->Form->control('fandoms', ["multiple" => true, "label" => "Fandom(s) ➕", "value" => !is_null($fanfiction->fandoms) ? array_column($fanfiction->fandoms, "id") : []]) . 
            $this->Form->control("fandoms-new", ["label" => "*New", "placeholder" => "Nouveau fandom"]) . "</div>";
            
            echo "<div class='allow-new-closed'>". $this->Form->control('langage', ["label" => "Langage ➕"]) . 
            $this->Form->control("langage-new", ["label" => "*New", "placeholder" => "Nouveau langage"]) . "</div>";
            
        ?>

        <table class="flylist">
            <thead>
                <tr>
                    <th colspan="3">
                        <label>Liens</label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($fanfiction->liens)): ?>
                    <?php foreach($fanfiction->liens as $keyDonnee => $lien): ?>
                        <tr>
                            <th><?= $keyDonnee + 1 ?></th>
                            <td><?= $this->Form->text("lien[" . ($keyDonnee+1) . "]", [
                                'value' => $lien->lien
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td colspan="3">
                        <div class="addButton" title="lien">➕</div>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php            
            echo "<div class='allow-new-closed'>" . $this->Form->control('relations', ["options" => $optionsRelations, "multiple" => true, "label" => "Relation(s) ➕", "value" => !is_null($fanfiction->relations) ? array_column($fanfiction->relations, "id") : []]) . 
            $this->Form->control("relations-new", ["label" => "*New", "placeholder" => "Nouvelle relation"]) .  "</div>";
            
            
            echo "<div class='allow-new-closed'>" . $this->Form->control('personnages', ["options" => $optionsPersonnages, "multiple" => true, "label" => "Personnage(s) ➕", "value" => !is_null($fanfiction->personnages) ? array_column($fanfiction->personnages, "id") : []]) . 
            $this->Form->control("personnages-new", ["label" => "*New", "placeholder" => "Nouveau personnage"]) . "</div>";
            
            echo "<div class='allow-new-closed'>" . $this->Form->control('tags', ["options" => $optionsTags, "multiple" => true, "label" => "Tag(s) ➕", "value" => !is_null($fanfiction->tags) ? array_column($fanfiction->tags, "id") : []]) . 
            "<div>" . $this->Form->control("tags-new", ["label" => "*New", "placeholder" => "Nouveau tag"]) . 
            $this->Form->control("tags-desc-new", ["label" => false, "type" => "textarea", "placeholder" => "Description du nouveau tag"])
             . "</div></div>";
            
            echo $this->Form->control('update_date', ['type' => 'hidden', 'value' => FrozenTime::now("Europe/Paris")->format('Y-m-d H:i:s')]);
        ?>
    </fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
<?= $this->Html->script(['form/fanfiction']) ?>