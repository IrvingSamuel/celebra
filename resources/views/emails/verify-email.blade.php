<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Confirme seu e-mail – {{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #F4F4F4; }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#F4F4F4;font-family:'Poppins',Arial,sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F4F4F4;">
        <tr>
            <td align="center" style="padding:40px 20px;">

                {{-- Container --}}
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header com gradiente --}}
                    <tr>
                        <td align="center" style="background:linear-gradient(135deg,#FF477E 0%,#B73058 100%);padding:40px 40px 32px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        {{-- Ícone de envelope --}}
                                        <div style="width:64px;height:64px;background:rgba(255,255,255,0.2);border-radius:50%;display:inline-block;margin-bottom:16px;line-height:64px;text-align:center;">
                                            <span style="font-size:30px; color:white">✦</span>
                                        </div>
                                        <h1 style="margin:0;font-size:26px;font-weight:700;color:#ffffff;letter-spacing:-0.5px;">
                                            {{ config('app.name') }}
                                        </h1>
                                        <p style="margin:6px 0 0;font-size:14px;color:rgba(255,255,255,0.85);font-weight:400;">
                                            {{ $isSupplier ? 'Portal de Fornecedores' : 'Plataforma de Eventos' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Corpo --}}
                    <tr>
                        <td style="padding:40px 48px 32px;">
                            <h2 style="margin:0 0 8px;font-size:22px;font-weight:700;color:#49516F;">
                                Confirme seu e-mail
                            </h2>
                            <p style="margin:0 0 24px;font-size:15px;color:#6B7280;line-height:1.6;">
                                Olá, <strong style="color:#49516F;">{{ $userName }}</strong>!<br>
                                Obrigado por se cadastrar. Clique no botão abaixo para confirmar seu endereço de e-mail e
                                @if($isSupplier)
                                    ativar sua conta de fornecedor.
                                @else
                                    ativar sua conta.
                                @endif
                            </p>

                            {{-- Botão CTA --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding:8px 0 32px;">
                                        <a href="{{ $verificationUrl }}"
                                           target="_blank"
                                           style="display:inline-block;background-color:#FF477E;color:#ffffff;font-family:'Poppins',Arial,sans-serif;font-size:15px;font-weight:600;text-decoration:none;padding:14px 40px;border-radius:100px;letter-spacing:0.3px;">
                                            Confirmar e-mail
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Aviso para fornecedores --}}
                            @if($isSupplier)
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:24px;">
                                <tr>
                                    <td style="background-color:#FFF5F8;border-left:4px solid #FF477E;border-radius:0 8px 8px 0;padding:14px 18px;">
                                        <p style="margin:0;font-size:13px;color:#B73058;font-weight:600;">
                                            📋 Próximos passos para fornecedores
                                        </p>
                                        <p style="margin:6px 0 0;font-size:13px;color:#6B7280;line-height:1.5;">
                                            Após confirmar seu e-mail, nossa equipe irá revisrar e aprovar seu cadastro.
                                            Você será notificado quando sua conta estiver ativa.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            {{-- Divider --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="border-top:1px solid #F1F1F1;padding-top:24px;">
                                        <p style="margin:0 0 8px;font-size:13px;color:#9CA3AF;line-height:1.5;">
                                            Se você não solicitou este cadastro, ignore este e-mail — nenhuma ação é necessária.
                                        </p>
                                        <p style="margin:0;font-size:13px;color:#9CA3AF;line-height:1.5;">
                                            Este link expira em <strong>60 minutos</strong>. Caso precise de um novo link,
                                            acesse o aplicativo e clique em <em>"Reenviar e-mail de confirmação"</em>.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Link de fallback --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:20px;">
                                <tr>
                                    <td style="background-color:#F9FAFB;border-radius:8px;padding:14px 18px;">
                                        <p style="margin:0 0 4px;font-size:12px;color:#6B7280;font-weight:500;">
                                            Botão não funcionou? Copie e cole este link no navegador:
                                        </p>
                                        <p style="margin:0;font-size:11px;color:#5465FF;word-break:break-all;">
                                            {{ $verificationUrl }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="background-color:#F9FAFB;padding:24px 40px;border-top:1px solid #F1F1F1;">
                            <p style="margin:0 0 4px;font-size:13px;color:#9CA3AF;">
                                © {{ date('Y') }} <strong style="color:#49516F;">{{ config('app.name') }}</strong> · Todos os direitos reservados
                            </p>
                            <p style="margin:0;font-size:12px;color:#C7C7C7;">
                                Este é um e-mail automático, não responda a esta mensagem.
                            </p>
                        </td>
                    </tr>

                </table>
                {{-- /Container --}}

            </td>
        </tr>
    </table>

</body>
</html>
