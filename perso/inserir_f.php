<?php

include_once("../php/conexao.php");

    $ems=$_POST['op'];
    $ff = $_POST['sl'];
    $nm_p = $_POST['nm'];
    $raca = $_POST['raca'];
    $classe = $_POST['classe'];
    $arma = $_POST['arma'];
    $FC = $_POST['fc'];
    $DS = $_POST['ds'];
    $CS = $_POST['cs'];
    $IT = $_POST['it'];
    $SB = $_POST['sb'];
    $CR = $_POST['cr'];
    $nivel = 1;

    $lg="SELECT * FROM sala WHERE nm_sala = '$ff'   LIMIT 1";
    $lga = mysqli_query($link, $lg);
    $lgb = mysqli_fetch_object($lga);
    $nt = $lgb->id_sl;
    $nm = $lgb->nm_criador;

    $pg = mysqli_query($link, "SELECT * FROM persona WHERE nm_do_perso = '$nm_p'");

    if(mysqli_num_rows($pg) == 1){
        echo "<script language='javascript' type='text/javascript'>alert('Este personagem ja esta cadastrado');window.location.href='../perso/criar_perso.php'</script>";
        
    }else{
      
         $final_at = $FC+$DS+$CS+$IT+$SB+$CR ;
        
       if($final_at==75)
       {
        
           $atrib = "INSERT INTO atributos(forca, destreza, constisuicao, inteligencia, sabedoria, carisma, nm) VALUES ('$FC', '$DS', '$CS', '$IT', '$SB', '$CR', '$nm_p')";
           $atrib_final = mysqli_query ($link, $atrib);          
           $atrib_sl1 = mysqli_query ($link, "SELECT * FROM atributos WHERE nm = '$nm' LIMIT 1");
           if( $atrib_sl1->num_rows == 1){
           
           $atri = mysqli_fetch_assoc($atrib_sl1);
           $niv = $atri['atrib_perso'];
               
                 $OP=10;
        if($FC<$OP){
        $FC_OP=-1;
        }else{
        $OP=$OP+2;
        if($FC<$OP){
        $FC_OP=0;
        }else{
        $OP=$OP+2;
        if($FC<$OP){
        $FC_OP=1;
        }else{
        $OP=$OP+2;
        if($FC<$OP){
        $FC_OP=2;
        }else{    
        $OP=$OP+2;
        if($FC<$OP){
        $FC_OP=3;
        }
        }}}}
    
    
         $OP=10;
        if($DS<$OP){
        $DS_OP=-1;
        }else{
        $OP=$OP+2;
        if($DS<$OP){
        $DS_OP=0;
        }else{
        $OP=$OP+2;
        if($DS<$OP){
        $DS_OP=1;
        }else{
        $OP=$OP+2;
        if($DS<$OP){
        $DS_OP=2;
        }else{    
        $OP=$OP+2;
        if($DS<$OP){
        $DS_OP=3;
        }
        }}}}
        
          $OP=10;
        if($CS<$OP){
        $CS_OP=-1;
        }else{
        $OP=$OP+2;
        if($CS<$OP){
        $CS_OP=0;
        }else{
        $OP=$OP+2;
        if($CS<$OP){
        $CS_OP=1;
        }else{
        $OP=$OP+2;
        if($CS<$OP){
        $CS_OP=2;
        }else{    
        $OP=$OP+2;
        if($CS<$OP){
        $CS_OP=3;
        }
        }}}}
        
        
         $OP=10;
        if($IT<$OP){
        $IT_OP=-1;
        }else{
        $OP=$OP+2;
        if($IT<$OP){
        $IT_OP=0;
        }else{
        $OP=$OP+2;
        if($IT<$OP){
        $IT_OP=1;
        }else{
        $OP=$OP+2;
        if($IT<$OP){
        $IT_OP=2;
        }else{    
        $OP=$OP+2;
        if($IT<$OP){
        $IT_OP=3;
        }
        }}}}
        
          $OP=10;
        if($SB<$OP){
        $SB_OP=-1;
        }else{
        $OP=$OP+2;
        if($SB<$OP){
        $SB_OP=0;
        }else{
        $OP=$OP+2;
        if($SB<$OP){
        $SB_OP=1;
        }else{
        $OP=$OP+2;
        if($SB<$OP){
        $SB_OP=2;
        }else{    
        $OP=$OP+2;
        if($SB<$OP){
        $SB_OP=3;
        }
        }}}}
        
          $OP=10;
        if($CR<$OP){
        $CR_OP=-1;
        }else{
        $OP=$OP+2;
        if($CR<$OP){
        $CR_OP=0;
        }else{
        $OP=$OP+2;
        if($CR<$OP){
        $CR_OP=1;
        }else{
        $OP=$OP+2;
        if($CR<$OP){
        $CR_OP=2;
        }else{    
        $OP=$OP+2;
        if($CR<$OP){
        $CR_OP=3;
        }
        }}}}
        
          }else{
               
               echo "<script language='javascript' type='text/javascript'>alert('Erro de atributos, revise as informações.');window.location.href='../perso/criar_perso.php'</script>";
        
               
           }
           
       }else{
           
            echo "<script language='javascript' type='text/javascript'>alert('Erro, atributos insuficientes.');window.location.href='../perso/criar_perso.php'</script>";
        
               
       }
        $prof = "INSERT INTO proficiencias(acrobacias_perso, arcanismo_perso, atletismo_perso, atuacao_perso, blefar_perso, furtividade_perso, historia_perso, intimidacao_perso, intuicao_perso, investigacao_perso, lidar_com_animais_perso, medicina_perso, natureza_perso, percepcao_perso, persuasao_perso, prestidigitacao_perso, religiao_perso, sobrevivecia_perso, nm, atrib_perso) VALUES ('$DS_OP','$IT_OP','$FC_OP','$CR_OP','$CR_OP', '$DS_OP', '$IT_OP', '$CR_OP', '$SB_OP', '$IT_OP', '$SB_OP', '$SB_OP', '$IT_OP', '$SB_OP', '$CR_OP', '$DS_OP', '$IT_OP', '$SB_OP', '$nm_p', '$niv')";
        
        $prof_f = mysqli_query($link, $prof);
        $pf="SELECT * FROM proficiencias WHERE nm = '$nm_p'   LIMIT 1";
        $pfa = mysqli_query($link, $pf);
        
        if( $pfa->num_rows == 1){
            
        }else{
            echo "<script language='javascript' type='text/javascript'>alert('Erro proficiciencia, revise as informações.');window.location.href='../perso/criar_perso.php'</script>";   
        }
        
    
        $invent="INSERT INTO inventario(nm_arma, nm) VALUES ('$arma','$nm_p')";
        $invent1 = mysqli_query($link, $invent);
    
        $iv="SELECT * FROM inventario WHERE nm = '$nm_p'   LIMIT 1";
        $iv1 = mysqli_query($link, $iv);
        
        
         if($iv1->num_rows == 1){
             
             $iv2 = mysqli_fetch_assoc($iv1);
             $id_iv = $iv2['id_invent'];
         }else{
              echo "<script language='javascript' type='text/javascript'>alert('Erro inventario, revise as informações.');window.location.href='../perso/criar_perso.php'</script>";
         }
            
            
        
        if($classe=="Clerico" or $classe=="Bruxo" or $classe=="Mago" or $classe=="Paladino" or $classe=="Bardo" or $classe=="Druida" or $classe=="Feiticeiro" or $classe=="Monge"){
           $vida=15;
            $mana=4;
             
            
             $result_usuario = "INSERT INTO persona(email, nm_do_perso, nm_classe, nm_especie,  hp_pl, mana_pl, nivel_pl, id_invent, atrib_perso,  sala) VALUES ('$ems','$nm_p','$classe','$raca', '$vida', '$mana', '$nivel', '$id_iv', '$niv', '$nt')";
                $retu = mysqli_query($link, $result_usuario);
            
             $result_salas= "INSERT INTO salas(nm_do_perso, email, id_sl) VALUES ('$nm_p','$ems','$nt')";
    $retus = mysqli_query($link, $result_salas);
            
        $pr="SELECT * FROM persona WHERE nm_do_perso = '$nm_p'   LIMIT 1";
        $prs = mysqli_query($link, $pr);
        
        
         if($prs->num_rows == 1){
             echo "<script language='javascript' type='text/javascript'>alert('Personagem cadastrado com sucesso!');window.location.href='../sal/sala_mestre.php?id=".$nt."'</script>";
            
 
             }else{
             
              echo "<script language='javascript' type='text/javascript'>alert('Erro persona, revise as informações.');window.location.href='../perso/criar_perso.php'</script>";
         }
             
             
    }else{
     $vida=15;
            $mana=0;
            
            
             $result_usuario = "INSERT INTO persona(email, nm_do_perso, nm_classe, nm_especie,  hp_pl, mana_pl, nivel_pl, id_invent, atrib_perso,  sala) VALUES ('$ems','$ff','$classe','$raca', '$vida', '$mana', '$nivel', '$id_iv', '$niv', '$nt')";
    $retu = mysqli_query($link, $result_usuario);
            
              $result_salas= "INSERT INTO salas(nm_do_perso, email, id_sl) VALUES ('$nm_p','$ems','$nt')";
    $retus = mysqli_query($link, $result_salas);
  echo "<script language='javascript' type='text/javascript'>alert('Personagem cadastrado com sucesso!');window.location.href='../sal/sala_mestre.php?id=".$nt."'</script>";
    
    }
        
             
        $pr="SELECT * FROM persona WHERE nm_do_perso = '$nm_p'   LIMIT 1";
        $prd = mysqli_query($link, $pr);
        
        
         if($prd->num_rows == 1){
             
            
    
             }else{
             
              echo "<script language='javascript' type='text/javascript'>alert('Erro persona, revise as informações.');window.location.href='../perso/criar_perso.php'</script>";
         }
        
        
    }

?>

