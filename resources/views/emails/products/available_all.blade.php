@component('mail::message')
# 🛒 Products Currently Available

Hi there!  
Here are the products that are now back in stock:

@foreach ($availableProducts as $product)
- **{{ $product->name }}**

@endforeach

@component('mail::button', ['url' => route('home.index')])
Shop Now
@endcomponent

Thanks for staying with us,  
**{{ config('app.name') }}**
@endcomponent
