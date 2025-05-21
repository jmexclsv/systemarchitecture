
<?php
// Start session to manage cart and user login state
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: e-commerce.php');
    exit();
}

// Sample products data - in a real application this would come from a database
$products = [
    'tshirt' => [
        ['id' => 't1', 'name' => 'New Release Tee', 'price' => 750, 'image' => 't1.jpg'],
        ['id' => 't2', 'name' => 'Graphic Print Tee', 'price' => 750, 'image' => 't2.jpg'],
        ['id' => 't3', 'name' => 'Graphic Print Tee', 'price' => 750, 'image' => 't3.jpg'],
        ['id' => 't4', 'name' => 'Urban Style Tee', 'price' => 750, 'image' => 't4.jpg'],
        ['id' => 't5', 'name' => 'Streetwear Tee', 'price' => 750, 'image' => 't5.jpg'],
        ['id' => 't6', 'name' => 'Boxxy Fit Tee', 'price' => 750, 'image' => 't6.jpg'],
        ['id' => 't7', 'name' => 'Streetwear Tee', 'price' => 750, 'image' => 't7.jpg'],

    ],
    'hoodie' => [
        ['id' => 'h1', 'name' => 'Classic Pullover Hoodie', 'price' => 1500, 'image' => 'h1.jpg'],
        ['id' => 'h2', 'name' => 'Classic Zip Hoodie', 'price' => 1500, 'image' => 'h2.jpg'],
        ['id' => 'h3', 'name' => 'Graphic Print Hoodie', 'price' => 1500, 'image' => 'h3.jpg'],
        ['id' => 'h4', 'name' => 'Fleece Pullover Hoodie', 'price' => 1500, 'image' => 'h4.jpg'],
        ['id' => 'h5', 'name' => 'Fleece Zip Hoodie', 'price' => 1500, 'image' => 'h5.jpg'],
        ['id' => 'h6', 'name' => 'Cropped Hoodie', 'price' => 1500, 'image' => 'h6.jpg'],
        ['id' => 'h7', 'name' => 'Cropped Hoodie V2', 'price' => 1500, 'image' => 'h7.jpg'],
        ['id' => 'h8', 'name' => 'Pocketsnacks Version 2 Hoodie', 'price' => 1500, 'image' => 'h8.jfif'],
    ],
    'shorts' => [
        ['id' => 's1', 'name' => 'Pocket Snacks Raw Edge in Black Colorway', 'price' => 500, 'image' => 's1.jpg'],
        ['id' => 's2', 'name' => 'Pocket Snacks Raw Edge Shorts in Gray Colorway', 'price' => 500, 'image' => 's2.jpg'],
        ['id' => 's3', 'name' => 'Pocket Snacks Raw Edge Shorts in Green Camou Colorway', 'price' => 500, 'image' => 's3.jpg'],
    ],
    'poloshirt' => [
        ['id' => 'p1', 'name' => 'Quarter Zip Polo', 'price' => 1200, 'image' => 'p1.jpg'],
        ['id' => 'p2', 'name' => 'Quarter Zip Polo', 'price' => 1200, 'image' => 'p2.jpg'],

    ]
];

// Sample events data
$events = [
    ['id' => 'e1', 'name' => 'The Distinguished Gentleman\'s Ride ', 'date' => 'May 18, 2025', 'image' => 'dgr.jpg', 'description' => '3rd DISTINGUISHED GENTLEMAN’S RIDE here in ALBAY 🔥⚡⛰️🌋', 'link' => 'https://www.facebook.com/unifiedalbaycustoms/posts/pfbid0yLNHUacGvYr1Sk12bHP8kvXn7qVzdvsek1qbpNjwmvibHK82K6KACXQuZwbYkn2Rl'],
    ['id' => 'e2', 'name' => 'Dropping New Streetwears', 'date' => 'May 9, 2025', 'image' => 'pocketsnacks.jpg', 'description' => 'Don\'t miss our biggest drops this 2025.', 'link' => 'https://www.facebook.com/photo/?fbid=1114691367340952&set=a.674862024657224'],
    ['id' => 'e3', 'name' => 'The Originals Street Cafe Year 2', 'date' => 'April 26, 2025', 'image' => 'anniv.jpg', 'description' => 'Year 2 is Coming! A new way to experience coffee. Come Coffee Clubbing with us at @theoriginalstreetcafe_', 'link' => 'https://www.facebook.com/photo?fbid=621418164212604&set=a.154395314248227'],
    ['id' => 'e4', 'name' => '3-Day Sale', 'date' => 'March 20-22, 2025', 'image' => 'sale.jpg', 'description' => '3 DAY SALE ❗️ Up to 300 PHP OFF ❗️

Exclusive only at our Flagship Store at The Originals Street Cafe 🏁

March 20 - 22, 2025

Grab your favorite merch and drink a coffee☕️', 'link' => 'https://www.facebook.com/events/321654987/']
];

// Slider images
$sliderImages = [
    'pocketsnacks.jpg',
    'pocketsnacks2.jpg',
    'pocketsnacks3.jpg',
    'pocket.jpg'
];

// Process add to cart if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_size = $_POST['size'];
    $product_color = $_POST['color'];
    $product_quantity = $_POST['quantity'];
    
    // Create a unique cart item ID based on product ID, size, and color
    $cart_item_id = $product_id . '-' . $product_size . '-' . $product_color;
    
    // Check if this item is already in the cart
    if (isset($_SESSION['cart'][$cart_item_id])) {
        // Update quantity if item exists
        $_SESSION['cart'][$cart_item_id]['quantity'] += $product_quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$cart_item_id] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'size' => $product_size,
            'color' => $product_color,
            'quantity' => $product_quantity
        ];
    }
    
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF'] . '#products');
    exit;
}

// Handle cart item removal
if (isset($_GET['remove_item']) && isset($_SESSION['cart'][$_GET['remove_item']])) {
    unset($_SESSION['cart'][$_GET['remove_item']]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Calculate cart total
$cartTotal = 0;
$cartItemCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartTotal += $item['price'] * $item['quantity'];
    $cartItemCount += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketSnacks PH - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="dashstyles.css">
    <script src="dashscripts.js"></script>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="logo">
            <img src="logo.jpg" alt="PocketSnacks PH Logo">
        </div>
        
        <div class="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        
        <nav id="main-nav">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#events">Events</a></li>
            </ul>
        </nav>
        
        <div class="header-actions">
            <a href="?logout" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
    </a>
        </div>
    </header>

    <!-- Hero Section with Slider -->
    <section id="home" class="hero-slider">
        <?php foreach ($sliderImages as $index => $image): ?>
            <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $image; ?>');">
                <div class="slide-overlay">
                    <div class="hero-content">
                        <h1>PocketSnacks PH</h1>
                       <p>Don't Stop Dreaming.<p>
                        <a href="#products" class="cta-btn">Shop Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Products Section -->
    <section id="products" class="products-section">
        <h2 class="section-title">Our Products</h2>
        
        <div class="category-tabs">
            <div class="category-tab active" data-category="tshirt">T-Shirts</div>
            <div class="category-tab" data-category="hoodie">Hoodies</div>
            <div class="category-tab" data-category="shorts">Shorts</div>
            <div class="category-tab" data-category="poloshirt">Polo Shirts</div>
        </div>
        
        <div class="product-container" id="product-container">
            <?php foreach ($products['tshirt'] as $product): ?>
                <div class="product-card" data-category="tshirt">
                    <div class="product-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name"><?php echo $product['name']; ?></h3>
                        <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                       <button class="add-to-cart"
                        data-id="<?php echo $product['id']; ?>"
                        data-name="<?php echo $product['name']; ?>"
                        data-price="<?php echo $product['price']; ?>"
                        data-image="<?php echo $product['image']; ?>">
                            See Details
                    </button>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <h2 class="section-title">About Us</h2>
        
        <div class="about-container">
            <div class="about-image">
                <img src="pocketsnacks.jpg" alt="PocketSnacks PH">
            </div>
            
            <div class="about-content">
                <h3>Our Story</h3>
                <p>PocketSnacks PH started in 2019 as a small passion project by a group of friends who shared a love for fashion and street culture. What began as a small collection sold at local markets has now grown into a beloved clothing brand that represents Filipino creativity and style.</p>
                
                <p>Our mission is to create comfortable, high-quality clothing that allows you to express your unique personality. Each piece is carefully designed with attention to detail, using premium materials to ensure durability and comfort.</p>
                
                <p>We believe in sustainable and ethical practices, which is why we work closely with local manufacturers to create our products. By supporting PocketSnacks PH, you're not just buying clothing – you're becoming part of a community that values authenticity, creativity, and Filipino craftsmanship.</p>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="events-section">
        <h2 class="section-title">Our Events</h2>
        
        <div class="events-container">
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div class="event-image">
                        <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['name']; ?>">
                    </div>
                    <div class="event-details">
                        <h3 class="event-name"><?php echo $event['name']; ?></h3>
                        <div class="event-date"><?php echo $event['date']; ?></div>
                        <p class="event-description"><?php echo $event['description']; ?></p>
                        <a href="<?php echo $event['link']; ?>" class="event-link" target="_blank">View Event</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>PocketSnacks PH</h4>
                <p>Your go-to clothing brand for premium streetwear and everyday fashion essentials.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/theoriginalstreetcafe"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/theoriginalstreetcafe_/"><i class="fab fa-instagram"></i></a>        
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="#home">Home</a>
                <a href="#products">Products</a>
                <a href="#about">About Us</a>
                <a href="#events">Events</a>
        
            </div>
            
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p><i class="fas fa-map-marker-alt"></i> Ynez Suites Bldg., F. Lotivio Street Bagumbayan, Daraga 4501 Albay Philippines, Daraga, Philippines</p>
                <p><i class="fas fa-phone"></i> 0995 308 5218</p>
                <p><i class="fas fa-envelope"></i> theoriginalstreetcafe@gmail.com</p>
            </div>
        </div>
        
        <div class="copyright">
            <p>POCKETSNACKS PH EST.2019 ALL RIGHTS RESERVED</p>
        </div>
    </footer>

    <!-- Product Modal -->
    <div id="product-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            
            <div class="product-modal">
                <div class="product-modal-image">
                    <img id="modal-product-image" src="" alt="">
                </div>
                
                <div class="product-modal-details">
                    <h2 id="modal-product-title" class="product-modal-title"></h2>
                    <div id="modal-product-price" class="product-modal-price"></div>
                    
                    <div class="product-modal-description">
                        <p>Premium quality product made with the finest materials. Designed for comfort and style, perfect for everyday wear or special occasions.</p>
                    </div>
                    
                    <form method="post" action="process_payment.php">
                        <input type="hidden" id="product_id" name="product_id" value="">
                        <input type="hidden" id="product_name" name="product_name" value="">
                        <input type="hidden" id="product_price" name="product_price" value="">
                        <input type="hidden" id="product_image" name="product_image" value="">
                        
                        <div class="product-options">
                            <span class="option-label">Size:</span>
                            <div class="size-options">
                                <label>
                                    <input type="radio" name="size" value="S" required>
                                    <span class="size-box">S</span>
                                </label>
                                <label>
                                    <input type="radio" name="size" value="M">
                                    <span class="size-box">M</span>
                                </label>
                                <label>
                                    <input type="radio" name="size" value="L">
                                    <span class="size-box">L</span>
                                </label>
                                <label>
                                    <input type="radio" name="size" value="XL">
                                    <span class="size-box">XL</span>
                                </label>
                                <label>
                                    <input type="radio" name="size" value="2XL">
                                    <span class="size-box">2XL</span>
                                </label>
                                <label>
                                    <input type="radio" name="size" value="3XL">
                                    <span class="size-box">3XL</span>
                                </label>
                            </div>
                            
                    
                            
                            <span class="option-label">Quantity:</span>
                            <div class="quantity-selector">
                                <div class="quantity-btn minus">-</div>
                                <input type="number" name="quantity" class="quantity" value="1" min="1" max="10">
                                <div class="quantity-btn plus">+</div>
                            </div>
                        </div>
                        
                       <!-- In your dashboard.php form -->
                        <button type="submit" name="process_payment" class="add-to-cart-btn">Buy Now</button>
                         <!-- Changed name to process_payment -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    
   
    <script>
        // DOM Elements
        const header = document.getElementById('header');
        const hamburgerMenu = document.querySelector('.hamburger-menu');
        const mainNav = document.getElementById('main-nav');
        const slides = document.querySelectorAll('.slide');
        const categoryTabs = document.querySelectorAll('.category-tab');
        const productContainer = document.getElementById('product-container');
        const productModal = document.getElementById('product-modal');
        const modalClose = document.querySelector('.modal-close');
        const cartBtn = document.querySelector('.cart-btn');
        const logoutBtn = document.querySelector('.logout-btn');
        const logoutModal = document.getElementById('logout-modal');
        const stayLoggedInBtn = document.querySelector('.stay-btn');
        const logoutConfirmBtn = document.querySelector('.logout-btn');
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        const quantityInput = document.querySelector('.quantity');
        const checkoutBtn = document.getElementById('checkout-btn');
        const checkoutModal = document.getElementById('checkout-modal');
        const checkoutClose = document.querySelector('.checkout-close');
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentDetails = document.querySelectorAll('.payment-details');
        const completeOrderBtn = document.querySelector('.complete-order-btn');

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile Menu Toggle
        hamburgerMenu.addEventListener('click', () => {
            hamburgerMenu.classList.toggle('active');
            mainNav.classList.toggle('active');
        });

        // Close Mobile Menu when clicking a link
        document.querySelectorAll('nav ul li a').forEach(link => {
            link.addEventListener('click', () => {
                hamburgerMenu.classList.remove('active');
                mainNav.classList.remove('active');
            });
        });

        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    window.scrollTo({
                        top: targetSection.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Image Slider
        let currentSlide = 0;
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        
        function nextSlide() {
            showSlide(currentSlide + 1);
        }
        
        // Auto-advance slides every 5 seconds
        setInterval(nextSlide, 5000);

        // Product Category Tabs
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                categoryTabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                tab.classList.add('active');
                
                // Get category from data attribute
                const category = tab.dataset.category;
                
                // Clear product container
                productContainer.innerHTML = '';
                
                // Load products for selected category via AJAX (simplified for demo)
                <?php foreach ($products as $category => $categoryProducts): ?>
                if (category === '<?php echo $category; ?>') {
                    <?php foreach ($categoryProducts as $product): ?>
                    productContainer.innerHTML += `
                        <div class="product-card" data-category="<?php echo $category; ?>">
                            <div class="product-image">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            </div>
                            <div class="product-details">
                                <h3 class="product-name"><?php echo $product['name']; ?></h3>
                                <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                                <button class="add-to-cart" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>" data-price="<?php echo $product['price']; ?>" data-image="<?php echo $product['image']; ?>">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    `;
                    <?php endforeach; ?>
                }
                <?php endforeach; ?>
                
                // Re-attach event listeners for "Add to Cart" buttons
                attachAddToCartListeners();
            });
        });

        // Product Modal Functions
        function openProductModal(id, name, price, image) {
            document.getElementById('modal-product-image').src = image;
            document.getElementById('modal-product-title').textContent = name;
            document.getElementById('modal-product-price').textContent = '₱' + parseFloat(price).toFixed(2);
            
            document.getElementById('product_id').value = id;
            document.getElementById('product_name').value = name;
            document.getElementById('product_price').value = price;
            document.getElementById('product_image').value = image;
            
            productModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeProductModal() {
            productModal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        modalClose.addEventListener('click', closeProductModal);
        
        // Close modal when clicking outside of content
        productModal.addEventListener('click', (e) => {
            if (e.target === productModal) {
                closeProductModal();
            }
        });

        // Attach event listeners to "Add to Cart" buttons
        function attachAddToCartListeners() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            
            addToCartButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const price = button.dataset.price;
                    const image = button.dataset.image;
                    
                    openProductModal(id, name, price, image);
                });
            });
        }
        
        // Initialize event listeners
        attachAddToCartListeners();

        // Quantity selector
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                let value = parseInt(quantityInput.value);
                
                if (btn.classList.contains('minus') && value > 1) {
                    quantityInput.value = value - 1;
                }
                
                if (btn.classList.contains('plus') && value < 10) {
                    quantityInput.value = value + 1;
                }
            });
        });

        // Cart Dropdown Toggle
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartBtn.classList.toggle('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!cartBtn.contains(e.target) && !cartBtn.nextElementSibling.contains(e.target)) {
                cartBtn.classList.remove('active');
            }
        });
        
        // Checkout Modal
        checkoutBtn.addEventListener('click', () => {
            cartBtn.classList.remove('active');
            checkoutModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        checkoutClose.addEventListener('click', () => {
            checkoutModal.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close checkout modal when clicking outside
        checkoutModal.addEventListener('click', (e) => {
            if (e.target === checkoutModal) {
                checkoutModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Payment Method Selection
        paymentOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove active class from all options
                paymentOptions.forEach(opt => opt.classList.remove('active'));
                paymentDetails.forEach(detail => detail.classList.remove('active'));
                
                // Add active class to clicked option
                option.classList.add('active');
                
                // Show corresponding payment details
                const paymentType = option.dataset.payment;
                document.getElementById(paymentType + '-payment').classList.add('active');
            });
        });
        
        // Complete Order Button
        completeOrderBtn.addEventListener('click', () => {
            alert('Thank you for your order! This is a demo transaction. No actual payment has been processed.');
            checkoutModal.classList.remove('active');
            document.body.style.overflow = '';
            // In a real application, you would submit the form to process the payment
            // and redirect to a thank you page or order confirmation
        });
    </script>
</body>
</html>
