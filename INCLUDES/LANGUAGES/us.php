<?php
// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

return [
    // Header
    'header-USLogo' => 'USA Face',
    'header-CALogo' => 'CANADA Face',
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
    'home-presentation-paragraph' => "AMAZING-USA-CANADA offers a selection of maps featuring the best spots across all 50 US states and 10 Canadian provinces. 
                                    The must-sees, the classics, hidden gems, local secrets... (Arches, Hoodoos, Viewpoints, Trailheads, Ghost Towns, 
                                    Falls, Lakes, Bridges, Slot Canyons, Historic Sites, Historic Gas Stations, Diners, Scenic Drives...)",
    'home-presentation-mapcard' => 'Map',
    'home-presentation-mapcard-title' => 'Fully Customized Maps',
    'home-presentation-mapcard-paragraph' => 'State-by-state maps or packs covering multiple states, with GPS coordinates, info links, photos, videos...',
    'home-presentation-accesscard' => 'Accessibility',
    'home-presentation-accesscard-title' => 'Full Accessibility',
    'home-presentation-accesscard-paragraph' => 'Our maps are easily accessible on all your devices, securely through this site',
    'home-mapshowcase-title' => 'Some maps from our catalog:',
    'home-mapshowcase-card-type' => 'Map type: ',
    'home-mapshowcase-card-localisation' => 'Location: ',
    'home-mapshowcase-card-price' => 'Price: ',
    'home-mapshowcase-card-cart' => 'Add to cart',
    'home-mapshowcase-card-info' => 'More information',

    // Currency
    'currency-code' => 'USD',
    'currency-symbol' => '$',
    'currency_locale' => 'en_US',

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

    // Auth - login
    'login-title' => 'Sign in',
    'login-email-label' => 'Email address:',
    'login-password-label' => 'Password:',
    'login-submit' => 'Sign in',
    'login-no-account' => 'No account yet?',
    'login-create-account' => 'Create an account',
    'login-forgot-password' => 'Forgot your password?',

    // Auth - register
    'register-title' => 'Create an account',
    'register-email-label' => 'Email address:',
    'register-next' => 'Next',
    'register-code-instruction' => 'You will receive a code at the email address you provided earlier.',
    'register-code-label' => 'Code:',
    'register-password-label' => 'Password:',
    'register-password-confirm-label' => 'Confirm password:',
    'register-username-label' => 'Username:',
    'register-picture-label' => 'Profile picture:',
    'register-already-registered' => 'Already registered?',
    'register-go-login' => 'Sign in',

    // Auth - forgot password
    'forgot-title' => 'Password reset',
    'forgot-description' => 'Enter your email address below. A temporary password will be sent to you.',
    'forgot-email-placeholder' => 'Your email address',
    'forgot-submit' => 'Receive a new password',
    'forgot-back-login' => 'Back to sign-in page',
    'forgot-icon-alt' => 'Security',
    'forgot-mail-subject' => 'Your new password',
    'forgot-mail-body-1' => 'Your new password is: %s',
    'forgot-mail-body-2' => 'We recommend changing it after you sign in.',
    'forgot-success' => 'A new password has been sent to you by email.',
    'forgot-error-send' => 'Error while sending email: %s',
    'forgot-error-notfound' => 'This email address does not exist.',

    // Map viewer (voirmap)
    'voirmap-download-button' => 'Download hiking map',
    'voirmap-search-placeholder' => 'Search for a point or a city…',
    'voirmap-search-button' => 'Search',
    'voirmap-noresult' => 'No results found',
    'voirmap-result-point' => 'Map point',
    'voirmap-result-city' => 'City',
    'voirmap-layer-road' => 'Road map',
    'voirmap-layer-satellite' => 'Satellite',
    'voirmap-invalid-map' => 'Invalid map.',
    'voirmap-map-not-found' => 'Map not found.',
    'voirmap-must-buy' => 'You must purchase this map to view it.',
    'voirmap-no-geojson' => 'No GeoJSON found for this map.',

    // Index - contact form
    'contact-title' => 'Contact us',
    'contact-name-placeholder' => 'Your name',
    'contact-email-placeholder' => 'Your email address',
    'contact-message-placeholder' => 'Your message',
    'contact-submit' => 'Send',
    'contact-error-name' => 'Please enter your name.',
    'contact-error-email' => 'Please enter a valid email address.',
    'contact-error-message' => 'Please enter your message.',
    'contact-success' => 'Your message has been sent successfully.',
    'contact-error-send' => 'Error while sending message: %s',

    // Maps details - extra labels
    'mapsdetails-places-count' => 'Number of locations:',
    'mapsdetails-pack-maps-title' => 'Maps in this pack',

    // Legal pages titles
    'legal-title' => 'Legal notice',
    'cgu-title' => 'Terms of use',
    'cgv-title' => 'Terms of sale',
];
?>
