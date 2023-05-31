<?php
    $my_con=new mysqli("localhost","root","","sweet_list");
    $res=$my_con->query("select username from users where id=1");
    $row=$res->fetch_assoc();
    echo $row['username'];
?>