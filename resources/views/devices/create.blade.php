@extends('layouts.admin')

@section('content')

@can('create devices')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Add Device') }}</h3>
    </div>

    <div class="card-body">

        <form action="{{ route('devices.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- Name in English --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Name (English)') }}</label>
                    <input type="text"
                           name="name_en"
                           class="form-control"
                           value="{{ old('name_en') }}"
                           required>
                </div>

                {{-- Name in Arabic --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Name (Arabic)') }}</label>
                    <input type="text"
                           name="name_ar"
                           class="form-control"
                           value="{{ old('name_ar') }}"
                           required>
                </div>

                {{-- Serial Number --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Serial Number') }}</label>
                    <input type="text"
                           name="serial_number"
                           class="form-control"
                           value="{{ old('serial_number') }}"
                           required>
                </div>

                {{-- Model --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Model') }}</label>
                    <input type="text"
                           name="model"
                           class="form-control"
                           value="{{ old('model') }}">
                </div>

                {{-- Manufacturer --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Manufacturer') }}</label>
                    <input type="text"
                           name="manufacturer"
                           class="form-control"
                           value="{{ old('manufacturer') }}">
                </div>

                {{-- Location (Hospital / Center name) --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Location') }}</label>
                    <input type="text"
                           name="location"
                           class="form-control"
                           value="{{ old('location') }}">
                </div>

                {{-- City --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('City') }}</label>
                    <select name="city" class="form-control" required>
                        <option value="">-- {{ __('Choose City') }} --</option>

                        @php
                            $cities = [
                                'Riyadh',
                                'Jeddah',
                                'Makkah',
                                'Madinah',
                                'Tabuk',
                                'Qassim',
                                'Hail',
                                'Asir',
                                'Jazan',
                                'Najran',
                                'Al Jouf',
                                'Northern Borders'
                            ];
                        @endphp

                        @foreach ($cities as $city)
                            <option value="{{ $city }}"
                                {{ old('city') === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Installation Date --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Installation Date') }}</label>
                    <input type="date"
                           name="installation_date"
                           class="form-control"
                           value="{{ old('installation_date') }}">
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                            {{ __('Active') }}
                        </option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                            {{ __('Inactive') }}
                        </option>
                        <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>
                            {{ __('Under Maintenance') }}
                        </option>
                        <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>
                            {{ __('Out of Service') }}
                        </option>
                    </select>
                </div>

                {{-- Project Selection --}}
                <div class="col-md-12 mb-3">
                    <label>{{ __('Project') }}</label>
                    <select name="project_id" class="form-control" required>
                        <option value="">-- {{ __('Choose Project') }} --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                                @if($project->client)
                                    ({{ $project->client }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Save') }}
                </button>
            </div>

        </form>

    </div>
</div>

@else

<div class="alert alert-danger mt-4">
    {{ __('You are not authorized to add devices.') }}
</div>

@endcan

@endsection
