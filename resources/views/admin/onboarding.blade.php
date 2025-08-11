@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Restoran va Bot ulash</div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.onboarding.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Restoran nomi</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
            <div class="form-text">Masalan: acme</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Telefon</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Manzil</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
          </div>
          <hr>
          <div class="mb-3">
            <label class="form-label">Telegram Bot token</label>
            <input type="text" name="bot_token" class="form-control" value="{{ old('bot_token') }}" required>
            <div class="form-text">BotFather dan olingan token</div>
          </div>
          <button class="btn btn-primary">Ulash va Webhook o‘rnatish</button>
        </form>

        @if ($errors->any())
          <div class="alert alert-danger mt-3">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection 