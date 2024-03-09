<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showField): ?>
    <search-model name="{{ $name }}" endpoint="{{ $options['endpoint'] ?? null }}" @isset(${'selected'.ucfirst($name)})) value="{{ ${'selected'.ucfirst($name)} }}" @endisset></search-model>

    <?php include helpBlockPath(); ?>
<?php endif; ?>

<?php include errorBlockPath(); ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
<?php endif; ?>
<?php endif; ?>
