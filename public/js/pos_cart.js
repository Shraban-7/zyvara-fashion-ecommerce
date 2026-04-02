class PosCartManager {
    constructor() {
        this.apiUrl = "/admin/pos/cart";
        this.csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        this.init();
    }

    init() {
        this.loadCart();
        this.setupAddToCartListeners();
        this.setupCartActions();
    }

    getHeaders() {
        return {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": this.csrfToken,
            "X-Requested-With": "XMLHttpRequest",
        };
    }

    // ===============================
    // LOAD CART
    // ===============================
    async loadCart() {
        try {
            const response = await fetch(this.apiUrl, {
                method: "GET",
                headers: this.getHeaders(),
                credentials: "same-origin",
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartUI(data.cart);
            }
        } catch (error) {
            console.error("Load cart error:", error);
        }
    }

    // ===============================
    // ADD TO CART
    // ===============================
    async addToCart(productId, variantId = null, quantity = 1) {
        try {
            const response = await fetch(`${this.apiUrl}/add`, {
                method: "POST",
                headers: this.getHeaders(),
                credentials: "same-origin",
                body: JSON.stringify({
                    product_id: productId,
                    product_variant_id: variantId,
                    quantity: quantity,
                    is_pos: 1
                }),
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCount(data.cart.items_count);
                this.loadCart();
            }
        } catch (error) {
            console.error("Add error:", error);
        }
    }

    // ===============================
    // UPDATE QTY
    // ===============================
    async updateQuantity(itemId, quantity) {
        try {
            const response = await fetch(`${this.apiUrl}/update/${itemId}`, {
                method: "PUT",
                headers: this.getHeaders(),
                credentials: "same-origin",
                body: JSON.stringify({ quantity }),
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCount(data.cart.items_count);
                this.loadCart();
            }
        } catch (error) {
            console.error("Update error:", error);
        }
    }

    // ===============================
    // REMOVE ITEM
    // ===============================
    async removeItem(itemId) {
        try {
            const response = await fetch(`${this.apiUrl}/remove/${itemId}`, {
                method: "DELETE",
                headers: this.getHeaders(),
                credentials: "same-origin",
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCount(data.cart.items_count);
                this.loadCart();
            }
        } catch (error) {
            console.error("Remove error:", error);
        }
    }

    // ===============================
    // CLEAR CART
    // ===============================
    async clearCart() {
        try {
            const response = await fetch(`${this.apiUrl}/clear`, {
                method: "DELETE",
                headers: this.getHeaders(),
                credentials: "same-origin",
            });

            const data = await response.json();

            if (data.success) {
                this.loadCart();
            }
        } catch (error) {
            console.error("Clear error:", error);
        }
    }

    // ===============================
    // UI UPDATE
    // ===============================
    updateCartUI(cart) {
        const container = document.getElementById("cartItemsContainer");
        const emptyCart = document.getElementById("emptyCart");

        // ===== COUNT =====
        this.updateCartCount(cart.items_count);

        // ===== ITEMS =====
        if (!cart.items || cart.items.length === 0) {
            container.innerHTML = "";
            if (emptyCart) emptyCart.style.display = "flex";
        } else {
            if (emptyCart) emptyCart.style.display = "none";

            container.innerHTML = cart.items
                .map(item => this.getCartItemHTML(item))
                .join("");
        }

        // ===== BUTTONS =====
        const holdBtn = document.getElementById("holdOrderBtn");
        const completeBtn = document.getElementById("completeOrderBtn");

        if (cart.items && cart.items.length > 0) {
            holdBtn.disabled = false;
            completeBtn.disabled = false;
        } else {
            holdBtn.disabled = true;
            completeBtn.disabled = true;
        }

        // ===== CLEAR CART =====
        const clearBtn = document.getElementById("clearCartBtn");

        if (cart.items && cart.items.length > 0) {
            if (clearBtn) clearBtn.classList.remove("hidden");
        } else {
            if (clearBtn) clearBtn.classList.add("hidden");
        }

        // ===== TOTALS (WITH FRONTEND DISCOUNT) =====
        const subtotalEl = document.getElementById("subtotalAmount");
        const totalEl = document.getElementById("totalAmount");
        const discountEl = document.getElementById("discountDisplay");
        const discountRow = document.getElementById("discountRow");

        let subtotal = parseFloat(cart.subtotal || 0);

        // 🔥 GET DISCOUNT INPUT
        let discountValue = parseFloat($('#discountInput').val());
        let discountType = $('#discountType').val();

        let discount = 0;

        if (discountValue && discountValue > 0) {
            if (discountType === 'percent') {
                if (discountValue > 100) discountValue = 100;
                discount = subtotal * (discountValue / 100);
            } else {
                discount = Math.min(discountValue, subtotal);
            }
        }

        let total = Math.max(0, subtotal - discount);

        // ===== UPDATE UI =====
        if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2);
        if (totalEl) totalEl.textContent = total.toFixed(2);

        if (discount > 0) {
            if (discountEl) discountEl.textContent = discount.toFixed(2);
            if (discountRow) discountRow.classList.remove("hidden");
        } else {
            if (discountRow) discountRow.classList.add("hidden");
        }
    }

    updateCartCount(count) {
        const elements = document.querySelectorAll(
            "#cartItemCount, #cartCountBadge, #headerCartCount"
        );

        elements.forEach(el => {
            if (el) el.textContent = count;
        });
    }

    // ===============================
    // EVENTS
    // ===============================
    setupAddToCartListeners() {
        document.addEventListener("click", (e) => {
            const btn = e.target.closest("[data-add-to-cart]");
            if (!btn) return;

            const productId = btn.dataset.productId;
            const variantId = btn.dataset.variantId || null;

            this.addToCart(productId, variantId, 1);
        });
    }

    setupCartActions() {
        document.addEventListener("click", (e) => {

            const inc = e.target.closest(".increase-qty");
            const dec = e.target.closest(".decrease-qty");
            const del = e.target.closest(".remove-item");

            if (inc) {
                const id = inc.dataset.id;
                const input = document.querySelector(`.qty-input[data-id="${id}"]`);
                this.updateQuantity(id, parseInt(input.value) + 1);
            }

            if (dec) {
                const id = dec.dataset.id;
                const input = document.querySelector(`.qty-input[data-id="${id}"]`);
                const qty = parseInt(input.value);

                if (qty > 1) {
                    this.updateQuantity(id, qty - 1);
                } else {
                    this.removeItem(id);
                }
            }

            if (del) {
                const id = del.dataset.id;
                this.removeItem(id);
            }
        });

        // manual input change
        document.addEventListener("change", (e) => {
            if (e.target.classList.contains("qty-input")) {
                const id = e.target.dataset.id;
                const qty = parseInt(e.target.value);

                if (qty > 0) {
                    this.updateQuantity(id, qty);
                }
            }
        });
    }

    // ===============================
    // HTML
    // ===============================
    getCartItemHTML(item) {
        return `
        <div class="bg-gray-50 p-3 rounded border">
            <div class="flex gap-3">
                <img src="${item.product_image}" class="w-16 h-16 object-cover rounded">

                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-gray-900 truncate">${item.product_name}</h4>
                    <p class="text-xs text-gray-500">${item.variant_name ?? ''}</p>

                    <div class="flex gap-2 mt-2 items-center justify-between">
                        <button class="decrease-qty w-7 h-7 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center text-gray-700" data-id="${item.id}">-</button>

                        <input type="number" value="${item.quantity}" 
                            class="qty-input w-14 text-center py-1 border border-gray-300 rounded text-sm font-semibold"
                            data-id="${item.id}">

                        <button class="increase-qty w-7 h-7 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center text-gray-700" data-id="${item.id}">+</button>

                        <button class="remove-item ml-auto text-red-500 hover:text-red-700 p-1" data-id="${item.id}">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-sm font-bold text-gray-900">৳${(item.price * item.quantity)}</p>
                    <p class="text-xs text-gray-500">৳${item.price} each</p>
                </div>
            </div>
        </div>`;
    }

    getEmptyCartHTML() {
        return `<div class="text-center py-10 text-gray-500">Cart is empty</div>`;
    }

    showCartFooter() {
        document.getElementById("cartFooter")?.classList.remove("hidden");
    }

    hideCartFooter() {
        document.getElementById("cartFooter")?.classList.add("hidden");
    }
}

// INIT
document.addEventListener("DOMContentLoaded", () => {
    window.posCartManager = new PosCartManager();
});

// GLOBAL FUNCTION
function addToCart(product, variant) {
    if (window.posCartManager) {
        window.posCartManager.addToCart(product.id, variant.id, 1);
    }

    $('#variantModal').addClass('hidden');
}