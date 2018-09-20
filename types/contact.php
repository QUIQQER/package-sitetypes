<?php

use QUI\Bricks\Controls\SimpleContact;

$Contact = new SimpleContact([
    'data-ajax'                 => 0,
    'mailTo'                    => $Site->getAttribute('quiqqer.settings.sitetypes.contact.email'),
    'showPrivacyPolicyCheckbox' => boolval($Site->getAttribute('quiqqer.settings.sitetypes.contact.showPrivacyPolicyCheckbox')),
    'useCaptcha'                => boolval($Site->getAttribute('quiqqer.settings.sitetypes.contact.useCaptcha'))
]);


$Engine->assign([
    'Contact' => $Contact
]);
