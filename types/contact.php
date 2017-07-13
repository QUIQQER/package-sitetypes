<?php

use QUI\Utils\Security\Orthos;
use QUI\Bricks\Controls\SimpleContact;

$Contact = new SimpleContact(array(
    'data-ajax' => 0
));

if (isset($_POST['name'])
    && isset($_POST['email'])
    && isset($_POST['message'])
) {
    $reciever = $Site->getAttribute('quiqqer.settings.sitetypes.contact.email');

    $Contact->setAttributes(array(
        'POST_NAME'    => $_POST['name'],
        'POST_EMAIL'   => $_POST['email'],
        'POST_MESSAGE' => $_POST['message']
    ));

    if (!Orthos::checkMailSyntax($_POST['email'])) {
        $Engine->assign(array(
            'errorMessage' => QUI::getLocale()->get(
                'quiqqer/system',
                'exception.not.correct.email'
            )
        ));
    } else {
        try {
            $Mail = new QUI\Mail\Mailer();
            $Mail->setFrom($_POST['email']);
            $Mail->setFromName($_POST['name']);

            $Mail->addRecipient($reciever);
            $Mail->setSubject($Site->getAttribute('title'));

            $Mail->setBody(
                "E-Mail: " . $_POST['email'] . "<br />" .
                "Name: " . $_POST['name'] . "<br /><br />" .
                $_POST['message']
            );

            if ($Mail->send()) {
                $Contact = false;

                $Engine->assign(array(
                    'successMessage' => QUI::getLocale()->get(
                        'quiqqer/system',
                        'message.contact.successful'
                    )
                ));
            }
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::addWarning($Exception->getMessage());

            $Engine->assign(array(
                'errorMessage' => QUI::getLocale()->get(
                    'quiqqer/system',
                    'exception.contact.send.mail'
                )
            ));
        }
    }
}

$Engine->assign(array(
    'Contact' => $Contact
));
