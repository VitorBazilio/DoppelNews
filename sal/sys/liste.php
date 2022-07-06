<?php  
   session_start();   
    include_once("../../php/conexao.php");

$nt =$_SESSION['id'];
$em=$_SESSION["email"];
  $lk = mysqli_query($link,"SELECT * FROM salas WHERE id_sl = '$nt' AND email = '$em'  LIMIT 1");
                                $lk2 = mysqli_fetch_assoc($lk);
                         $nm_perso=$lk2['nm_do_perso'];
   
                    // Seleciona todos os usuários
$tt = mysqli_query($link,"SELECT * FROM (SELECT * FROM chat WHERE (id_sl = '$nt') ORDER BY id_chat DESC LIMIT 20) sub ORDER BY id_chat ASC");
 
// Exibe as informações de cada usuário

$conta = mysqli_num_rows($tt);

    if($conta <= 0){
    	echo "<code>Nova sala de RPG, vamos começar?</code>";
    }else{
    	while($row = mysqli_fetch_array($tt)){
    	
            
             if($row['nm_do_perso'] == $nm_perso){?>
        
     
        
        
        <div align="right">
            <p class="chat-i">
               
                <?php
               
                                  
                                
                echo $row['mensagem'];
                
                
                
                
                ?>
                
                
            </p>
        </div>

        <?php }else if($row['nm_do_perso'] != $nm_perso){?>
        
        <div align="left" >
            <p class="chat-you">
               
                <?php echo $row['mensagem'];?>
                
            </p>
        </div>
     <link rel="stylesheet" href="../../node_modules/bootstrap/compiler/bootstrap.css">
     <link rel="stylesheet" href="../../node_modules/bootstrap/compiler/bootstyle.css">
        <?php
                                                    
                                                    }}}

?>