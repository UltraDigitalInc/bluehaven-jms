<?php

$pat='80000002-1289331065';

if (preg_match('/-/',$pat))
{
    echo 'Match!';
}
else
{
    echo 'No Match';
}

?>