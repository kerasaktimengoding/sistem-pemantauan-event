@php
    use Filament\Support\Facades\FilamentAsset;
    use Guava\Calendar\Enums\Context;
    use Filament\Support\Facades\FilamentColor;
    use Filament\Support\View\Components\ButtonComponent;
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        :after-header="$this->getCachedHeaderActionsComponent()"
        :footer="$this->getCachedFooterActionsComponent()"
    >

        <style>
            .ec-event.ec-preview,
            .ec-now-indicator {
                z-index: 30;
            }
        </style>

        @if($heading = $this->getHeading())
            <x-slot name="heading">
                {{ $this->getHeading() }}
            </x-slot>
        @endif

        <div
            wire:ignore
            x-load
            x-load-src="{{ FilamentAsset::getAlpineComponentSrc('calendar', 'guava/calendar') }}"
            x-data="calendar({
                view: @js($this->getCalendarView()),
                locale: @js($this->getLocale()),
                firstDay: @js($this->getFirstDay()),
                dayMaxEvents: @js($this->getDayMaxEvents()),
                eventContent: @js($this->getEventContentJs()),
                eventClickEnabled: @js($this->isEventClickEnabled()),
                eventDragEnabled: @js($this->isEventDragEnabled()),
                eventResizeEnabled: @js($this->isEventResizeEnabled()),
                noEventsClickEnabled: @js($this->isNoEventsClickEnabled()),
                dateClickEnabled: @js($this->isDateClickEnabled()),
                dateSelectEnabled: @js($this->isDateSelectEnabled()),
                datesSetEnabled: @js($this->isDatesSetEnabled()),
                viewDidMountEnabled: @js($this->isViewDidMountEnabled()),
                eventAllUpdatedEnabled: @js($this->isEventAllUpdatedEnabled()),
                hasDateClickContextMenu: @js($this->hasContextMenu(Context::DateClick)),
                hasDateSelectContextMenu: @js($this->hasContextMenu(Context::DateSelect)),
                hasEventClickContextMenu: @js($this->hasContextMenu(Context::EventClick)),
                hasNoEventsClickContextMenu: @js($this->hasContextMenu(Context::NoEventsClick)),
                resources: @js($this->getResourcesJs()),
                resourceLabelContent: @js($this->getResourceLabelContentJs()),
                theme: @js($this->getTheme()),
                options: @js($this->getOptions()),
                eventAssetUrl: @js(FilamentAsset::getAlpineComponentSrc('calendar-event', 'guava/calendar')),
            })"
            @class(FilamentColor::getComponentClasses(ButtonComponent::class, 'primary'))
        >
            <div data-calendar></div>
            @if($this->hasContextMenu())
                <x-guava-calendar::context-menu/>
            @endif
        </div>

        {{-- LEGEND COUNT EVENTS --}}
        @php
            $counts = $this->getEventCounts();
            $colors = [
                'Direncanakan' => '#3b82f6', // blue
                'Berjalan' => '#f59e0b',     // amber
                'Selesai' => '#10b981',      // green
                'Dibatalkan' => '#ef4444',   // red
            ];
        @endphp
        <div class="mt-6 flex flex-wrap gap-4 px-4 py-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="w-full text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">Total Event Berdasarkan Status:</div>
            @foreach(['Direncanakan', 'Berjalan', 'Selesai', 'Dibatalkan'] as $status)
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $colors[$status] }}"></span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $status }} ({{ $counts[$status] ?? 0 }})
                    </span>
                </div>
            @endforeach
        </div>

    </x-filament::section>
        <x-filament-actions::modals/>
</x-filament-widgets::widget>
