<div class="relative inline-block text-left" x-data="{ open: false }">
    <div>
        <button @click="open = !open" type="button" class="inline-flex justify-center items-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" aria-expanded="true" aria-haspopup="true">
            <span class="mr-2">{{ $currentLocaleInfo['flag'] }}</span>
            <span class="mr-1">{{ $currentLocaleInfo['native'] }}</span>
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">

        <div class="py-1" role="menu" aria-orientation="vertical">
            @foreach($availableLocales as $locale => $info)
                <a href="{{ his_switch_url($locale) }}"
                   class="@if($locale === $currentLocale) bg-gray-100 @endif text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-gray-100 transition-colors duration-150"
                   role="menuitem">
                    <span class="mr-3">{{ $info['flag'] }}</span>
                    <div class="flex flex-col">
                        <span class="font-medium">{{ $info['native'] }}</span>
                        <span class="text-xs text-gray-500">{{ $info['name'] }}</span>
                    </div>
                    @if($locale === $currentLocale)
                        <svg class="ml-auto h-4 w-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
