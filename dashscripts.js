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
                             Add to Cart
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