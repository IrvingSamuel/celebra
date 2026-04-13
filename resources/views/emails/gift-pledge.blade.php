<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Presente confirmado – {{ config('app.name') }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; display: block; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #F4F4F4; }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#F4F4F4;font-family:'Poppins',Arial,sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F4F4F4;">
        <tr>
            <td align="center" style="padding:40px 20px;">

                <table border="0" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="background:linear-gradient(135deg,#FF477E 0%,#B73058 100%);padding:40px 40px 32px;">
                            <div style="width:64px;height:64px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:16px;line-height:64px;text-align:center;">
                                <span style="font-size:30px;">🎁</span>
                            </div>
                            <h1 style="margin:0;font-size:26px;font-weight:700;color:#ffffff;letter-spacing:-0.5px;">
                                Presente confirmado!
                            </h1>
                            <p style="margin:6px 0 0;font-size:14px;color:rgba(255,255,255,0.85);">
                                {{ $eventTitle }}
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px 48px 32px;">

                            <p style="margin:0 0 20px;font-size:15px;color:#6B7280;line-height:1.7;">
                                Olá, <strong style="color:#49516F;">{{ $pledge->giver_name }}</strong>! 🎉<br>
                                Você marcou que vai presentear em <strong style="color:#49516F;">{{ $eventTitle }}</strong>. Que ótimo!
                            </p>

                            {{-- Item box --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:28px;">
                                <tr>
                                    <td style="background:#FFF5F8;border-radius:12px;padding:16px 20px;">
                                        <p style="margin:0 0 4px;font-size:12px;font-weight:600;color:#FF477E;text-transform:uppercase;letter-spacing:0.8px;">Seu presente</p>
                                        <p style="margin:0;font-size:17px;font-weight:700;color:#1c1917;">{{ $pledge->giftItem->name }}</p>
                                        @if($pledge->giftItem->price)
                                            <p style="margin:6px 0 0;font-size:14px;color:#78716c;">
                                                R$ {{ number_format($pledge->giftItem->price, 2, ',', '.') }}
                                                @if($pledge->giftItem->platform)
                                                    · {{ $pledge->giftItem->platform }}
                                                @endif
                                            </p>
                                        @endif
                                        @if($pledge->giftItem->url)
                                            <p style="margin:10px 0 0;">
                                                <a href="{{ $pledge->giftItem->url }}" target="_blank"
                                                   style="font-size:13px;color:#FF477E;text-decoration:none;font-weight:600;">
                                                    Ver produto →
                                                </a>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 28px;font-size:14px;color:#6B7280;line-height:1.7;">
                                Se mudar de ideia, você pode cancelar sua marcação a qualquer momento clicando no botão abaixo.
                                O contador do presente será ajustado automaticamente.
                            </p>

                            {{-- Cancel CTA --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom:32px;">
                                        <a href="{{ $cancelUrl }}"
                                           style="display:inline-block;background-color:#f9f5f6;border:2px solid #fda4af;color:#9f1239;font-family:Arial,sans-serif;font-size:14px;font-weight:600;text-decoration:none;padding:12px 32px;border-radius:100px;">
                                            Cancelar minha marcação
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding:20px 48px 32px;border-top:1px solid #f0ede8;">
                            <p style="margin:0;font-size:12px;color:#a8a29e;line-height:1.6;">
                                Enviado por <a href="{{ config('app.url') }}" style="color:#FF477E;text-decoration:none;font-weight:600;">{{ config('app.name') }}</a>.<br>
                                Se não foi você quem marcou este presente, ignore este e-mail.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
