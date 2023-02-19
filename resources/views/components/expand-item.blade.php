@if(Str::length($slot) > ($max ?? 780))
    <div class="mt-1 d-block" x-data="{ open: false }">
        <template x-if="open">
            <small class="text-muted">
                {{$slot}}
            </small>
        </template>
        <template x-if="!open">
            <small class="text-muted">
                {!! Str::limit($slot, ($max ?? 780)) !!}
            </small>
        </template>
        <button @click="open=!open" class="text-primary text-sm bg-transparent d-block mt-1 ps-0" x-text="open ? 'Show less': 'Read more'"></button>
    </div>
@else
    <small class="text-muted d-block mt-1">{{$slot}}</small>
@endif
