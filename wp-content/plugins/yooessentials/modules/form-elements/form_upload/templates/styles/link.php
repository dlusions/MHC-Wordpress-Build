<span class="uk-text-middle"><?= $props['placeholder'] ?? '' ?></span>

<div uk-form-custom>

    <?= $this->render("{$__dir}/_input", ['node' => $node, 'props' => $props]) ?>

    <span class="uk-link">
        <?= $props['content'] ?>
    </span>
</div>
