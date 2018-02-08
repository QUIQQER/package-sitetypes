<?php

use QUI\Bricks\Controls\SimpleContact;

$Contact = new SimpleContact(array(
    'data-ajax' => 0,
    'mailTo'    => $Site->getAttribute('quiqqer.settings.sitetypes.contact.email')
));


$Engine->assign(array(
    'Contact' => $Contact
));
