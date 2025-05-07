<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Monitor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Update Monitor') }}
                            </h2>
                        </header>

                        <form method="post" action="{{ route('monitors.update', $monitor) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="url" :value="__('URL')" />
                                <x-text-input id="url" name="url" type="text" class="mt-1 block w-full"
                                    :value="old('url', $monitor->url)" required autofocus="url" />
                                <x-input-error class="mt-2" :messages="$errors->get('url')" />
                            </div>

                            <div>
                                <x-input-label for="lookForString" :value="__('Look for String')" />
                                <x-text-input id="lookforstring" name="lookforstring" type="text"
                                    class="mt-1 block w-full" :value="old('lookforstring', $monitor->look_for_string)" />
                                <x-input-error class="mt-2" :messages="$errors->get('lookforstring')" />
                            </div>

                            <x-input-label for="uptime_check_enabled" :value="__('Uptime check enabled')" />
                            <input type="checkbox" id="uptime_check_enabled" name="uptime_check_enabled" value="1"
                                @checked($monitor->uptime_check_enabled)>

                            <x-input-label for="certificate_check_enabled" :value="__('Certificate check enabled')" />
                            <input type="checkbox" id="certificate_check_enabled" name="certificate_check_enabled"
                                value="1" @checked($monitor->certificate_check_enabled)>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'monitor-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
