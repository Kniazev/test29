@component('mail::message')
    Car **{{ $car->brand->name }} {{ $car->carModel->name }}** is linked to your account.

    - Year: {{ $car->year ?? '-' }}
    - Mileage: {{ $car->mileage ?? '-' }}
    - Color: {{ $car->color ?? '-' }}
@endcomponent
