@if(Str::length($slot) > ($max ?? 780))
    <div class="mt-1 d-block" x-data="{ open: false }">
        <template x-if="open">
            <small {{ $attributes->class(['text-muted' => !$attributes->has('color'), 'text-'.$attributes->get('color') => $attributes->has('color')])}}>
                {{$slot}}
            </small>
        </template>
        <template x-if="!open">
            <small {{ $attributes->class(['text-muted' => !$attributes->has('color'), 'text-'.$attributes->get('color') => $attributes->has('color')])}}>
                {!! Str::limit($slot, ($max ?? 780)) !!}
            </small>
        </template>
        <button @click="open=!open" class="text-primary text-sm bg-transparent d-block mt-1 ps-0" x-text="open ? '{{ __('Show less') }}' : '{{ __('Read more') }}'"></button>
    </div>
@else
    <small {{ $attributes->class(['text-muted' => !$attributes->has('color'), 'text-'.$attributes->get('color') => $attributes->has('color')])}} class="d-block mt-1">
        {{$slot}}
    </small>
@endif
