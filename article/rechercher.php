<?php
    session_start();
    require('../php/connect.php');

    if(isset($_GET['q'])){
        $query = (String) htmlspecialchars(trim(($_GET['q']))); 

        $sql = $db->prepare("SELECT * FROM articles  WHERE title LIKE ? OR textValue LIKE ? ORDER BY article_id  LIMIT 0, 7 ");
        $sql->execute(array("%$query%", "%$query%")); // si le contenu ou le tire de l'article contiennent les mots de recherchés par les utilisateurs

        $req = $sql->fetchALL(); // alors on affihces les résultats  :) !(:)

        foreach($req as $r){
            ?>
            <div style="font-size: 29px;">

                <a href="http://localhost/projet/article/article-contenu.php?article_id=<?= $r['article_id']?>">  <p  style='text-align: center;' ><?= $r['title'];?> </p><br /> 
                    
            </div>
        <?php    
       }
    } 
?>
