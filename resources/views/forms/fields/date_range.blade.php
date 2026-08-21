<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showField): ?>
        <div>
            <label for="{{ $name }}" class="form-label fw-bold">{{$options['label']}}</label>
            <select name="{{ $name }}" id="{{ $name }}" class="form-control">
                @foreach($options['options'] as $value => $label)
                    <option @selected($options['selected'] === $value) value={{$value}}>{{$label}}</option>
                @endforeach
            </select>
        </div>
    <?php include helpBlockPath(); ?>
<?php endif; ?>

<?php include errorBlockPath(); ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
<?php endif; ?>
<?php endif; ?>
