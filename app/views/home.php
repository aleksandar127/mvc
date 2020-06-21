<?php

$this->start('body');
echo '<h1>Home</h1>';

if($this->data):
?>
    <ul class="list-group">
<?php
    foreach($this->data as $obj): ?>
        <li class="list-group-item"><?= $obj ?></li>
   <?php endforeach; ?>
   </ul>
<?php
else:
    echo 'Nema rezultata!';
endif;
$this->end('body');





 

