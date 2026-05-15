<?php

namespace App\Services;

use App\Mail\PartnerInvitationMail;
use App\Models\PartnerInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ConviteService
{
    public function criar(int $criadoPor, int $categoriaId, string $email): PartnerInvitation
    {
        $invitation = PartnerInvitation::create([
            'criado_por' => $criadoPor,
            'categoria_id' => $categoriaId,
            'email' => $email,
            'token' => Str::random(48),
            'expira_em' => now()->addDays(7),
        ]);

        Mail::to($invitation->email)->send(new PartnerInvitationMail($invitation));

        return $invitation;
    }

    public function cancelar(PartnerInvitation $invitation): void
    {
        $invitation->delete();
    }
}
