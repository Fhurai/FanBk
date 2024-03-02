<?php

/**
 * @var string $type Le type de formulaire à produire.
 */
$singular = substr($type, 0, -1);
?>
<!-- La donnée de l'action "add" traduite pour l'URL. -->
<input type="hidden" name="_action" value="<?= array_flip($actions)["add"] ?>" />

<!-- La donnée de l'objet manipulé traduit pour l'URL. -->
<input type="hidden" name="_object" value="<?= array_flip($objects)[$type] ?>" />

<!-- Formulaire pour l'objet manipulé -->
<?= $this->element("form/" . $singular, ["$singular" => $$singular, "action" => "add"]) ?>

<!-- Champ d'alerte pour la modale de l'appel Ajax. -->
<div class="form alert"></div>