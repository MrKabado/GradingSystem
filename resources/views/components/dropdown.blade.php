<select
    name="{{ $selectName }}"
    class="gs-secondary-bg border border-[#545878]
    gs-secondary-text px-4 py-1 rounded-lg
    focus:outline-none focus:ring-1 focus:ring-[#6366F1] w-fit h-full">

    <option value="">Select option</option>

    @foreach ($options as $value => $label)
        <option value="{{ $value }}"
            {{ $selected == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach

</select>