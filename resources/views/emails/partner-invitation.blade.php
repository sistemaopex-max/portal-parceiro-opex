<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite para cadastro — Portal Opex</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background-color: #1e293b; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; margin: 0; }
        .body { padding: 40px; color: #374151; }
        .body p { line-height: 1.7; margin-bottom: 16px; }
        .btn { display: inline-block; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: bold; font-size: 15px; margin: 16px 0; }
        .info-box { background: #f8fafc; border-left: 4px solid #4f46e5; padding: 16px 20px; border-radius: 4px; margin: 24px 0; font-size: 14px; color: #6b7280; }
        .footer { background: #f8fafc; padding: 24px 40px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Portal Opex — Convite de Cadastro</h1>
        </div>
        <div class="body">
            <p>Olá!</p>

            <p>
                Você foi convidado(a) para se cadastrar no <strong>Portal do Parceiro Opex</strong>
                como prestador(a) na categoria <strong>{{ $invitation->category?->nome }}</strong>.
            </p>

            <p>
                Clique no botão abaixo para criar sua conta e cadastrar a sua empresa.
                Após o primeiro cadastro, você poderá adicionar outras filiais pelo portal.
            </p>

            <p style="text-align: center;">
                <a href="{{ $url }}" class="btn">Acessar convite e criar conta</a>
            </p>

            <div class="info-box">
                <strong>Atenção:</strong> Este convite expira em <strong>{{ $invitation->expira_em->format('d/m/Y \à\s H:i') }}</strong>.
                Se você não solicitou este acesso, ignore este e-mail.
            </div>

            <p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:</p>
            <p style="word-break: break-all; font-size: 13px; color: #6b7280;">{{ $url }}</p>
        </div>
        <div class="footer">
            <p>Sistema Opex &bull; Este é um e-mail automático, por favor não responda.</p>
        </div>
    </div>
</body>
</html>
