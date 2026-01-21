<?php
// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

return [
    //Tous ce qui concerne le header
    'header-USLogo' => 'USA Face',
    'header-CALogo' => 'Canada Face',
    'header-home' => 'Home',
    'header-maplist' => 'Maps List',
    'header-cart' => 'Cart',
    'header-account' => 'Account',
    'header-currentLang' => 'Current Language',
    'header-FRLang' => 'French',
    'header-USLang' => 'English (US)',
    'header-USLg' => 'Américan English',
    'header-CALang' => 'English (CA)',
    'header-CALg' => 'Canadian English',
    //Tous ce qui concerne l'index
    'home-hero-title' => 'Welcome to AMAZING-USA-CANADA.com',
    'home-hero-paragraph' => 'Explore our maps and the places to explore!',
    'home-presentation-title' => 'What we offer:',
    'home-presentation-paragraph' => "AMAZING-USA-CANADA offers a selection of MyMaps for U.S. states and customized maps of Canada.<br>
                                        Whether you are an urban explorer, a landscape enthusiast, a travel professional, or a local resident, 
                                        our maps are designed to guide, assist, and inform you about lesser-known locations.",
    'home-presentation-mapcard' => 'Map',
    'home-presentation-mapcard-title' => 'Fully Customized Maps',
    'home-presentation-mapcard-paragraph' => 'Explore places few have seen, places not on traditional maps',
    'home-presentation-commentscard' => 'Comment',
    'home-presentation-commentscard-title' => 'Comment Section for each map',
    'home-presentation-commentscard-paragraph' => 'Share your thoughts on our maps to help us improve them over time',
    'home-presentation-accesscard' => 'Accessibility',
    'home-presentation-accesscard-title' => 'Full Accessibility',
    'home-presentation-accesscard-paragraph' => 'Our maps are easily accessible on all your devices, securely via this site',
    'home-mapshowcase-title' => 'Some maps from our catalogue :',
    'home-mapshowcase-card-type' => 'Map Type : ',
    'home-mapshowcase-card-localisation' => 'Location : ',
    'home-mapshowcase-card-price' => 'Price : ',
    'home-mapshowcase-card-money' => ' C$',
    'home-mapshowcase-card-cart' => 'Add to cart',
    'home-mapshowcase-card-info' => "More informations",
    //Tous ce qui concerne le footer
    'footer-mandatory' => 'Required Documents :',
    'footer-legalnotice' => 'Legal notices',
    'footer-CGU' => "General terms and condition of use",
    'footer-CGV' => "General terms and condition of sale",
    'footer-followUs' => 'Follow-us on Socials',
    //Tous ce qui concerne les map-list
    'maplist-presentationtext-h2' => 'Our available maps',
    'maplist-presentationtext-p' => 'Discover all our maps of the United States and Canada. Filter by type, price, or location to find the one that suits you.',
    'maplist-alltypes' => 'All Types',
    'maplist-alllocations' => 'All Locations',
    'maplist-price' => 'Price :',
    'filter-money' => 'C$'
];
?>
