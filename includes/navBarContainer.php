<div id="navBarContainer">
    <nav class="navBar">
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
            <img src="assets/images/controlp_sq_jpg.jpg" alt="Logo">
        </span>

        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('search.php')" class="navItemLink">DB Search
                    <img src="assets/images/icons/search.png" class="icon" alt="Search">
                </span>

            </div>
        </div>

        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Your Music</span>
            </div>
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Playlist</span>
            </div>
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('sync.php')" class="navItemLink">Sync Music</span>
            </div>
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink">Settings for <?php echo $userLoggedIn->getUsername(); ?></span>
            </div>
        </div>
    </nav>
</div>