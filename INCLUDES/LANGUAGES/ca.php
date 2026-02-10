<?php
// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

return [
    // Header
    'header-USLogo' => 'USA Face',
    'header-CALogo' => 'Canada Face',
    'header-home' => 'Home',
    'header-maplist' => 'Maps List',
    'header-cart' => 'Cart',
    'header-account' => 'Account',
    'header-currentLang' => 'Current Language',
    'header-FRLang' => 'French',
    'header-USLang' => 'English (US)',
    'header-USLg' => 'American English',
    'header-CALang' => 'English (CA)',
    'header-CALg' => 'Canadian English',

    // Home
    'home-hero-title' => 'Welcome to AMAZING-USA-CANADA.com',
    'home-hero-paragraph' => 'Explore our maps and the places to discover!',
    'home-presentation-title' => 'What we offer:',
    'home-presentation-paragraph' => "AMAZING-USA-CANADA offers a selection of MyMaps for U.S. states and customized maps of Canada.<br>
                                        Whether you are an urban explorer, a landscape enthusiast, a travel professional, or simply a local resident,
                                        our maps are designed to guide, assist, and inform you about lesser-known places.",
    'home-presentation-mapcard' => 'Map',
    'home-presentation-mapcard-title' => 'Fully Customized Maps',
    'home-presentation-mapcard-paragraph' => 'Explore places few have seen, places not on traditional maps',
    'home-presentation-commentscard' => 'Comment',
    'home-presentation-commentscard-title' => 'Comment section for each map',
    'home-presentation-commentscard-paragraph' => 'Share your thoughts on our maps to help us improve them over time',
    'home-presentation-accesscard' => 'Accessibility',
    'home-presentation-accesscard-title' => 'Full Accessibility',
    'home-presentation-accesscard-paragraph' => 'Our maps are easily accessible on all your devices, securely through this site',
    'home-mapshowcase-title' => 'Some maps from our catalogue:',
    'home-mapshowcase-card-type' => 'Map type: ',
    'home-mapshowcase-card-localisation' => 'Location: ',
    'home-mapshowcase-card-price' => 'Price: ',
    'home-mapshowcase-card-cart' => 'Add to cart',
    'home-mapshowcase-card-info' => 'More information',

    // Currency
    'currency-code' => 'CAD',
    'currency-symbol' => 'C$',
    'currency_locale' => 'en_CA',

    // Footer
    'footer-mandatory' => 'Required documents:',
    'footer-legalnotice' => 'Legal notice',
    'footer-CGU' => 'Terms of use',
    'footer-CGV' => 'Terms of sale',
    'footer-followUs' => 'Follow us',

    // Maps list
    'maplist-presentationtext-h2' => 'Our available maps',
    'maplist-presentationtext-p' => 'Discover all our maps of the United States and Canada. Filter by type, price, or location to find the one that suits you.',
    'maplist-alltypes' => 'All types',
    'maplist-alllocations' => 'All locations',
    'maplist-price' => 'Price:',

    // Maps details
    'mapsdetails-h1-main-title' => 'Essential map data',
    'mapsdetails-h1-complementary-title' => 'Additional information',
    'mapsdetails-h2-complementary-smalltitle' => 'Its location on the country map:',
    'mapsdetails-p-complementary' => 'This map lets you locate yourself on the country map and find your position more easily. It also helps plan your trips.',

    // Profile
    'profile-main-title' => 'My profile',
    'profile-action-title' => 'Edit my profile',
    'profile-mail-field' => 'Email address:',
    'profile-username-field' => 'Username:',
    'profile-picture-field' => 'Profile picture:',
    'profile-password-field' => 'Password:',
    'profile-passwordconfir-field' => 'Confirm password:',
    'profile-save-change' => 'Save changes',
    'profile-discard-account' => 'Delete my account',
    'profile-purchased-maps' => 'My purchased maps',
    'profile-nopurchased-maps' => 'No purchased maps yet.',
    'profile-see-map' => 'See map',

    // Cart
    'cart-title' => 'Your cart',
    'cart-empty' => 'Your cart is currently empty.',
    'cart-see' => 'View',
    'cart-delete' => 'Delete',
    'cart-delete-confirm' => 'Delete this item from your cart?',
    'cart-summary' => 'Summary',
    'cart-total' => 'Total:',
    'cart-checkout' => 'Proceed to checkout',
    'cart-cancel' => 'Cancel cart',
    'cart-cancel-confirm' => 'Do you really want to cancel your cart? This action cannot be undone.',

    // Purchasing / payment
    'purchasing-title' => 'Checkout',
    'purchasing-cart-title' => 'Your cart',
    'purchasing-summary-title' => 'Order summary',
    'purchasing-pay-card' => 'Pay by card',
    'purchasing-pay-paypal' => 'Pay with PayPal',
    'purchasing-empty-message' => 'Your cart is empty.',
    'payment-paypal-title' => 'PayPal payment',
    'payment-paypal-heading' => 'Pay with PayPal',
    'payment-config-missing-paypal' => 'Missing PayPal configuration.',
    'payment-config-missing-stripe' => 'Missing Stripe configuration.',
    'payment-not-confirmed' => 'Payment not confirmed.',
    'payment-order-not-found' => 'Order not found in Stripe metadata.',
    'payment-paypal-success-title' => 'Payment successful - PayPal',
    'payment-stripe-success-title' => 'Payment successful - Stripe',
    'payment-success-heading' => 'Payment successful!',
    'payment-success-paypal-message' => 'Thank you for your purchase. Your order #%d has been confirmed via PayPal.',
    'payment-success-stripe-message' => 'Thank you for your purchase. Your order #%d has been confirmed.',
    'payment-success-back-home' => 'Back to home',
];
?>
