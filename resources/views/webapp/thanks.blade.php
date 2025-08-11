@extends('layouts.app')

@section('content')
<div class="text-center py-5">
  <h3>Rahmat!</h3>
  <p>Buyurtmangiz qabul qilindi. Tez orada aloqaga chiqamiz.</p>
  <a href="{{ route('webapp.index', ['slug' => $restaurant->slug]) }}" class="btn btn-primary mt-3">Menyuga qaytish</a>
</div>
@endsection 