@props(['colspan' => 6, 'title' => 'No records found', 'message' => 'Get started by adding your first record.', 'actionUrl' => null, 'actionText' => 'Add Record'])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
        <div class="flex flex-col items-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">{{ $message }}</p>
            @if($actionUrl)
                <a href="{{ $actionUrl }}"
                   class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                    {{ $actionText }}
                </a>
            @endif
            {{ $slot }}
        </div>
    </td>
</tr>
