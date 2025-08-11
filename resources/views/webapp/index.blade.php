@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-7">
    <h4 class="mb-3">{{ $restaurant->name }} — Menyu</h4>
    @foreach($categories as $cat)
      <div class="mb-4">
        <h5 class="border-bottom pb-1">{{ $cat->name }}</h5>
        <div class="list-group">
          @foreach(($items[$cat->id] ?? []) as $it)
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">{{ $it->name }}</div>
                <div class="text-muted small">{{ number_format($it->price/100, 2) }} UZS</div>
              </div>
              <button class="btn btn-sm btn-outline-primary" onclick="addToCart({ id: {{ $it->id }}, name: '{{ addslashes($it->name) }}', price: {{ $it->price }} })">Qo‘shish</button>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
  <div class="col-md-5">
    <h5>Savatcha</h5>
    <ul id="cart-list" class="list-group mb-3"></ul>
    <div class="d-flex justify-content-between">
      <div class="fw-semibold">Jami:</div>
      <div class="fw-semibold" id="cart-total">0 UZS</div>
    </div>
    <form class="mt-3" method="post" action="{{ route('webapp.checkout', ['slug' => $restaurant->slug]) }}" onsubmit="return syncCart();">
      @csrf
      <input type="hidden" name="cart_json" id="cart-json">
      <input type="hidden" name="chat_id" id="chat-id">
      <input type="hidden" name="type" value="pickup">
      <button class="btn btn-success w-100">Buyurtma berish</button>
    </form>
  </div>
</div>

<script src="https://telegram.org/js/telegram-web-app.js"></script>
<script>
  const tg = window.Telegram?.WebApp;
  if (tg) {
    tg.ready();
    try {
      const user = tg.initDataUnsafe?.user;
      if (user && user.id) {
        document.getElementById('chat-id').value = user.id;
      }
    } catch (e) {}
  }

  const cart = [];

  function addToCart(item) {
    const idx = cart.findIndex(r => r.id === item.id);
    if (idx === -1) cart.push({ ...item, qty: 1 });
    else cart[idx].qty += 1;
    renderCart();
  }

  function changeQty(id, delta) {
    const idx = cart.findIndex(r => r.id === id);
    if (idx === -1) return;
    cart[idx].qty += delta;
    if (cart[idx].qty <= 0) cart.splice(idx, 1);
    renderCart();
  }

  function renderCart() {
    const list = document.getElementById('cart-list');
    list.innerHTML = '';
    let total = 0;
    cart.forEach(row => {
      total += row.price * row.qty;
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      li.innerHTML = `
        <div>
          <div class=\"fw-semibold\">${row.name}</div>
          <div class=\"small text-muted\">${(row.price/100).toFixed(2)} UZS</div>
        </div>
        <div class=\"btn-group\" role=\"group\">
          <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\" onclick=\"changeQty(${row.id},-1)\">-</button>
          <span class=\"px-2\">${row.qty}</span>
          <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\" onclick=\"changeQty(${row.id},1)\">+</button>
        </div>`;
      list.appendChild(li);
    });
    document.getElementById('cart-total').innerText = new Intl.NumberFormat('uz-UZ').format(total) + ' UZS';
  }

  function syncCart() {
    document.getElementById('cart-json').value = JSON.stringify(cart);
    return cart.length > 0;
  }
</script>
@endsection 