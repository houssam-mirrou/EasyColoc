<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invitation EasyColoc</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="background-color: #f8fafc; padding: 40px 20px; width: 100%; box-sizing: border-box;">

        <div
            style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">

            <div style="text-align: center; padding: 30px 20px; border-bottom: 1px solid #f8fafc;">
                <h1 style="margin: 0; font-size: 24px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px;">
                    Easy<span style="color: #111827;">Coloc</span>
                </h1>
            </div>

            <div style="padding: 40px 30px; color: #334155; font-size: 16px; line-height: 1.6;">
                <h2 style="margin-top: 0; color: #111827; font-size: 20px; font-weight: bold;">Bonjour ! 👋</h2>
                <p style="margin-bottom: 24px;">Vous avez été invité(e) à rejoindre la colocation <strong
                        style="color: #2563eb;">{{ $colocation->name }}</strong> sur EasyColoc.</p>

                <div
                    style="background-color: #eff6ff; border: 2px dashed #bfdbfe; padding: 24px; border-radius: 12px; text-align: center; margin: 32px 0;">
                    <p
                        style="margin: 0 0 10px 0; font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">
                        Votre code d'invitation</p>
                    <span
                        style="display: inline-block; font-family: 'Courier New', Courier, monospace; font-size: 20px; font-weight: bold; color: #1d4ed8; word-break: break-all; letter-spacing: 1px;">
                        {{ $invitation->token }}
                    </span>
                </div>

                @if($user_exist)
                <p style="margin-bottom: 30px;">Puisque vous avez déjà un compte chez nous, il vous suffit de vous
                    connecter et de coller ce code dans votre tableau de bord pour nous rejoindre !</p>

                <div style="text-align: center;">
                    <a href="{{ route('user.dashboard') }}"
                        style="display: inline-block; background-color: #2563eb; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                        Aller sur mon Tableau de Bord
                    </a>
                </div>
                @else
                <p style="margin-bottom: 30px;">Pour accepter cette invitation, vous devez d'abord créer un compte
                    gratuit sur notre plateforme. Gardez le code ci-dessus, il vous sera demandé !</p>

                <div style="text-align: center;">
                    <a href="{{ route('register') }}"
                        style="display: inline-block; background-color: #111827; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                        Créer mon compte
                    </a>
                </div>
                @endif
            </div>

            <div style="background-color: #f8fafc; padding: 24px; text-align: center; border-top: 1px solid #f1f5f9;">
                <p style="margin: 0; font-size: 14px; color: #64748b;">
                    À très vite !<br>
                    <strong>L'équipe EasyColoc</strong>
                </p>
            </div>

        </div>

        <div style="max-width: 600px; margin: 20px auto 0; text-align: center;">
            <p style="font-size: 12px; color: #94a3b8; margin: 0;">Cet email a été envoyé automatiquement, merci de ne
                pas y répondre.</p>
        </div>

    </div>

</body>

</html>