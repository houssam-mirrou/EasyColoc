<!DOCTYPE html>
<html>

<head>
    <title>Invitation EasyColoc</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">
    <h2 style="color: #2563eb;">Bonjour ! 👋</h2>
    <p>Vous avez été invité(e) à rejoindre la colocation <strong>{{ $colocation->name }}</strong> sur EasyColoc.</p>

    <div
        style="background-color: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0; max-width: 400px; word-break: break-all;">
        <span style="font-size: 18px; font-weight: bold; color: #1e3a8a;">
            {{ $invitation->token }}
        </span>
    </div>

    @if($user_exist)
    <p>Puisque vous avez déjà un compte chez nous, il vous suffit de vous connecter et de coller ce code dans votre
        tableau de bord pour nous rejoindre !</p>

    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ route('user.dashboard') }}"
            style="background-color: #2563eb; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Aller
            sur mon Tableau de Bord</a>
    </p>
    @else
    <p>Pour accepter cette invitation, vous devez d'abord créer un compte gratuit sur notre plateforme. Gardez le code
        ci-dessus, il vous sera demandé !</p>

    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ route('register') }}"
            style="background-color: #111827; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Créer
            mon compte</a>
    </p>
    @endif

    <p style="margin-top: 30px;">À très vite !<br>L'équipe EasyColoc</p>
</body>

</html>