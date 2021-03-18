<svg xmlns="http://www.w3.org/2000/svg"
    {{ $attributes->merge([
        'class' => $inlineSvgClasses,
        'aria-hidden' => 'true',
        'focusable' => 'false',
        'role' => 'img',
        'viewBox' => $viewBox,
    ]) }}
>
    <path fill="currentColor" d="{{ $path }}"/>
</svg>
