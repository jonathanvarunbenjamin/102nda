<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin &middot; Members</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-2xl font-bold text-amber-600">{{ $counts['pending'] }}</p>
                    <p class="text-sm text-gray-500">Awaiting approval</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-2xl font-bold text-green-600">{{ $counts['approved'] }}</p>
                    <p class="text-sm text-gray-500">Approved members</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-2xl font-bold text-slate-700">{{ $counts['admins'] }}</p>
                    <p class="text-sm text-gray-500">Administrators</p>
                </div>
            </div>

            {{-- Filter tabs --}}
            <div class="flex flex-wrap gap-2">
                @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'suspended' => 'Suspended', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                    <a href="{{ route('admin.members.index', ['status' => $key]) }}"
                       class="rounded-full px-4 py-1.5 text-sm {{ $filter === $key ? 'bg-slate-700 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Members table --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if ($users->isEmpty())
                    <p class="p-8 text-center text-gray-500">No members in this list.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50 text-left text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Member</th>
                                <th class="px-4 py-3">Details</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if ($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="" class="h-8 w-8 rounded-full object-cover">
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $user->profile?->squadron ? $user->profile->squadron.' Sqn' : '—' }}
                                        {{ $user->profile?->academy_number ? '· '.$user->profile->academy_number : '' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-0.5 text-xs
                                            @class([
                                                'bg-green-100 text-green-700' => $user->status === 'approved',
                                                'bg-amber-100 text-amber-700' => $user->status === 'pending',
                                                'bg-red-100 text-red-700' => in_array($user->status, ['rejected', 'suspended']),
                                            ])">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                        @if ($user->isAdmin())
                                            <span class="ml-1 rounded-full bg-slate-100 text-slate-700 px-2 py-0.5 text-xs">Admin</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            @if ($user->status !== 'approved')
                                                <x-admin-action :action="route('admin.members.status', $user)" field="status" value="approved" label="Approve" class="text-green-700" />
                                            @endif
                                            @if ($user->status === 'pending')
                                                <x-admin-action :action="route('admin.members.status', $user)" field="status" value="rejected" label="Reject" class="text-red-600" />
                                            @endif
                                            @if ($user->status === 'approved')
                                                <x-admin-action :action="route('admin.members.status', $user)" field="status" value="suspended" label="Suspend" class="text-red-600" />
                                            @endif
                                            @if ($user->isAdmin())
                                                <x-admin-action :action="route('admin.members.role', $user)" field="role" value="member" label="Remove admin" class="text-gray-600" />
                                            @else
                                                <x-admin-action :action="route('admin.members.role', $user)" field="role" value="admin" label="Make admin" class="text-slate-700" />
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div>{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
