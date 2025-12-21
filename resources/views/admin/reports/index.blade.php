@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-primary text-white rounded hover:bg-primary-foreground transition">Back to Users</a>
    </div>
    <div class="bg-white border border-border rounded-lg shadow p-6">
        <table class="w-full text-left">
            <thead>
                <tr class="text-sm text-muted-foreground border-b">
                    <th class="py-2">#</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Reported ID</th>
                    <th>Motive</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr class="border-t hover:bg-gray-50">
                    <td class="py-2">{{ $report->id }}</td>
                    <td>{{ $report->user->name ?? 'Unknown' }}</td>
                    <td class="capitalize">{{ $report->reportable_type }}</td>
                    <td>{{ $report->reportable_id }}</td>
                    <td>{{ $report->motive }}</td>
                    <td>{{ $report->description }}</td>
                    <td>
                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                            @if($report->status=='pending') bg-yellow-100 text-yellow-800
                            @elseif($report->status=='reviewed') bg-green-100 text-green-800
                            @elseif($report->status=='dismissed') bg-gray-200 text-gray-600
                            @endif">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.reports.updateStatus', $report->id) }}">
                            @csrf
                            <select name="status" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
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
