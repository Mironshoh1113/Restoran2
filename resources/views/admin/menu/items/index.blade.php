@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Mahsulotlar — {{ $restaurant->name }}</h4>
  <a href="{{ route('webapp.index', ['slug' => $restaurant->slug]) }}" class="btn btn-sm btn-outline-secondary">WebApp</a>
</div>

<div class="card mb-4">
  <div class="card-header">Yangi mahsulot</div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.menu.items.store', ['slug' => $restaurant->slug]) }}" class="row g-2">
      @csrf
      <div class="col-md-3">
        <select class="form-select" name="category_id" required>
          @foreach($categories as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <input class="form-control" name="name" placeholder="Nom" required>
      </div>
      <div class="col-md-2">
        <input class="form-control" name="price" placeholder="Narx (UZS)" required type="number" min="0">
      </div>
      <div class="col-md-2">
        <input class="form-control" name="sku" placeholder="SKU">
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Qo‘shish</button>
      </div>
    </form>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Kategoriya</th>
        <th>Nom</th>
        <th>Narx</th>
        <th>Holat</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach($items as $it)
      <tr>
        <td>{{ $it->id }}</td>
        <td>{{ optional($categories->firstWhere('id', $it->category_id))->name }}</td>
        <td>{{ $it->name }}</td>
        <td>{{ number_format($it->price/100, 2) }} UZS</td>
        <td>{{ $it->is_active ? 'Active' : 'Inactive' }}</td>
        <td class="text-end">
          <form method="post" action="{{ route('admin.menu.items.destroy', ['slug' => $restaurant->slug, 'item' => $it->id]) }}">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection 