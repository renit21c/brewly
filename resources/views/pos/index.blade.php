<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
                <link rel="stylesheet" href="{{ asset('css/page-animate.css') }}">
            <link rel="stylesheet" href="{{ asset('css/button-hover.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('build/assets/brewly3.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Brewly - PoS Interface</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col page-animate">
        <!-- Header -->
        <div class="bg-cafe-coffee text-white p-4 shadow-md">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('build/assets/brewly2.png') }}" alt="Brewly Logo" class="h-8 w-8 object-contain">
                        <span class="text-2xl font-bold text-white">Brewly POS</span>
                    </div>
                    <p class="text-xs text-cafe-peach">Shift: {{ $activeShift->opened_at->setTimezone(config('app.timezone'))->format('H:i') }} | Cashier: {{ auth()->user()->name }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('shift.close') }}" class="flex items-center gap-2 bg-cafe-rust hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition font-semibold text-sm">
                        <i data-feather="log-out" class="w-4 h-4"></i>
                        Close Shift
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex gap-6 p-6 overflow-hidden">
            <!-- Left: Products Grid -->
            <div class="flex-1 overflow-y-auto">
                <h2 class="text-xl font-bold text-cafe-coffee mb-4">Select Products</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" id="productsContainer">
                    @forelse ($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border-2 border-cafe-latte hover:border-cafe-gold transition cursor-pointer product-card"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $product->price }}"
                            data-product-stock="{{ $product->stock }}">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                    <i data-feather="coffee" class="w-8 h-8 text-gray-400"></i>
                                </div>
                            @endif

                            <div class="p-3">
                                <h3 class="font-bold text-cafe-coffee text-sm line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-xs text-cafe-gold font-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-600 mt-1">Stock: {{ $product->stock }}</p>
                                <button class="w-full mt-2 bg-cafe-gold text-white text-xs font-bold py-2 rounded hover:bg-opacity-90 transition add-to-cart-btn">
                                    Add
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-12 text-gray-500">
                            <p>No products available</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Cart & Payment -->
            <div class="w-96 bg-white rounded-lg shadow-lg p-6 border-2 border-cafe-latte h-fit sticky top-6 max-h-[calc(100vh-120px)] overflow-y-auto">
                <!-- Cart Header -->
                <h2 class="text-xl font-bold text-cafe-coffee mb-4 flex items-center gap-2">
                    <i data-feather="shopping-cart" class="w-5 h-5"></i>
                    Cart
                </h2>

                <!-- Cart Items -->
                <div class="space-y-2 mb-4 max-h-40 overflow-y-auto" id="cartItems">
                    <p class="text-gray-500 text-sm text-center py-4">Cart is empty</p>
                </div>

                <!-- Divider -->
                <div class="border-t-2 border-cafe-latte my-4"></div>

                <!-- Order Type -->
                <div class="mb-4">
                    <label for="orderType" class="block text-xs font-semibold text-cafe-coffee mb-2">Order Type</label>
                    <select id="orderType" class="w-full px-3 py-2 border border-cafe-latte rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold text-xs">
                        <option value="">-- Select --</option>
                        @foreach ($orderTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pricing -->
                <div class="space-y-2 mb-4 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold text-cafe-coffee" id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-600">Tax ({{ config('charges.tax_rate') }}%):</span>
                        <input type="number" id="taxInput" min="0" step="100" value="0" class="w-20 px-2 py-1 border border-cafe-latte rounded text-right font-semibold bg-gray-100" readonly>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-600">Service ({{ config('charges.service_charge') }}%):</span>
                        <input type="number" id="serviceInput" min="0" step="100" value="0" class="w-20 px-2 py-1 border border-cafe-latte rounded text-right font-semibold bg-gray-100" readonly>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-600">Discount (%):</span>
                        <input type="number" id="discountInput" min="0" max="100" step="1" value="0" class="w-20 px-2 py-1 border border-cafe-latte rounded text-right font-semibold">
                    </div>
                    <div class="border-t-2 border-cafe-latte my-2"></div>
                    <div class="flex justify-between text-sm">
                        <span class="font-bold text-cafe-coffee">Total:</span>
                        <span class="font-bold text-cafe-gold text-lg" id="totalPrice">Rp 0</span>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-cafe-coffee mb-2">Payment Methods</label>
                    <div id="paymentMethods" class="space-y-2">
                        @foreach ($paymentMethods as $method)
                            <div class="flex items-center gap-2 p-2 border border-cafe-latte rounded-lg hover:bg-cafe-peach hover:bg-opacity-20 cursor-pointer payment-method"
                                data-method-id="{{ $method->id }}"
                                data-method-name="{{ $method->name }}">
                                <input type="radio" name="paymentMethodRadio" class="w-4 h-4 accent-cafe-gold" />
                                <span class="text-xs font-medium text-cafe-coffee flex-1">{{ $method->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Input -->
                <div id="paymentInputs" class="space-y-2 mb-4 max-h-32 overflow-y-auto"></div>

                <!-- Change Display -->
                <div id="changeDisplay" class="p-3 bg-green-100 border border-green-400 rounded-lg mb-4 hidden">
                    <p class="text-xs text-gray-600">Change</p>
                    <p class="text-xl font-bold text-green-700" id="changeAmount">Rp 0</p>
                </div>

                <!-- Checkout Button -->
                <button id="checkoutBtn" class="w-full bg-cafe-gold text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm" disabled>
                    Process Payment
                </button>

                <!-- Clear Cart Button -->
                <button id="clearCartBtn" class="w-full mt-2 border-2 border-cafe-latte text-cafe-coffee font-bold py-2 px-4 rounded-lg hover:bg-cafe-peach hover:bg-opacity-20 transition text-xs">
                    Clear Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <div class="mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-feather="check-circle" class="w-8 h-8 text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-cafe-coffee mb-2">Payment Successful!</h2>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Invoice:</span>
                    <span class="font-bold text-cafe-coffee" id="invoiceCode"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-bold text-cafe-gold" id="successTotal"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Change:</span>
                    <span class="font-bold text-cafe-coffee" id="successChange"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Time:</span>
                    <span class="font-semibold text-cafe-coffee" id="successTime"></span>
                </div>
            </div>

            <button onclick="location.reload()" class="w-full bg-cafe-gold text-white font-bold py-2 px-4 rounded-lg hover:bg-opacity-90 transition text-sm">
                New Transaction
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace();
        const cart = {};

        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const card = this.closest('.product-card');
                const id = card.dataset.productId;
                const name = card.dataset.productName;
                const price = parseFloat(card.dataset.productPrice);
                const stock = parseInt(card.dataset.productStock);

                if (cart[id]) {
                    if (cart[id].quantity < stock) cart[id].quantity++;
                    else alert('Insufficient stock');
                } else {
                    cart[id] = { name, price, quantity: 1, stock };
                }
                renderCart();
                updateTotals();
            });
        });

        function renderCart() {
            const cartItems = document.getElementById('cartItems');
            if (Object.keys(cart).length === 0) {
                cartItems.innerHTML = '<p class="text-gray-500 text-xs text-center py-4">Cart is empty</p>';
                document.getElementById('checkoutBtn').disabled = true;
                return;
            }
            document.getElementById('checkoutBtn').disabled = false;
            cartItems.innerHTML = Object.entries(cart).map(([id, item]) => `
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded text-xs">
                    <div class="flex-1">
                        <p class="font-medium text-cafe-coffee">${item.name}</p>
                        <p class="text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="changeQty('${id}', -1)" class="px-1 py-0 bg-cafe-latte text-white rounded text-xs">-</button>
                        <span class="w-4 text-center font-bold text-cafe-coffee">${item.quantity}</span>
                        <button onclick="changeQty('${id}', 1)" class="px-1 py-0 bg-cafe-gold text-white rounded text-xs" ${item.quantity >= item.stock ? 'disabled' : ''}>+</button>
                        <button onclick="removeItem('${id}')" class="px-1 py-0 bg-cafe-rust text-white rounded text-xs">✕</button>
                    </div>
                </div>
            `).join('');
            feather.replace();
        }

        function changeQty(id, change) {
            const newQty = cart[id].quantity + change;
            if (newQty > 0 && newQty <= cart[id].stock) {
                cart[id].quantity = newQty;
                renderCart();
                updateTotals();
            }
        }

        function removeItem(id) {
            delete cart[id];
            renderCart();
            updateTotals();
        }

        // Set tax and service charge rates from backend
        const TAX_RATE = {{ config('charges.tax_rate') }};
        const SERVICE_CHARGE = {{ config('charges.service_charge') }};

        function updateTotals() {
            let subtotal = 0;
            Object.values(cart).forEach(item => subtotal += item.price * item.quantity);
            // Calculate tax and service charge as percent of subtotal
            const tax = Math.round(subtotal * TAX_RATE / 100);
            const service = Math.round(subtotal * SERVICE_CHARGE / 100);
            document.getElementById('taxInput').value = tax;
            document.getElementById('serviceInput').value = service;
            // Discount as percent
            const discountPercent = parseFloat(document.getElementById('discountInput').value) || 0;
            const discount = Math.round(subtotal * discountPercent / 100);
            const total = subtotal + tax + service - discount;

            document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            document.getElementById('totalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            updatePaymentInputs(total);
        }

        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Uncheck all radios except the one clicked
                document.querySelectorAll('.payment-method input[type="radio"]').forEach(radio => {
                    radio.checked = false;
                });
                this.querySelector('input[type="radio"]').checked = true;
                updatePaymentInputs(parseFloat(document.getElementById('totalPrice').textContent.replace(/\D/g, '')) || 0);
            });
        });

        function updatePaymentInputs(total) {
            const container = document.getElementById('paymentInputs');
            const selected = Array.from(document.querySelectorAll('.payment-method input[type="radio"]:checked'))
                .map(cb => cb.closest('.payment-method'));

            if (selected.length === 0) {
                container.innerHTML = '';
                document.getElementById('changeDisplay').classList.add('hidden');
                return;
            }

            // Only one payment method allowed
            const method = selected[0];
            const isCash = method.dataset.methodName && method.dataset.methodName.toLowerCase().includes('cash');
            container.innerHTML = `
                <div class="p-2 bg-gray-50 rounded">
                    <label class="text-xs text-gray-600">${method.dataset.methodName}</label>
                    <input type="number" class="w-full px-2 py-1 border border-cafe-latte rounded text-xs font-bold payment-input" 
                        data-method-id="${method.dataset.methodId}"
                        value="${total}"
                        ${isCash ? '' : 'readonly'}>
                </div>
            `;

            const paymentInput = container.querySelector('.payment-input');

            if (isCash) {
                document.getElementById('changeDisplay').classList.remove('hidden');
                // Update change when input changes
                paymentInput.addEventListener('input', function() {
                    const paid = parseFloat(paymentInput.value) || 0;
                    const change = paid - total;
                    document.getElementById('changeAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(change, 0));
                });
                // Set initial change
                document.getElementById('changeAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(0);
            } else {
                document.getElementById('changeDisplay').classList.add('hidden');
            }
        }

        document.getElementById('clearCartBtn').addEventListener('click', function() {
            if (confirm('Clear all items?')) {
                Object.keys(cart).forEach(key => delete cart[key]);
                renderCart();
                updateTotals();
            }
        });

        document.getElementById('checkoutBtn').addEventListener('click', async function() {
            const orderTypeId = document.getElementById('orderType').value;
            if (!orderTypeId) { alert('Select order type'); return; }

            const tax = parseFloat(document.getElementById('taxInput').value) || 0;
            const service = parseFloat(document.getElementById('serviceInput').value) || 0;
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;

            let subtotal = 0;
            const items = [];
            Object.entries(cart).forEach(([id, item]) => {
                items.push({ product_id: id, quantity: item.quantity, variants: [] });
                subtotal += item.price * item.quantity;
            });

            const payments = [];
            document.querySelectorAll('.payment-input').forEach(input => {
                const amount = parseFloat(input.value);
                if (amount > 0) payments.push({ payment_method_id: input.dataset.methodId, amount: amount, reference: null });
            });

            if (payments.length === 0) { alert('Add payment method'); return; }
            if (payments.reduce((s, p) => s + p.amount, 0) < subtotal + tax + service - discount) { alert('Insufficient payment'); return; }

            try {
                const response = await axios.post('{{ route("pos.checkout") }}', {
                    items, order_type_id: orderTypeId, subtotal, tax, service_charge: service, discount, payments,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                });
                document.getElementById('invoiceCode').textContent = response.data.invoice_code;
                document.getElementById('successTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(response.data.total_price);
                document.getElementById('successChange').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(response.data.change_money);
                document.getElementById('successTime').textContent = new Date().toLocaleTimeString('id-ID');
                document.getElementById('successModal').classList.remove('hidden');
                Object.keys(cart).forEach(key => delete cart[key]);
                renderCart(); updateTotals();
            } catch (error) {
                alert(error.response?.data?.error || 'Payment failed');
            }
        });

        // Only discount is editable, so only listen to its change
        document.getElementById('discountInput').addEventListener('change', updateTotals);

        // Initial calculation
        updateTotals();
    </script>
</body>
</html>
