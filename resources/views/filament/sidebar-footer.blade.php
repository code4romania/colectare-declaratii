<aside
    class="flex flex-col items-start justify-end flex-1 gap-3 "
    x-show="$store.sidebar.isOpen"
    x-transition:enter="lg:transition lg:delay-100"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100">

    <h2
        class="w-full pt-6 text-sm font-medium leading-6 text-gray-500 fi-sidebar-group-label">
        @lang('app.developed_by')
    </h2>

    <a href="https://www.commitglobal.org" target="_blank" rel="noopener noreferrer" tabindex="-1">
        <x-icon-commitglobal class="h-8 text-gray-950 dark:text-gray-100" />
    </a>
</aside>
