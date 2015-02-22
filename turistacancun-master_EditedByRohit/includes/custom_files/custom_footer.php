<?
// inicio para el google taps

$contents = ob_get_contents(); 
ob_end_clean(); 
echo replace_for_mod_rewrite($contents); 

// fin para el google taps
?>