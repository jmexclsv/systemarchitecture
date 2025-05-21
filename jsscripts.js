document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.section');

    // Function to handle navigation
    function navigateToSection(targetId) {
        sections.forEach(section => {
            section.classList.remove('active');
            if (section.id === targetId) {
                section.classList.add('active');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + targetId) {
                link.classList.add('active');
            }
        });

        // Scroll to top when changing sections
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Add click event listeners to navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            navigateToSection(targetId);
        });
    });

    // Image Slider Functionality
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlider();
    }

    function updateSlider() {
        slider.style.transform = `translateX(-${currentSlide * 33.33}%)`;
    }

    // Auto slide every 5 seconds
    setInterval(nextSlide, 5000);

    // Logout modal functionality
    const logoutBtn = document.querySelector('.logout-btn');
    const logoutModal = document.querySelector('.logout-modal');
    const logoutConfirm = document.getElementById('logout-confirm');
    const logoutCancel = document.getElementById('logout-cancel');

    logoutBtn.addEventListener('click', function() {
        logoutModal.style.display = 'flex';
    });

    logoutCancel.addEventListener('click', function() {
        logoutModal.style.display = 'none';
    });

    logoutConfirm.addEventListener('click', function() {
        window.location.href = 'includes/logout.php';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === logoutModal) {
            logoutModal.style.display = 'none';
        }
    });

    // Products functionality
    const productContainer = document.querySelector('.products-container');
    const categoryButtons = document.querySelectorAll('.category-btn');
    let currentCategory = 'tshirt';

    // Load products for the current category
    function loadProducts(category) {
        // Clear existing products
        productContainer.innerHTML = '';
        
        // Fetch products via AJAX
        fetch(`includes/get_products.php?category=${category}`)
            .then(response => response.json())
            .then(products => {
                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.classList.add('product-card');
                    
                    productCard.innerHTML = `
                        <div class="product-image">
                            <img src="${product.image}" alt="${product.name}">
                        </div>
                        <div class="product-info">
                            <h3>${product.name}</h3>
                            <p>₱${product.price.toFixed(2)}</p>
                            <button class="add-to-cart-btn" data-id="${product.id}">Add to Cart</button>
                        </div>
                    `;
                    
                    productContainer.appendChild(productCard);
                });
                
                // Attach event listeners to the add to cart buttons
                document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-id');
                        openProductModal(productId);
                    });
                });
            })
            .catch(error => {
                console.error('Error loading products:', error);
                productContainer.innerHTML = '<p>Error loading products. Please try again later.</p>';
            });
    }

    // Change active category
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            loadProducts(currentCategory);
        });
    });

    // Load initial products for the default category
    loadProducts(currentCategory);

    // Product detail modal functionality
    const productModal = document.getElementById('product-modal');
    const closeModal = document.querySelector('.close-modal');
    const productDetail = document.querySelector('.product-detail');

    function openProductModal(productId) {
        // Fetch product details
        fetch(`includes/get_product_detail.php?id=${productId}`)
            .then(response => response.json())
            .then(product => {
                productDetail.innerHTML = `
                    <div class="detail-image">
                        <img src="${product.image}" alt="${product.name}">
                    </div>
                    <div class="detail-info">
                        <h3>${product.name}</h3>
                        <p>${product.description}</p>
                        <p class="product-price">₱${product.price.toFixed(2)}</p>
                        
                        <div class="product-options">
                            <label class="option-label">Size:</label>
                            <div class="size-options">
                                <div class="size-option" data-size="S">S</div>
                                <div class="size-option" data-size="M">M</div>
                                <div class="size-option" data-size="L">L</div>
                                <div class="size-option" data-size="XL">XL</div>
                                <div class="size-option" data-size="2XL">2XL</div>
                                <div class="size-option" data-size="3XL">3XL</div>
                            </div>
                            
                            <label class="option-label">Color:</label>
                            <div class="color-options">
                                <div class="color-option" data-color="black" style="background-color: #000; color: #fff;">Black</div>
                                <div class="color-option" data-color="white" style="background-color: #fff; color: #000;">White</div>
                                <div class="color-option" data-color="gray" style="background-color: #888; color: #fff;">Gray</div>
                                <div class="color-option" data-color="red" style="background-color: #f00; color: #fff;">Red</div>
                            </div>
                            
                            <label class="option-label">Quantity:</label>
                            <div class="quantity-control">
                                <button class="quantity-btn minus">-</button>
                                <input type="number" class="quantity-input" value="1" min="1" max="99">
                                <button class="quantity-btn plus">+</button>
                            </div>
                            
                            <button class="add-to-cart-detail-btn" data-id="${product.id}">Add to Cart</button>
                        </div>
                    </div>
                `;
                
                // Display the modal
                productModal.style.display = 'block';
                
                // Size selection functionality
                const sizeOptions = document.querySelectorAll('.size-option');
                sizeOptions[0].classList.add('selected'); // Default to first size
                sizeOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        sizeOptions.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');
                    });
                });
                
                // Color selection functionality
                const colorOptions = document.querySelectorAll('.color-option');
                colorOptions[0].classList.add('selected'); // Default to first color
                colorOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        colorOptions.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');
                    });
                });
                
                // Quantity controls
                const minusBtn = document.querySelector('.quantity-btn.minus');
                const plusBtn = document.querySelector('.quantity-btn.plus');
                const quantityInput = document.querySelector('.quantity-input');
                
                minusBtn.addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                    }
                });
                
                plusBtn.addEventListener('click', function() {
                    let value = parseInt(quantityInput.value);
                    if (value < 99) {
                        quantityInput.value = value + 1;
                    }
                });
                
                // Add to cart functionality
                const addToCartDetailBtn = document.querySelector('.add-to-cart-detail-btn');
                addToCartDetailBtn.addEventListener('click', function() {
                    const selectedSize = document.querySelector('.size-option.selected').getAttribute('data-size');
                    const selectedColor = document.querySelector('.color-option.selected').getAttribute('data-color');
                    const quantity = parseInt(document.querySelector('.quantity-input').value);
                    
                    const productData = {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        size: selectedSize,
                        color: selectedColor,
                        quantity: quantity,
                        image: product.image
                    };
                    
                    // Send data to add_to_cart.php using Fetch API
                    fetch('includes/add_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(productData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Product added to cart!');
                            // Update cart count
                            document.querySelector('.cart-count').textContent = data.cartCount;
                            // Close modal
                            productModal.style.display = 'none';
                        } else {
                            alert('Failed to add product to cart. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching product details:', error);
                productDetail.innerHTML = '<p>Error loading product details. Please try again later.</p>';
            });
    }

    // Close modal functionality
    closeModal.addEventListener('click', function() {
        productModal.style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === productModal) {
            productModal.style.display = 'none';
        }
    });
});