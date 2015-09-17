<?php

    require(__DIR__ . "/../includes/config.php");

    // numerically indexed array of places
    $places = [];
    
    // TODO: search database for places matching $_GET["geo"]
    // 1. eerst de string uit geo als een array opmaken met explode, htlspecialchar, trim
    

        
    $geo = array_map('trim', explode(",", urldecode($_GET["geo"])));
    
   
    // When there is no comma use in the search balk 
    if (count($geo) == 1)
    {
        $geo = explode(" ", $geo[0]);
    }
    
    $sql = "SELECT * FROM places WHERE";
    
    // 2. sql for loop door array if array[i] cijfers dan (postal code) en maak $sql .=
    // als geen cijfers zijn dan wordt uit for-loop gestapt
    $geolength = count($geo);
 
    // loop through the geo variable to make sure a correct query will appear   
    for($i = 0; $i < $geolength; $i++)
    {
    
        if (is_numeric($geo[$i]))
        {
            $sql .= (" postal_code = '$geo[$i]'"); 
           
        }  
        else if ($geolength >= 1)
        {
           if(strlen($geo[$i]) == 2)
           {
               $sql.= " admin_code1 LIKE '$geo[$i]'";
           }
           else
           {
               $sql.= (" admin_name1 LIKE '$geo[$i]' OR place_name LIKE '$geo[$i]'");      
           }
        
        }
            if($i < $geolength-1)
            {
            $sql .= "AND"; 
            }
    }
    
    $places = query($sql); 
    
    //var_dump($places);

    //output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));

?>
