<?php
imagepng($image);
imagedestroy($image);
if(isset($postfix)) echo $postfix;