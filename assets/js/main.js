// Edluxury Main JavaScript

// Cart functionality
(function () {
    // Reload loop guard
    const reloadCount = parseInt(sessionStorage.getItem('reload_count') || '0');
    const lastReload = parseInt(sessionStorage.getItem('last_reload') || '0');
    const now = Date.now();

    if (now - lastReload < 2000) { // If reloaded within 2s
        if (reloadCount > 3) {
            console.error('Reload loop detected and stopped.');
            sessionStorage.removeItem('reload_count');
            return;
        }
        sessionStorage.setItem('reload_count', (reloadCount + 1).toString());
    } else {
        sessionStorage.setItem('reload_count', '0');
    }
    sessionStorage.setItem('last_reload', now.toString());
})();

const Cart = {
    // Add to cart
    add: async function (productId, quantity = 1) {
        try {
            const response = await fetch(`${SITE_URL}/api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateCount(data.cart_count);
                this.showNotification('Product added to cart!', 'success');
            } else {
                this.showNotification(data.message || 'Failed to add product', 'error');
            }

            return data;
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('An error occurred', 'error');
        }
    },

    // Update cart item
    update: async function (productId, quantity) {
        try {
            const response = await fetch(`${SITE_URL}/api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'update',
                    product_id: productId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateCount(data.cart_count);
                // Reload page if on cart page
                if (window.location.pathname.includes('cart.php')) {
                    location.reload();
                }
            }

            return data;
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    },

    // Remove from cart
    remove: async function (productId) {
        if (!confirm('Remove this item from cart?')) return;

        try {
            const response = await fetch(`${SITE_URL}/api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'remove',
                    product_id: productId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateCount(data.cart_count);
                this.showNotification('Item removed from cart', 'success');
                // Remove item from DOM or reload
                const itemElement = document.getElementById(`cart-item-${productId}`);
                if (itemElement) {
                    itemElement.style.transition = 'all 0.3s ease';
                    itemElement.style.opacity = '0';
                    itemElement.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                } else {
                    location.reload();
                }
            }

            return data;
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    },

    // Clear entire cart
    clear: async function () {
        try {
            const response = await fetch(`${SITE_URL}/api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'clear'
                })
            });

            const data = await response.json();

            if (data.success) {
                this.updateCount(0);
                location.reload();
            }

            return data;
        } catch (error) {
            console.error('Error clearing cart:', error);
        }
    },

    // Update cart count in header
    updateCount: function (count) {
        const cartCountElement = document.getElementById('cartCount');
        if (cartCountElement) {
            cartCountElement.textContent = count;
            if (count > 0) {
                cartCountElement.style.display = 'flex';
            } else {
                cartCountElement.style.display = 'none';
            }
        }
    },

    // Show notification
    showNotification: function (message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
};

const Wishlist = {
    // Key for localStorage
    STORAGE_KEY: 'edluxury_wishlist',

    // Get all items
    getItems: function () {
        return JSON.parse(localStorage.getItem(this.STORAGE_KEY) || '[]');
    },

    // Toggle item in wishlist
    toggle: function (product) {
        let items = this.getItems();
        const index = items.findIndex(item => item.id == product.id);

        if (index > -1) {
            // Remove if exists
            items.splice(index, 1);
            this.showNotification('Removed from wishlist', 'info');
        } else {
            // Add if not exists
            items.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                slug: product.slug
            });
            this.showNotification('Added to wishlist', 'success');
        }

        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(items));
        this.updateCount();
        this.updateButtons();

        // If on wishlist page, re-render
        if (window.location.pathname.includes('wishlist.php')) {
            this.renderPage();
        }
    },

    // Remove by ID
    remove: function (productId) {
        let items = this.getItems();
        items = items.filter(item => item.id != productId);
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(items));
        this.updateCount();
        this.updateButtons();

        if (window.location.pathname.includes('wishlist.php')) {
            this.renderPage();
        }
    },

    // Update wishlist badge count
    updateCount: function () {
        const items = this.getItems();
        const count = items.length;

        // Find or create badge
        let badge = document.getElementById('wishlistCount');
        if (!badge) {
            const wishlistBtn = document.querySelector('.sh-action-btn[href*="wishlist.php"]');
            if (wishlistBtn) {
                badge = document.createElement('span');
                badge.id = 'wishlistCount';
                badge.className = 'sh-cart-count';
                wishlistBtn.appendChild(badge);
            }
        }

        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    },

    // Update heart icons across the page
    updateButtons: function () {
        const items = this.getItems();
        const ids = items.map(item => item.id.toString());

        document.querySelectorAll('.btn-wishlist-toggle').forEach(btn => {
            const id = btn.dataset.id;
            const icon = btn.querySelector('i');
            if (ids.includes(id)) {
                btn.classList.add('active');
                icon.classList.replace('bi-heart', 'bi-heart-fill');
            } else {
                btn.classList.remove('active');
                icon.classList.replace('bi-heart-fill', 'bi-heart');
            }
        });
    },

    // Render wishlist page items
    renderPage: function () {
        const container = document.getElementById('wishlist-container');
        if (!container) return;

        const items = this.getItems();

        if (items.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center empty-wishlist-state py-5" data-aos="fade-up">
                    <i class="bi bi-heart empty-wishlist-icon"></i>
                    <h2 class="fw-bold">Your wishlist is empty</h2>
                    <p class="text-muted mb-4">You haven't added any products to your wishlist yet.</p>
                    <a href="${SITE_URL}/products.php" class="sh-btn sh-btn-primary rounded-pill px-5">Explore Products</a>
                </div>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            html += `
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                    <div class="wishlist-item-card shadow-sm rounded-4 overflow-hidden h-100 position-relative">
                        <button class="remove-wishlist-item" onclick="Wishlist.remove('${item.id}')" style="position: absolute; top: 10px; right: 10px; z-index: 10; border: none; background: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); color: #999;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div class="wishlist-item-media" style="aspect-ratio: 1/1; overflow: hidden;">
                            <a href="${SITE_URL}/product.php?slug=${item.slug}">
                                <img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                        </div>
                        <div class="wishlist-item-content p-3">
                            <a href="${SITE_URL}/product.php?slug=${item.slug}" class="wishlist-item-title d-block text-truncate mb-2 text-decoration-none text-dark fw-bold">${item.name}</a>
                            <div class="wishlist-item-price text-primary mb-3">${item.price}</div>
                            <div class="wishlist-actions">
                                <button class="btn btn-dark w-100 rounded-pill py-2 small fw-bold" onclick="Cart.add('${item.id}')">
                                    <i class="bi bi-bag-plus me-1"></i> ADD TO CART
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;

        if (window.AOS) {
            AOS.refresh();
        }
    },

    showNotification: function (message, type = 'success') {
        Cart.showNotification(message, type);
    }
};

// Add to cart button handlers
document.addEventListener('DOMContentLoaded', function () {
    // Add to cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = this.dataset.quantity || 1;

            Cart.add(productId, quantity);
        });
    });

    // Wishlist toggle buttons
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-wishlist-toggle');
        if (btn) {
            e.preventDefault();
            const product = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                price: btn.dataset.price,
                image: btn.dataset.image,
                slug: btn.dataset.slug
            };
            Wishlist.toggle(product);
        }
    });

    // Initialize counts
    Wishlist.updateCount();
    Wishlist.updateButtons();

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                document.getElementById('searchResults').innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`${SITE_URL}/api/search.php?q=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (data.success && data.products.length > 0) {
                        let html = '<div class="list-group position-absolute w-100 shadow" style="top: 100%; z-index: 1000;">';
                        data.products.forEach(product => {
                            html += `
                                <a href="${SITE_URL}/product.php?slug=${product.slug}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        ${product.primary_image ? `<img src="${SITE_URL}/uploads/${product.primary_image}" alt="${product.name}" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">` : ''}
                                        <div>
                                            <div class="fw-bold">${product.name}</div>
                                            <small class="text-primary">AED ${product.price}</small>
                                        </div>
                                    </div>
                                </a>
                            `;
                        });
                        html += '</div>';
                        document.getElementById('searchResults').innerHTML = html;
                    } else {
                        document.getElementById('searchResults').innerHTML = '';
                    }
                } catch (error) {
                    console.error('Search error:', error);
                }
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target)) {
                document.getElementById('searchResults').innerHTML = '';
            }
        });
    }

    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function () {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');

            // Close all other answers
            document.querySelectorAll('.faq-answer').forEach(a => {
                if (a !== answer) {
                    a.classList.remove('active');
                }
            });

            // Toggle current answer
            answer.classList.toggle('active');

            // Rotate icon
            if (answer.classList.contains('active')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    // Circular Collections Drag-to-Scroll
    const slider = document.querySelector('.overflow-auto.no-scrollbar');
    if (slider) {
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; //scroll-fast
            slider.scrollLeft = scrollLeft - walk;
        });
    }

    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img.lazy').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// Quantity input handlers for cart page
function updateQuantity(productId, quantity) {
    if (quantity < 1) return;

    Cart.update(productId, quantity).then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeFromCart(productId) {
    if (confirm('Remove this item from cart?')) {
        Cart.remove(productId).then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Global Quick Buy Function 
function quickBuy(productId) {
    Cart.add(productId, 1).then(data => {
        if (data && data.success) {
            window.location.href = `${SITE_URL}/checkout.php`;
        }
    });
}

// Live Viewers Simulation (Shopify Style)
document.addEventListener('DOMContentLoaded', function () {
    const viewerElement = document.getElementById('liveViewerCount');
    if (viewerElement) {
        let count = parseInt(viewerElement.innerText) || 24;

        setInterval(() => {
            // Randomly fluctuate count between 18 and 45
            const change = Math.floor(Math.random() * 5) - 2; // -2 to +2
            count = Math.max(18, Math.min(45, count + change));
            viewerElement.innerText = count;
        }, 10000); // Update every 10 seconds
    }
});

// Social Proof: Recent Sales Notification
document.addEventListener('DOMContentLoaded', function () {
    const cities = ["Dubai", "Abu Dhabi", "Sharjah", "Ajman", "Ras Al Khaimah", "Fujairah"];
    const products = [
        "Luxury Gold Watch",
        "Premium Oud Fragrance",
        "Designer Sunglasses",
        "Silk Abaya Collection",
        "Modern Arabic Coffee Set",
        "Leather Minimalist Wallet"
    ];

    function showRecentSale() {
        const city = cities[Math.floor(Math.random() * cities.length)];
        const product = products[Math.floor(Math.random() * products.length)];

        const popup = document.createElement('div');
        popup.className = 'recent-sale-popup shadow-lg d-flex align-items-center animate-slide-up';
        popup.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 1000;
            border-left: 4px solid #D4AF37;
            font-size: 0.85rem;
            min-width: 280px;
        `;

        popup.innerHTML = `
            <div class="me-3">
                <i class="bi bi-cart-check-fill text-success fs-4"></i>
            </div>
            <div>
                <div class="fw-bold">Someone in ${city}</div>
                <div class="text-muted">Just purchased <span class="text-dark fw-bold">${product}</span></div>
                <div class="x-small text-muted mt-1">2 minutes ago</div>
            </div>
            <button type="button" class="btn-close ms-auto small" style="font-size: 0.6rem;"></button>
        `;

        document.body.appendChild(popup);

        popup.querySelector('.btn-close').addEventListener('click', () => popup.remove());

        setTimeout(() => {
            if (document.body.contains(popup)) {
                popup.style.opacity = '0';
                popup.style.transform = 'translateY(20px)';
                popup.style.transition = 'all 0.5s ease';
                setTimeout(() => popup.remove(), 500);
            }
        }, 6000);
    }

    // Show first sale after 5 seconds, then every 45-60 seconds
    setTimeout(showRecentSale, 5000);
    setInterval(showRecentSale, Math.random() * (60000 - 45000) + 45000);
});
// Header Scroll Effect - Shopify Style
document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('mainHeader');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 50) {
                header.classList.add('py-2', 'shadow');
                header.classList.remove('py-3');
            } else {
                header.classList.remove('py-2', 'shadow');
                header.classList.add('py-3');
            }
        });
    }
});
