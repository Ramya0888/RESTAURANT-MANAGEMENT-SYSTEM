let cart = [];

function addToCart(id, name, price) {
    let item = cart.find(i => i.id === id);
    if (item) {
        item.quantity++;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }
    updateCart();
}

function updateCart() {
    let cartDiv = document.getElementById('cart');
    cartDiv.innerHTML = "";
    let total = 0;
    cart.forEach(item => {
        total += item.price * item.quantity;
        cartDiv.innerHTML += `<div class="cart-row">${item.name} - $${item.price} x ${item.quantity}
                <button class="cart-btn" onclick="removeItem(${item.id})">-</button></div>`;
    });
    document.getElementById('total').innerText = total.toFixed(2);
}

function removeItem(id) {
    let item = cart.find(i => i.id === id);
    if (item.quantity > 1) {
        item.quantity--;
    } else {
        cart = cart.filter(i => i.id !== id);
    }
    updateCart();
}

function placeOrder() {
    if (cart.length === 0) {
        alert("Cart is empty!");
        return;
    }

    fetch('process_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cart)
    }).then(() => {
        alert("Order placed successfully!");
        cart = [];
        updateCart();
    });
}