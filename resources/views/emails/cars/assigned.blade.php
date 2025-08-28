<x-mail::message>
# Здравствуйте, {{ $user->name }}!

Вам был назначен новый автомобиль.

**Марка:** {{ $car->brand->name }}
**Модель:** {{ $car->carModel->name }}
**Год:** {{ $car->year }}

Спасибо,
{{ config('app.name') }}
</x-mail::message>
