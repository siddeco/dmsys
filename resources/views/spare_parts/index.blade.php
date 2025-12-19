@extends('layouts.admin')

@section('content')

    <div class="container mt-4">

        @can('view spare parts')

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Spare Parts Inventory</h3>

                @can('manage spare parts')
                    <a href="{{ route('spare_parts.create') }}" class="btn btn-primary">
                        + Add Spare Part
                    </a>
                @endcan

            </div>

            <!-- Success -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Parts Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">

                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Part Number</th>
                                <th>Manufacturer</th>
                                <th>For Device</th>
                                <th>Quantity</th>
                                <th>Low Stock?</th>
                                <th width="140">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($parts as $part)
                                <tr>
                                    <td>{{ $part->id }}</td>

                                    <td>{{ $part->name }}</td>

                                    <td>{{ $part->part_number ?? '—' }}</td>

                                    <td>{{ $part->manufacturer ?? '—' }}</td>

                                    <td>
                                        @if ($part->device)
                                            {{ $part->device->name['en'] ?? 'Device' }}
                                            <br>
                                            <small class="text-muted">SN: {{ $part->device->serial_number }}</small>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>{{ $part->quantity }}</td>

                                    <td>
                                        @if ($part->isLow())
                                            <span class="badge bg-danger">Low</span>
                                        @else
                                            <span class="badge bg-success">OK</span>
                                        @endif
                                    </td>

                                    <td>

                                        @can('edit spare parts')
                                            <a href="{{ route('spare_parts.edit', $part->id) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete spare parts')
                                            <form action="{{ route('spare_parts.delete', $part->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this part?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3">
                                        No spare parts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="card-footer">
                    {{ $parts->links() }}
                </div>
            </div>

        @else

            <!-- No Permission -->
            <div class="alert alert-danger mt-4">
                You are not authorized to view spare parts.
            </div>

        @endcan

    </div>

@endsection