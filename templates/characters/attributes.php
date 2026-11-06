<?php
/** @var array $character linha do banco (attr_for…) ou ['attr_for' => …] */
use Doppel\Rpg\CharacterRules;
?>
<ul class="attributes">
    <?php foreach (ATTRIBUTES as $code => $label): $value = (int) $character['attr_' . $code]; ?>
        <li title="<?= e($label) ?>">
            <span class="attr-code"><?= strtoupper($code) ?></span>
            <strong class="attr-value"><?= $value ?></strong>
            <span class="attr-mod"><?= signed(CharacterRules::modifier($value)) ?></span>
        </li>
    <?php endforeach; ?>
</ul>
