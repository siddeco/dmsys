<div class="col-md-6 mb-3">
    <label>{{ __('City') }}</label>
    <select name="city" class="form-control" required>
        @foreach (['Riyadh','Jeddah','Makkah','Madinah','Tabuk','Qassim','Hail','Asir','Jazan','Najran','Al Jouf','Northern Borders'] as $city)
            <option value="{{ $city }}" {{ $device->city === $city ? 'selected' : '' }}>
                {{ $city }}
            </option>
        @endforeach
    </select>
</div>
