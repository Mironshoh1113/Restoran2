@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Kategoriyalar — {{ $restaurant->name }}</h4>
  <a href="{{ route('webapp.index', ['slug' => $restaurant->slug]) }}" class="btn btn-sm btn-outline-secondary">WebApp</a>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header">Yangi kategoriya</div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.menu.categories.store', ['slug' => $restaurant->slug]) }}">
          @csrf
          <div class="mb-2">
            <input class="form-control" name="name" placeholder="Nom" required>
          </div>
          <div class="mb-2">
            <input class="form-control" name="sort" placeholder="Sort" type="number" value="0">
          </div>
          <button class="btn btn-primary">Saqlash</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="list-group">
      @foreach($categories as $cat)
        <div class="list-group-item">
          <form class="row g-2 align-items-center" method="post" action="{{ route('admin.menu.categories.update', ['slug' => $restaurant->slug, 'category' => $cat->id]) }}">
            @csrf
            @method('PUT')
            <div class="col-6"><input name="name" class="form-control" value="{{ $cat->name }}"></div>
            <div class="col-3"><input name="sort" class="form-control" type="number" value="{{ $cat->sort }}"></div>
            <div class="col-3 d-flex gap-2">
              <button class="btn btn-sm btn-success">OK</button>
              <form method="post" action="{{ route('admin.menu.categories.destroy', ['slug' => $restaurant->slug, 'category' => $cat->id]) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
              </form>
            </div>
          </form>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection 