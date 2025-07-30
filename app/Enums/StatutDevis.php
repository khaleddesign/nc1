<?php

namespace App\Enums;

enum StatutDevis: string
{
    case PROSPECT_BROUILLON = 'prospect_brouillon';
    case PROSPECT_ENVOYE = 'prospect_envoye';
    case PROSPECT_NEGOCIE = 'prospect_negocie';
    case PROSPECT_ACCEPTE = 'prospect_accepte';
    case CHANTIER_VALIDE = 'chantier_valide';
    case FACTURABLE = 'facturable';
    case FACTURE = 'facture';

    public function label(): string
    {
        return match($this) {
            self::PROSPECT_BROUILLON => 'Prospect - Brouillon',
            self::PROSPECT_ENVOYE => 'Prospect - Envoyé',
            self::PROSPECT_NEGOCIE => 'Prospect - En négociation',
            self::PROSPECT_ACCEPTE => 'Prospect - Accepté',
            self::CHANTIER_VALIDE => 'Chantier - Validé',
            self::FACTURABLE => 'Prêt à facturer',
            self::FACTURE => 'Facturé',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PROSPECT_BROUILLON => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
            self::PROSPECT_ENVOYE => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
            self::PROSPECT_NEGOCIE => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
            self::PROSPECT_ACCEPTE => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800',
            self::CHANTIER_VALIDE => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800',
            self::FACTURABLE => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800',
            self::FACTURE => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800',
        };
    }

    public function isProspect(): bool
    {
        return in_array($this, [
            self::PROSPECT_BROUILLON,
            self::PROSPECT_ENVOYE,
            self::PROSPECT_NEGOCIE,
            self::PROSPECT_ACCEPTE,
        ]);
    }

    public function isChantier(): bool
    {
        return in_array($this, [
            self::CHANTIER_VALIDE,
            self::FACTURABLE,
            self::FACTURE,
        ]);
    }

    public function peutEtreModifie(): bool
    {
        return in_array($this, [
            self::PROSPECT_BROUILLON,
            self::PROSPECT_NEGOCIE,
            self::CHANTIER_VALIDE,
        ]);
    }

    public function peutEtreConverti(): bool
    {
        return $this === self::PROSPECT_ACCEPTE;
    }

    public function peutEtreEnvoye(): bool
    {
        return in_array($this, [
            self::PROSPECT_BROUILLON,
            self::PROSPECT_NEGOCIE,
        ]);
    }

    public function peutEtreAccepte(): bool
    {
        return in_array($this, [
            self::PROSPECT_ENVOYE,
            self::PROSPECT_NEGOCIE,
        ]);
    }

    public function peutEtreFacture(): bool
    {
        return $this === self::FACTURABLE;
    }

    public function icon(): string
    {
        return match($this) {
            self::PROSPECT_BROUILLON => '📝',
            self::PROSPECT_ENVOYE => '📤',
            self::PROSPECT_NEGOCIE => '🔄',
            self::PROSPECT_ACCEPTE => '✅',
            self::CHANTIER_VALIDE => '🏗️',
            self::FACTURABLE => '💰',
            self::FACTURE => '🧾',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PROSPECT_BROUILLON => 'gray',
            self::PROSPECT_ENVOYE => 'blue',
            self::PROSPECT_NEGOCIE => 'yellow',
            self::PROSPECT_ACCEPTE => 'green',
            self::CHANTIER_VALIDE => 'purple',
            self::FACTURABLE => 'orange',
            self::FACTURE => 'emerald',
        };
    }

    public function getProchainStatutsPossibles(): array
    {
        return match($this) {
            self::PROSPECT_BROUILLON => [self::PROSPECT_ENVOYE],
            self::PROSPECT_ENVOYE => [self::PROSPECT_NEGOCIE, self::PROSPECT_ACCEPTE],
            self::PROSPECT_NEGOCIE => [self::PROSPECT_ENVOYE, self::PROSPECT_ACCEPTE],
            self::PROSPECT_ACCEPTE => [self::CHANTIER_VALIDE],
            self::CHANTIER_VALIDE => [self::FACTURABLE],
            self::FACTURABLE => [self::FACTURE],
            self::FACTURE => [],
        };
    }

    public static function getProspectStatuts(): array
    {
        return [
            self::PROSPECT_BROUILLON,
            self::PROSPECT_ENVOYE,
            self::PROSPECT_NEGOCIE,
            self::PROSPECT_ACCEPTE,
        ];
    }

    public static function getChantierStatuts(): array
    {
        return [
            self::CHANTIER_VALIDE,
            self::FACTURABLE,
            self::FACTURE,
        ];
    }

    public static function getStatutsModifiables(): array
    {
        return [
            self::PROSPECT_BROUILLON,
            self::PROSPECT_NEGOCIE,
            self::CHANTIER_VALIDE,
        ];
    }
}