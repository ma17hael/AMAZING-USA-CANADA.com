<?php
// Empêche l'accès direct au fichier
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Accès interdit");
}
return [
    //Tous ce qui concerne le header
    'header-USLogo' => 'Face USA',
    'header-CALogo' => 'Face CANADA',
    'header-home' => 'Accueil',
    'header-maplist' => 'Liste des Cartes',
    'header-cart' => 'Panier',
    'header-account' => 'Compte',
    'header-currentLang' => 'Langue Actuelle',
    'header-FRLang' => 'Français',
    'header-USLang' => 'Anglais (US)',
    'header-USLg' => 'Américain',
    'header-CALang' => 'Anglais (CA)',
    'header-CALg' => 'Canadien',
    //Tous ce qui concerne l'index
    'home-hero-title' => 'Bienvenue sur AMAZING-USA-CANADA.com',
    'home-hero-paragraph' => 'Découvrez nos cartes et leur lieux à découvrir !',
    'home-presentation-title' => 'Ce que nous vous proposons :',
    'home-presentation-paragraph' => "AMAZING-USA-CANADA vous offre une sélection de cartes MyMaps des états des Etats-Unis et des cartes du Canada personnalisées.<br>
                                        Que vous soyez un explorateur urbain, un passionné de paysage, un professionnel du voyage ou même un simple résident de l'endroit,
                                        nos cartes sont conçues pour vous guider, et vous accompagner et vous informer sur les lieux peu connus de ces cartes.",
    'home-presentation-mapcard' => 'Carte',
    'home-presentation-mapcard-title' => 'Cartes entièrement personnalisées par nos soins',
    'home-presentation-mapcard-paragraph' => 'Explorer des lieux que peu ont vu, des lieux qui ne figurent pas sur les cartes classiques',
    'home-presentation-commentscard' => 'Commentaire',
    'home-presentation-commentscard-title' => 'Espace Commentaire pour chaque carte',
    'home-presentation-commentscard-paragraph' => 'Exprimer vous sur nos cartes pour nous permettre de les améliorer avec le temps',
    'home-presentation-accesscard' => 'Accessibilité',
    'home-presentation-accesscard-title' => 'Accessibilité complète',
    'home-presentation-accesscard-paragraph' => 'Nos cartes sont facilement accessible sur tout vos appareils, de manière sécurisé grâce à ce site',
    'home-mapshowcase-title' => 'Quelques cartes de notre catalogue :',
    'home-mapshowcase-card-type' => 'Type de carte : ',
    'home-mapshowcase-card-localisation' => 'Localisation : ',
    'home-mapshowcase-card-price' => 'Prix : ',
    'home-mapshowcase-card-money' => ' €',
    'home-mapshowcase-card-cart' => 'Ajouter au panier',
    'home-mapshowcase-card-info' => "Plus d'informations",
    //Tous ce qui concerne le footer
    'footer-mandatory' => 'Documents obligatoires :',
    'footer-legalnotice' => 'Mentions Légales',
    'footer-CGU' => "Conditions Générales d'Utilisation",
    'footer-CGV' => "Conditions Générales de Vente",
    'footer-followUs' => 'Suivez-nous',
    //Tous ce qui concerne les map-list
    'maplist-presentationtext-h2' => 'Nos cartes disponibles',
    'maplist-presentationtext-p' => 'Découvrez toutes nos cartes des États-Unis et du Canada. Filtrez par type, prix ou localisation pour trouver celle qui vous convient.',
    'maplist-alltypes' => 'Tous types',
    'maplist-alllocations' => 'Toutes localisations',
    'maplist-price' => 'Prix :',
    'filter-money' => '€'
]
?>