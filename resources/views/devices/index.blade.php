@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{ __('Devices List') }}</h3>
        <a href="{{ route('devices.create') }}" class="btn btn-primary">+ {{ __('Add Device') }}</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-bordered mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Serial Number') }}</th>
                        <th>{{ __('Model') }}</th>
                        <th>{{ __('Project') }}</th>
                        <th>City</th>
                        <th>{{ __('Status') }}</th>
                        <th width="120">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($devices as $device)
                        <tr>
                            <td>{{ $device->id }}</td>
                            <td>{{ $device->name['en'] ?? 'N/A' }}</td>
                            <td>{{ $device->serial_number }}</td>
                            <td>{{ $device->model }}</td>

                            <td>
                                {{ $device->project->name ?? '-' }}
                            </td>
                            <td>{{ $device->city }}</td>

                            <td>
                                <span class="badge bg-info">{{ $device->status }}</span>
                            </td>

                            <td>
                                <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                {{ __('No devices found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $devices->links() }}
        </div>
    </div>

</div>

@endsection
