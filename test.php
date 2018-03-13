<?php
include "library/pcFunction.php";
echo head("ad");
$file = ".user.ini";
?>
<style>
.t{ width:1000px; height:800px;}
</style>
<textarea class="t"><?php echo file_get_contents($file);?></textarea>