@extends('layouts.admin')

@section('content')

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
                    <input type="text" name="name_en" class="form-control" required>
                </div>

                {{-- Name in Arabic --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Name (Arabic)') }}</label>
                    <input type="text" name="name_ar" class="form-control" required>
                </div>

                {{-- Serial Number --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Serial Number') }}</label>
                    <input type="text" name="serial_number" class="form-control" required>
                </div>

                {{-- Model --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Model') }}</label>
                    <input type="text" name="model" class="form-control">
                </div>

                {{-- Manufacturer --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Manufacturer') }}</label>
                    <input type="text" name="manufacturer" class="form-control">
                </div>

                {{-- Location --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Location') }}</label>
                    <input type="text" name="location" class="form-control">
                </div>

                {{-- Installation Date --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Installation Date') }}</label>
                    <input type="date" name="installation_date" class="form-control">
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label>{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                        <option value="active">{{ __('Active') }}</option>
                        <option value="inactive">{{ __('Inactive') }}</option>
                        <option value="under_maintenance">{{ __('Under Maintenance') }}</option>
                        <option value="out_of_service">{{ __('Out of Service') }}</option>
                    </select>
                </div>

            </div>

            <div class="text-center mt-4">
                <button class="btn btn-primary">{{ __('Save') }}</button>
            </div>

        </form>

    </div>
</div>

@endsection
