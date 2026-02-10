<footer class="site-footer">
    <div class="footer-container">

        <!-- Colonne : Informations -->
        <div class="footer-column">
            <h4><?=$translations['footer-mandatory']?></h4>
            <ul>
                <li><a href="legalnotice.php"><?=$translations['footer-legalnotice']?></a></li>
                <li><a href="CGU.php"><?=$translations['footer-CGU']?></a></li>
                <li><a href="CGV.php"><?=$translations['footer-CGV']?></a></li>
            </ul>
        </div>

        <!-- Colonne : Navigation -->
        <div class="footer-column">
            <h4>Navigation :</h4>
            <ul>
                <li><a href="index.php"><?=$translations['header-home']?></a></li>
                <li><a href="mapslist.php"><?=$translations['header-maplist']?></a></li>
            </ul>
        </div>

        <!-- Colonne : Réseaux -->
        <div class="footer-column">
            <h4><?=$translations['footer-followUs']?></h4>
            <ul class="social-links">
                <li>
                    <a href="https://www.facebook.com/groups/1278764037358320/" target="_blank" aria-label="Facebook">
                        <img src="INCLUDES/ICONS/Facebook.svg" alt="Facebook" style="width:50px; height:50px;">
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?=date('Y')?> AMAZING-USA-CANADA.com - Contenu protégée par les droits d'auteurs</p>
    </div>
</footer>
