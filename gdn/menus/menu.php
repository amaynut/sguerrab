        <?php
                $type = $_SESSION['type'];
                switch($type)
                {
                    case 'cf':
                        include_once "menus/menu-cf.php";
                    break;
                    case 'rp':
                        include_once "menus/menu-rp.php";
                    break;
                    case 'correcteur':
                        include_once "menus/menu-correcteur.php";
                    break;
                    case 'tuteur':
                        include_once "menus/menu-tuteur.php";
                    break;
                    case 'etudiant':
                        include_once "menus/menu-etudiant.php";
                    break;
                }

            ?>