<?php

use Cake\I18n\FrozenTime;

/**
 * @var string $name Le nom de l'élément et de son input résultat.
 * @var string $label Le libellé de l'élément.
 * @var string $class classe CSS à rajouter
 * @var FrozenTime $value Valeur de la date
 * @var boolean $required Champ répose est requis.
 * @var boolean $time Indication que le temps est modifiable
 */

// Setup des variables en fonction qu'elles ont été fournies ou non.
$label = isset($label) ? $label : "New Text";
$class = isset($class) ? $class : "";
$value = isset($value) ? $value : null;
$required = isset($required) ? $required : false;
$time = isset($time) ? $time : false;
?>
<!-- Objet Fly Date -->
<div class="flydate">
    <?php // Si le nom de la date est fourni. 
    ?>
    <?php if (isset($name)) : ?>

        <!-- Partie date -->
        <div class="date">
            <!-- Label -->
            <?php // Si la date est requise, un petit text rouge est affiché. 
            ?>
            <label for="<?= $name ?>"><?= $label ?><?= $required ? "<span class='required'></span>" : "" ?></label>

            <!-- Champ résultat de l'élément Fly Date -->
            <input type="text" name="<?= $name ?>" id="<?= $name ?>" />

            <!-- Partie dator date -->
            <div class="dator">
                📅
                <!-- Champ jour -->
                <div class="input">
                    <input type="text" id="flydate_input_day_<?= $name ?>" placeholder="Day" <?= $required ? "required" : "" ?> maxlength="2" pattern="[0-2][0-9]|[30-1]{2}" />
                </div>
                /

                <!-- Champ mois -->
                <div class="input">
                    <input type="text" id="flydate_input_month_<?= $name ?>" placeholder="Month" <?= $required ? "required" : "" ?> maxlength="2" pattern="[0][1-9]|1[012]" />
                </div>
                /

                <!-- Champ année -->
                <div class="input">
                    <input type="text" id="flydate_input_year_<?= $name ?>" placeholder="Year" <?= $required ? "required" : "" ?> maxlength="4" pattern="^(?:19|20)\d{2}$" />
                </div>
            </div>

            <?php if ($time) : ?>
                <!-- Partie dator heure -->
                <div class="dator">
                    ⌚
                    <!-- Champ heure -->
                    <div class="input hour">
                        <input type="text" id="flydate_input_hour_<?= $name ?>" placeholder="Hour" <?= $required ? "required" : "" ?> maxlength="2" />

                        <!-- Sélecteur periode de la journée -->
                        <select id="time_period">
                            <option>AM</option>
                            <option>PM</option>
                        </select>
                    </div>
                    /

                    <!-- Champ minute -->
                    <div class="input">
                        <input type="text" id="flydate_input_minute_<?= $name ?>" placeholder="Minute" <?= $required ? "required" : "" ?> maxlength="2" pattern="[0-5][0-9]" />
                    </div>
                    /

                    <!-- Champ secondes -->
                    <div class="input">
                        <input type="text" id="flydate_input_seconds_<?= $name ?>" placeholder="Seconds" <?= $required ? "required" : "" ?> maxlength="2" pattern="[0-5][0-9]" />
                    </div>
                    ⏲️
                    <!-- Selecteur cycle heures -->
                    <div class="select">
                        <select id="time_cycle">
                            <option>24H</option>
                            <option>12H</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <?php else : ?>

        <!-- Partie erreur. -->
        <div class="error">

            <?php // Affichage du paramètre manquant dans le message d'erreur. 
            ?>
            Missing parameter(s) for FlyDate[name]
        </div>

    <?php endif; ?>
</div>
<!-- Imports pour l'apparence et le fonctionnement du champ text. -->
<?= $this->Html->script(["fly/date"]); ?>
<?= $this->Html->css(["fly/date"]); ?>

<!-- Initialisation des valeurs existantes. -->
<?php if (isset($value)) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTypeDate("<?= $name ?>", "<?= $value->format("Y-m-d H:i:s") ?>");
        });
    </script>
<?php endif; ?>