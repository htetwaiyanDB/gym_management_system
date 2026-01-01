<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-100 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Blog Management</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Create and manage blog posts for members and trainers.
                            </p>
                        </div>
                        <a
                            href="{{ route('blogs.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        >
                            New Blog Post
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Posts</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">Title</th>
                                    <th class="px-4 py-2 text-left font-semibold">Status</th>
                                    <th class="px-4 py-2 text-left font-semibold">Published At</th>
                                    <th class="px-4 py-2 text-left font-semibold">Updated</th>
                                    <th class="px-4 py-2 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($posts as $post)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold">{{ $post->title }}</div>
                                            @if($post->summary)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->summary }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $post->is_published ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-400' }}">
                                                {{ $post->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                            {{ $post->published_at?->format('M d, Y') ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                            {{ $post->updated_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                <a
                                                    href="{{ route('blogs.edit', $post) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-500"
                                                >
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('blogs.destroy', $post) }}" onsubmit="return confirm('Delete this blog post?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center px-3 py-1 bg-rose-600 text-white rounded-md text-xs hover:bg-rose-500"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                            No blog posts created yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
