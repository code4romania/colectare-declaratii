@props(['version'])

<div @class([
    'flex w-full',
    'text-xs text-center text-gray-400',
    'p-4 md:px-6 lg:px-8',
    auth()->check() ? 'justify-end' : 'justify-center',
])>
    <span>{{ $version }}</span>
</div>
