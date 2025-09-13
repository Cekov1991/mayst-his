@props(['header' => false, 'class' => ''])

@if($header)
    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0 dark:text-white {{ $class }}">
        {{ $slot }}
    </td>
@else
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 {{ $class }}">
        {{ $slot }}
    </td>
@endif
