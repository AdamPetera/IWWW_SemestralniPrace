<!--<button id="openButton" class="openbtn" onclick="openNav()">☰ Open Sidebar</button>-->
<div class="sidemenu" id="mySideMenu">
<!--    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>-->
    <h3 class="prodcut_name">Produkty  <i class="fas fa-shopping-cart"></i></h3>
    <ul>
        <li><a href="index.php?page=shopitems_sticks"><i class="fas fa-check"></i>&nbsp;&nbsp;Florbalky</a></li>
        <li><a href="index.php?page=shopitems&items=blade"><i class="fas fa-fan"></i>&nbsp;&nbsp;Čepele</a></li>
        <li><a href="index.php?page=shopitems&items=goalie"><i class="fas fa-bullseye"></i>&nbsp;&nbsp;Brankáři</a></li>
        <li><a href="index.php?page=shopitems&items=bag"><i class="fas fa-suitcase-rolling"></i>&nbsp;&nbsp;Vaky, tašky</a></li>
        <li><a href="index.php?page=shopitems&items=ball"><i class="far fa-futbol"></i>&nbsp;&nbsp;Míčky</a></li>
        <li><a href="index.php?page=shopitems&items=accessory"><i class="fas fa-glasses"></i>&nbsp;&nbsp;Doplňky</a></li>
        <li><a href="index.php?page=shopitems&items=clothes"><i class="fas fa-tshirt"></i>&nbsp;&nbsp;Oblečení</a></li>
        <li><a href="index.php?page=shopitems&items=goal"><i class="fas fa-crosshairs"></i>&nbsp;&nbsp;Branky</a></li>
    </ul>
</div>

<script>
    function openNav() {
        document.getElementById("mySideMenu").style.display = "block";
        document.getElementById("main").style.width = "85%";
        document.getElementById("openButton").style.display = "none";
    }

    function closeNav() {
        document.getElementById("mySideMenu").style.display = "none";
        document.getElementById("main").style.width= "100%";
        document.getElementById("openButton").style.display = "block";
    }
</script>