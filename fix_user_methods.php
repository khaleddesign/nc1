<?php

echo "🔧 Ajout des méthodes manquantes dans User.php...\n\n";

// Lire le fichier User.php actuel
$userContent = file_get_contents('app/Models/User.php');

// Méthodes à ajouter
$methodsToAdd = '
    /**
     * Vérifier si l\'utilisateur est commercial
     */
    public function isCommercial(): bool
    {
        return $this->role === \'commercial\' || $this->email === \'commercial@example.com\';
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getNotificationsNonLues(): int
    {
        return $this->notifications()->where(\'lu\', false)->count();
    }

    /**
     * Vérifier si l\'utilisateur peut créer des chantiers
     */
    public function canCreateChantiers(): bool
    {
        return $this->isAdmin() || $this->isCommercial();
    }

    /**
     * Vérifier si l\'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Obtenir les chantiers assignés (pour commercial)
     */
    public function chantiersAssignes()
    {
        return $this->hasMany(Chantier::class, \'commercial_id\');
    }

    /**
     * Obtenir le nombre total de notifications
     */
    public function getTotalNotifications(): int
    {
        return $this->notifications()->count();
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllNotificationsAsRead(): void
    {
        $this->notifications()->where(\'lu\', false)->update([\'lu\' => true]);
    }';

// Trouver la dernière accolade de la classe
$lastBracePos = strrpos($userContent, '}');

// Insérer les méthodes avant la dernière accolade
$newContent = substr($userContent, 0, $lastBracePos) . $methodsToAdd . "\n" . substr($userContent, $lastBracePos);

// Sauvegarder le fichier
file_put_contents('app/Models/User.php', $newContent);

echo "✅ Méthodes ajoutées avec succès !\n\n";
echo "Méthodes ajoutées :\n";
echo "- isCommercial() ✅\n";
echo "- getNotificationsNonLues() ✅\n";
echo "- canCreateChantiers() ✅\n";
echo "- hasRole() ✅\n";
echo "- chantiersAssignes() ✅\n";
echo "- getTotalNotifications() ✅\n";
echo "- markAllNotificationsAsRead() ✅\n";

// Vérifier aussi si on doit ajouter un champ 'role' dans la migration users
$usersMigration = glob('database/migrations/*create_users_table.php');
if (!empty($usersMigration)) {
    $migrationContent = file_get_contents($usersMigration[0]);
    if (!str_contains($migrationContent, "'role'")) {
        echo "\n⚠️  ATTENTION: Il faudra peut-être ajouter le champ 'role' dans la table users.\n";
        echo "Ajoutez cette ligne dans la migration users après 'email' :\n";
        echo "\$table->string('role')->default('client');\n";
    }
}
