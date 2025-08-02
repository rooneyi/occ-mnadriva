@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-6">
    <h2 class="text-2xl font-bold text-blue-900 font-sans">{{ $title }}</h2>
    <p class="text-sm text-blue-700 mt-2 font-sans">{{ $description }}</p>
</div>
