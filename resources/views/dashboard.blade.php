<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Monitor List -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Monitors</h3>

                    @if ($monitors->isEmpty())
                        <p class="text-gray-400">
                            No monitors found. <br>
                            Use <code>import.json</code> in the root directory to add monitors when starting the
                            container.
                        </p>
                    @else
                        <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700">
                                    <th class="border p-2">
                                        <a href="{{ route('dashboard.index', ['sort' => 'url', 'direction' => $sortBy === 'url' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                           class="flex items-center justify-between hover:text-gray-600 dark:hover:text-gray-300">
                                            URL
                                            @if ($sortBy === 'url')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border p-2">
                                        <a href="{{ route('dashboard.index', ['sort' => 'uptime_status', 'direction' => $sortBy === 'uptime_status' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                           class="flex items-center justify-between hover:text-gray-600 dark:hover:text-gray-300">
                                            Status
                                            @if ($sortBy === 'uptime_status')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border p-2">
                                        <a href="{{ route('dashboard.index', ['sort' => 'uptime_last_check_date', 'direction' => $sortBy === 'uptime_last_check_date' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                           class="flex items-center justify-between hover:text-gray-600 dark:hover:text-gray-300">
                                            Last Checked
                                            @if ($sortBy === 'uptime_last_check_date')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border p-2">
                                        <a href="{{ route('dashboard.index', ['sort' => 'certificate_status', 'direction' => $sortBy === 'certificate_status' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                           class="flex items-center justify-between hover:text-gray-600 dark:hover:text-gray-300">
                                            Certificate status
                                            @if ($sortBy === 'certificate_status')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border p-2">
                                        <a href="{{ route('dashboard.index', ['sort' => 'certificate_expiration_date', 'direction' => $sortBy === 'certificate_expiration_date' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                           class="flex items-center justify-between hover:text-gray-600 dark:hover:text-gray-300">
                                            Certificate expiration
                                            @if ($sortBy === 'certificate_expiration_date')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monitors as $monitor)
                                    <tr class="border border-gray-300 dark:border-gray-700">
                                        <td class="p-2">
                                            <a href="{{ $monitor->url }}" class="text-blue-500 hover:underline"
                                                target="_blank">
                                                {{ $monitor->url }}
                                            </a>
                                        </td>
                                        <td class="p-2">
                                            <span
                                                class="px-2 py-1 rounded-full text-white text-xs break-inside-avoid
                                                {{ $monitor->uptime_status === 'up' ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ ucfirst($monitor->uptime_status) }}
                                            </span>
                                        </td>
                                        <td class="p-2">
                                            {{ $monitor->uptime_last_check_date ? $monitor->uptime_last_check_date->diffForHumans() : 'Never' }}
                                        </td>
                                        <td class="p-2">
                                            <span
                                                class="px-2 py-1 rounded-full text-white text-xs
                                                {{ $monitor->certificate_status === 'valid' ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ ucfirst($monitor->certificate_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span title="{{ $monitor->certificate_expiration_date }}">
                                                @if ($monitor->certificate_expiration_date)
                                                    {{ $monitor->certificate_expiration_date->isFuture()
                                                        ? (int) now()->diffInDays($monitor->certificate_expiration_date) . ' days from now'
                                                        : (int) now()->diffInDays($monitor->certificate_expiration_date) . ' days ago' }}
                                                @else
                                                    Never
                                                @endif
                                            </span>

                                        </td>
                                        <td>
                                            <div class="flex flex-col-2 gap-2 p-2">
                                                <a href="{{ route('monitors.edit', $monitor) }}"
                                                    class="hover:underline">Edit</a>
                                                <form action="{{ route('monitors.destroy', $monitor) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="hover:underline decoration-amber-600">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
