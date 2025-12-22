@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-primary text-white rounded hover:bg-primary-foreground transition">Back to Users</a>
    </div>
    <div class="bg-white border border-border rounded-lg shadow overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-sm text-muted-foreground border-b bg-gray-50">
                    <th class="py-3 px-4 font-semibold">#</th>
                    <th class="py-3 px-4 font-semibold">Reporter</th>
                    <th class="py-3 px-4 font-semibold">Reported User</th>
                    <th class="py-3 px-4 font-semibold">Type</th>
                    <th class="py-3 px-4 font-semibold">Content</th>
                    <th class="py-3 px-4 font-semibold">Motive</th>
                    <th class="py-3 px-4 font-semibold">Description</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                    <th class="py-3 px-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                @php
                    $reportedUser = null;
                    if($report->reportable_type === 'post' || $report->reportable_type === 'comment') {
                        $content = \App\Models\Content::find($report->reportable_id);
                        $reportedUser = $content ? $content->ownerUser : null;
                    }
                @endphp
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="py-3 px-4 text-sm">{{ $report->id }}</td>
                    <td class="py-3 px-4 text-sm">{{ $report->user->name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4 text-sm">
                        @if($reportedUser)
                            <span class="font-medium text-gray-900">{{ $reportedUser->name }}</span>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-sm capitalize">{{ $report->reportable_type }}</td>
                    <td class="py-3 px-4 text-sm">
                        @if($report->reportable_type === 'post')
                            <a href="{{ route('posts.show', $report->reportable_id) }}" class="text-blue-600 hover:underline" target="_blank">View Post</a>
                        @elseif($report->reportable_type === 'comment')
                            @php
                                $comment = \App\Models\Content::find($report->reportable_id);
                                $postId = $comment ? $comment->reply_to : null;
                            @endphp
                            @if($postId)
                                <a href="{{ route('posts.show', $postId) }}" class="text-blue-600 hover:underline" target="_blank">View Comment</a>
                            @else
                                <span class="text-gray-400">Not found</span>
                            @endif
                        @endif
                    </td>
                    <td class="py-3 px-4 text-sm">{{ $report->motive }}</td>
                    <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $report->description }}">{{ $report->description }}</td>
                    <td class="py-3 px-4 text-sm">
                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                            @if($report->status=='pending') bg-yellow-100 text-yellow-800
                            @elseif($report->status=='reviewed') bg-green-100 text-green-800
                            @elseif($report->status=='dismissed') bg-gray-200 text-gray-600
                            @endif">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm">
                        <form method="POST" action="{{ route('admin.reports.updateStatus', $report->id) }}">
                            @csrf
                            <select name="status" class="border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary" onchange="this.form.submit()">
                                <option value="pending" @if($report->status=='pending') selected @endif>Pending</option>
                                <option value="reviewed" @if($report->status=='reviewed') selected @endif>Reviewed</option>
                                <option value="dismissed" @if($report->status=='dismissed') selected @endif>Dismissed</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
