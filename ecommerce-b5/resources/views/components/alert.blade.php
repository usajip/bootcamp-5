@if($cssStyle === 'bootstrap')
<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    {{ $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif($cssStyle === 'tailwind')
<div class="rounded-md p-4
    @if($type === 'success') bg-green-50 text-green-800
    @elseif($type === 'error') bg-red-50 text-red-800
    @elseif($type === 'warning') bg-yellow-50 text-yellow-800
    @elseif($type === 'info') bg-blue-50 text-blue-800
    @else bg-gray-50 text-gray-800 @endif
    ">
    <div class="flex">
        <div class="flex-shrink-0">
            @if($type === 'success')
            <span class="material-symbols-rounded text-2xl">check_circle</span>
            @elseif($type === 'error')
            <span class="material-symbols-rounded text-2xl">error</span>
            @elseif($type === 'warning')
            <span class="material-symbols-rounded text-2xl">warning</span>
            @elseif($type === 'info')
            <span class="material-symbols-rounded text-2xl">info</span>
            @else
            <span class="material-symbols-rounded text-2xl">notifications</span>
            @endif
        </div>
        <div class="ml-3">
            <p class="text-sm">
                {{ $slot }}
            </p>
        </div>
    </div>
</div>
@endif