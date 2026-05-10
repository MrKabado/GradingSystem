<button {{ $attributes->merge([
    'class' => 'bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg cursor-pointer'
]) }}>
    {{ $slot }}
</button>