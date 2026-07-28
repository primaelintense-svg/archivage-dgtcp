<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; color: #23241F; line-height: 1.5; max-width: 560px; margin: 0 auto; padding: 20px;">

    <p>Bonjour {{ $user->prenom }} {{ $user->nom }},</p>

    <p>
        Nous vous confirmons que votre demande d'accès au système d'archivage numérique
        de la Direction Générale du Trésor et de la Comptabilité Publique (DGTCP) a bien été
        examinée et acceptée par notre équipe.
    </p>

    <p>
        Vous disposez désormais d'un espace personnel vous permettant de rechercher et de
        consulter les documents comptables archivés qui vous sont accessibles.
    </p>

    <p><strong>Voici les informations nécessaires pour accéder à votre espace :</strong></p>

    <table style="border-collapse: collapse; margin: 16px 0;">
        <tr>
            <td style="padding: 6px 12px 6px 0; color: #5B5A52;">Adresse de connexion</td>
            <td style="padding: 6px 0;"><a href="{{ route('login') }}">{{ route('login') }}</a></td>
        </tr>
        <tr>
            <td style="padding: 6px 12px 6px 0; color: #5B5A52;">Identifiant</td>
            <td style="padding: 6px 0;">{{ $user->email }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px 6px 0; color: #5B5A52;">Code d'accès provisoire</td>
            <td style="padding: 6px 0;">{{ $motDePasseTemporaire }}</td>
        </tr>
    </table>

    <p>
        Par mesure de sécurité, il vous sera demandé de définir votre propre code d'accès
        dès votre première connexion. Ce code provisoire ne pourra plus être utilisé ensuite.
    </p>

    <p>
        Pour toute question concernant votre accès, vous pouvez contacter le service
        gestionnaire du système d'archivage.
    </p>

    <p style="margin-top: 28px;">
        Cordialement,<br>
        Direction Générale du Trésor et de la Comptabilité Publique<br>
        Trésor Public du Bénin
    </p>

</body>
</html>