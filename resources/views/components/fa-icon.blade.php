<svg xmlns="http://www.w3.org/2000/svg"
    {{ $attributes->merge([
        'class' => $inlineSvgClasses,
        'aria-hidden' => 'true',
        'focusable' => 'false',
        'role' => 'img',
        'viewBox' => $viewBox,
    ]) }}
>
    @if ($style)
        <defs><style>{{ $style }}</style></defs>
    @endif
    @foreach ($paths as $path)
        <path fill="currentColor" {{ $path['class'] ? 'class="' . $path['class'] . '"' : '' }} d="{{ $path['d'] }}" />
    @endforeach
</svg>
