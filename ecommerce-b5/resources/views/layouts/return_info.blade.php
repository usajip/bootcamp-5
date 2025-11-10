{{-- Show success message and error message --}}
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
    @if (session('success'))
        <x-alert type="success" :cssStyle="'tailwind'">
            {!! session('success') !!}
        </x-alert>
    @endif

    @if ($errors->any())
        <x-alert type="error" :cssStyle="'tailwind'">
            <ul class="mb-0 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif
</div>