<?php
    if (isset($_POST['add_category'])) {
        if (!empty($_POST['name']) && !empty($_POST['identifier'])) {
            $rowCount = CategoryController::addCategory($_POST['name'], $_POST['identifier']);
            if ($rowCount == 1) {
                HelpFunctions::alert("Kategorie úspěšně přidána");
                echo '<script type="text/javascript">
                    window.location = "index.php"
                    </script>';
            }
        } else {
            HelpFunctions::alert("Zkontrolujte, zda jste vyplnili korektně obě pole");
        }
    }

    if (isset($_POST['del_category'])) {
        $row = CategoryController::deleteCategory($_POST['select']);
        if ($row == 1) {
            HelpFunctions::alert('Kategorie úspěšně odstraněna');
            echo '<script type="text/javascript">
                    window.location = "index.php"
                    </script>';
        } else {
            HelpFunctions::alert('Něco se pokazilo :(');
        }
    }

?>

<!--<button id="openButton" class="openbtn" onclick="openNav()">☰ Open Sidebar</button>-->
<div class="sidemenu" id="mySideMenu">
<!--    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>-->
    <h3 class="prodcut_name">Produkty  <i class="fas fa-shopping-cart"></i></h3>
    <ul>
        <?php
        foreach (CategoryController::getAllCategoriesNamesAndIdentifiers() as $category) {
            if ($category['identifier'] === 'stick') {
                echo '<li><a href="index.php?page=shopitems_'. $category['identifier'] .'s">
                        <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;'. $category['name'] .'</a></li>';
            } else {
                echo '<li><a href="index.php?page=shopitems&items='. $category['identifier'] .'">
                        <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;'. $category['name'] .'</a></li>';
            }
        }
        ?>
    </ul>
    <?php
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] == "admin" || $_SESSION["role"] == "seller") {
            echo '
            <div class="add_category_wrap">
                <form method="post">
                    <input class="cat_name" type="text" name="name" placeholder="Název kategorie" required>
                    <input class="cat_name" type="text" name="identifier" placeholder="Identifikátor" required>
                    <input class="cat_add" type="submit" name="add_category" value="Přidat kategorii">
                </form>
            </div>
            <div class="delete_category_wrap">
                <form method="post">
                    <div class="selection">
                        <select name="select" required>';
                            foreach (CategoryController::getAllCategoriesNamesAndIdentifiers() as $cat) {
                                echo '<option value="'.$cat['identifier'].'">'.$cat['name'].'</option>';
                            }
                echo '  </select>
                    </div>
                    <div class="delete_category_btn">
                        <input class="cat_del" type="submit" name="del_category" value="Odstranit kategorii">
                    </div>
                </form>
            </div>';
        }
    }
    ?>
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