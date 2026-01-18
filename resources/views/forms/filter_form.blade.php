<div x-data="{ filters: window.innerWidth > 992 }">
    <button class="btn btn-primary btn-sm d-flex d-lg-none align-items-center gap-2 mb-3" @click="filters = !filters">
        <i class="fa-solid fa-filter"></i>
        <span>{{ __('Filters') }}</span>
        <i class="fa-solid fa-chevron-down" x-show.important="!filters" ></i>
        <i class="fa-solid fa-chevron-up" x-show.important="filters" ></i>
    </button>

    <?php if ($showStart): ?>
        <?= Form::open($formOptions) ?>
    <?php endif; ?>

    <?php if ($showFields): ?>
        <?php foreach ($fields as $field): ?>
            <?php if( ! in_array($field->getName(), $exclude) ) { ?>
                <?= $field->render() ?>
            <?php } ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="btn-group col-auto">
        <button type="submit" class="btn btn-outline-secondary" title="{{ __('Search') }}">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <a href="{{ url()->current() }}" class="btn btn-outline-secondary" title="{{ __('Reset') }}">
            <i class="fa-solid fa-eraser"></i>
        </a>
    </div>

    <?php if ($showEnd): ?>
        <?= Form::close() ?>
    <?php endif; ?>
</div>
