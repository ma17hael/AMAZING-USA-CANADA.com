<?php
// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}

return [
    // Header
    'header-USLogo' => 'Face USA',
    'header-CALogo' => 'Face CANADA',
    'header-home' => 'Accueil',
    'header-maplist' => 'Liste des Cartes',
    'header-cart' => 'Panier',
    'header-account' => 'Compte',
    'header-currentLang' => 'Langue actuelle',
    'header-FRLang' => 'Français',
    'header-USLang' => 'Anglais (US)',
    'header-USLg' => 'Américain',
    'header-CALang' => 'Anglais (CA)',
    'header-CALg' => 'Canadien',

    // Home
    'home-hero-title' => 'Bienvenue sur AMAZING-USA-CANADA.com',
    'home-hero-paragraph' => 'Découvrez nos cartes et leurs lieux à découvrir !',
    'home-presentation-title' => 'Ce que nous vous proposons :',
    'home-presentation-paragraph' => "AMAZING-USA-CANADA, vous propose une sélection de cartes regroupant les meilleurs spots sur l'ensemble des 50 Etats US et les 10 provinces du
                                        CANADA. Les immanquables, les grands classiques, les perles cachées, les secrets des locaux... (Arches, Hoodoos, Viewpoints, Trailheads, Ghost Towns,
                                        Falls, Lakes, Bridges, Slot Canyon, Historic Sites, Historic Gaz Stations, Diners, Scenic Drives....)",
    'home-presentation-mapcard' => 'Carte',
    'home-presentation-mapcard-title' => 'Cartes entièrement personnalisées par nos soins',
    'home-presentation-mapcard-paragraph' => 'Cartes Etat par Etat ou Pack regroupan plusieurs Etats, avec coordonnées GPS, liens infos, photos, vidéos..',
    'home-presentation-accesscard' => 'Accessibilité',
    'home-presentation-accesscard-title' => 'Accessibilité complète',
    'home-presentation-accesscard-paragraph' => 'Nos cartes sont accessibles sur tous vos appareils de manière sécurisée',
    'home-mapshowcase-title' => 'Quelques cartes de notre catalogue :',
    'home-mapshowcase-card-type' => 'Type de carte : ',
    'home-mapshowcase-card-localisation' => 'Localisation : ',
    'home-mapshowcase-card-price' => 'Prix : ',
    'home-mapshowcase-card-cart' => 'Ajouter au panier',
    'home-mapshowcase-card-info' => "Plus d'informations",

    // Currency
    'currency-code' => 'EUR',
    'currency-symbol' => '€',
    'currency_locale' => 'fr_FR',

    // Footer
    'footer-mandatory' => 'Documents obligatoires :',
    'footer-legalnotice' => 'Mentions légales',
    'footer-CGU' => "Conditions Générales d'Utilisation",
    'footer-CGV' => "Conditions Générales de Vente",
    'footer-followUs' => 'Suivez-nous',

    // Maps list
    'maplist-presentationtext-h2' => 'Nos cartes disponibles',
    'maplist-presentationtext-p' => 'Découvrez toutes nos cartes des États-Unis et du Canada. Filtrez par type, prix ou localisation pour trouver celle qui vous convient.',
    'maplist-alltypes' => 'Tous types',
    'maplist-alllocations' => 'Toutes localisations',
    'maplist-price' => 'Prix :',

    // Maps details
    'mapsdetails-h1-main-title' => 'Données essentielles de la carte',
    'mapsdetails-h1-complementary-title' => 'Informations complémentaires',
    'mapsdetails-h2-complementary-smalltitle' => 'Son emplacement sur la carte du pays :',
    'mapsdetails-p-complementary' => 'Cette carte vous permet de vous situer sur la carte du pays et de retrouver plus facilement votre position. Elle facilitera également vos trajets.',

    // Profile
    'profile-main-title' => 'Mon profil',
    'profile-action-title' => 'Modifier mon profil',
    'profile-mail-field' => 'Adresse e-mail :',
    'profile-username-field' => "Nom d'utilisateur :",
    'profile-picture-field' => 'Photo de profil :',
    'profile-password-field' => 'Mot de passe :',
    'profile-passwordconfir-field' => 'Confirmer le mot de passe :',
    'profile-save-change' => 'Enregistrer les modifications',
    'profile-discard-account' => 'Supprimer mon compte',
    'profile-purchased-maps' => 'Mes cartes achetées',
    'profile-nopurchased-maps' => 'Aucune carte achetée pour le moment.',
    'profile-see-map' => 'Voir la carte',

    // Cart
    'cart-title' => 'Votre panier',
    'cart-empty' => 'Votre panier est actuellement vide.',
    'cart-see' => 'Voir',
    'cart-delete' => 'Supprimer',
    'cart-delete-confirm' => 'Supprimer cet article du panier ?',
    'cart-summary' => 'Résumé',
    'cart-total' => 'Total :',
    'cart-checkout' => 'Passer la commande',
    'cart-cancel' => 'Annuler le panier',
    'cart-cancel-confirm' => 'Voulez-vous vraiment annuler votre panier ? Cette action est irréversible.',

    // Purchasing / payment
    'purchasing-title' => 'Paiement',
    'purchasing-cart-title' => 'Votre panier',
    'purchasing-summary-title' => 'Résumé de la commande',
    'purchasing-pay-card' => 'Payer par carte',
    'purchasing-pay-paypal' => 'Payer avec PayPal',
    'purchasing-empty-message' => 'Votre panier est vide.',
    'payment-paypal-title' => 'Paiement PayPal',
    'payment-paypal-heading' => 'Payer avec PayPal',
    'payment-config-missing-paypal' => 'Configuration PayPal manquante.',
    'payment-config-missing-stripe' => 'Configuration Stripe manquante.',
    'payment-not-confirmed' => 'Paiement non confirmé.',
    'payment-order-not-found' => 'Commande introuvable dans les métadonnées Stripe.',
    'payment-paypal-success-title' => 'Paiement réussi - PayPal',
    'payment-stripe-success-title' => 'Paiement réussi - Stripe',
    'payment-success-heading' => 'Paiement réussi !',
    'payment-success-paypal-message' => 'Merci pour votre achat. Votre commande #%d a été confirmée via PayPal.',
    'payment-success-stripe-message' => 'Merci pour votre achat. Votre commande #%d a été confirmée.',
    'payment-success-back-home' => "Retour à l'accueil",
];
?>
