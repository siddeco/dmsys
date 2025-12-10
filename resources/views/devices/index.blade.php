@extends('layouts.admin')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Devices List') }}</h3>

        <div class="card-tools">
            <a href="{{ route('devices.create') }}" class="btn btn-primary btn-sm">
                + {{ __('Add Device') }}
            </a>
        </div>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Serial Number') }}</th>
                    <th>{{ __('Model') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($devices as $device)
                    <tr>
                        <td>{{ $device->name }}</td>
                        <td>{{ $device->serial_number }}</td>
                        <td>{{ $device->model }}</td>
                        <td>{{ __(''.$device->status) }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">{{ __('Edit') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">{{ __('No devices found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $devices->links() }}
        </div>
    </div>
</div>

@endsection
