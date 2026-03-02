<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation ColocManager</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #4f46e5, #6366f1); padding: 40px 32px; text-align: center;">
            <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <span style="font-size: 24px; color: #ffffff;">🏠</span>
            </div>
            <h1 style="color: #ffffff; font-size: 24px; font-weight: 800; margin: 0;">Vous êtes invité !</h1>
        </div>

        <!-- Content -->
        <div style="padding: 40px 32px; text-align: center;">
            <p style="color: #64748b; font-size: 14px; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                Invitation de {{ $inviterName }}
            </p>
            <h2 style="color: #1e293b; font-size: 28px; font-weight: 800; margin: 0 0 24px 0;">
                {{ $groupName }}
            </h2>
            
            <div style="background: #f8fafc; border-radius: 16px; padding: 24px; margin-bottom: 32px; border: 1px solid #e2e8f0;">
                <p style="color: #475569; font-size: 15px; line-height: 1.6; margin: 0; font-style: italic;">
                    "{{ $inviterName }} vous invite à rejoindre le groupe <strong>{{ $groupName }}</strong> sur ColocManager pour gérer vos dépenses communes plus facilement."
                </p>
            </div>

            <a href="{{ $inviteUrl }}" style="display: inline-block; background: #4f46e5; color: #ffffff; font-size: 16px; font-weight: 700; text-decoration: none; padding: 16px 48px; border-radius: 16px; box-shadow: 0 4px 16px rgba(79,70,229,0.3);">
                Rejoindre le groupe →
            </a>

            <p style="color: #94a3b8; font-size: 12px; margin-top: 32px; line-height: 1.5;">
                Ce lien d'invitation expire dans 1 heure.<br>
                Si vous n'avez pas demandé cette invitation, ignorez simplement cet email.
            </p>
        </div>

        <!-- Footer -->
        <div style="background: #f8fafc; padding: 20px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                © {{ date('Y') }} ColocManager — Simplifiez la vie à plusieurs.
            </p>
        </div>
    </div>
</body>
</html>
