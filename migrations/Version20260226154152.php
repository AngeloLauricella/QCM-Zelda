<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260226154152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update score table structure safely';
    }

    public function up(Schema $schema): void
    {
        // Default update
        $this->addSql('ALTER TABLE players ALTER is_active SET DEFAULT true');

        // 🔥 Ajouter la colonne en nullable d'abord (IMPORTANT)
        $this->addSql('ALTER TABLE score ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE');

        // Remplir les lignes existantes pour éviter NOT NULL error
        $this->addSql('UPDATE score SET updated_at = NOW() WHERE updated_at IS NULL');

        // Ensuite activer la contrainte NOT NULL
        $this->addSql('ALTER TABLE score ALTER COLUMN updated_at SET NOT NULL');

        // Autres colonnes
        $this->addSql('ALTER TABLE score ADD questions_correct INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE score ADD questions_attempted INT DEFAULT 0 NOT NULL');

        $this->addSql('ALTER TABLE score ALTER value SET DEFAULT 0');
        $this->addSql('ALTER TABLE score ALTER value SET NOT NULL');
        $this->addSql('ALTER TABLE score ALTER created_at SET NOT NULL');

        $this->addSql('COMMENT ON COLUMN score.updated_at IS \'(DC2Type:datetime_immutable)\'');

        // Index
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_score_player ON score (player_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_score_value ON score (value)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_score_updated_at ON score (updated_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_score_player');
        $this->addSql('DROP INDEX IF EXISTS idx_score_value');
        $this->addSql('DROP INDEX IF EXISTS idx_score_updated_at');

        $this->addSql('ALTER TABLE score DROP COLUMN IF EXISTS updated_at');
        $this->addSql('ALTER TABLE score DROP COLUMN IF EXISTS questions_correct');
        $this->addSql('ALTER TABLE score DROP COLUMN IF EXISTS questions_attempted');

        $this->addSql('ALTER TABLE score ALTER value DROP DEFAULT');
        $this->addSql('ALTER TABLE score ALTER value DROP NOT NULL');
        $this->addSql('ALTER TABLE score ALTER created_at DROP NOT NULL');

        $this->addSql('ALTER TABLE players ALTER is_active DROP DEFAULT');
    }
}
