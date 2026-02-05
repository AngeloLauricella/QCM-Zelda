<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260205150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create zone_progress table for zone progression tracking';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE zone_progress (
                id SERIAL PRIMARY KEY,
                player_id INTEGER NOT NULL,
                zone_id INTEGER NOT NULL,
                status VARCHAR(20) NOT NULL DEFAULT \'locked\',
                questions_answered INTEGER NOT NULL DEFAULT 0,
                questions_correct INTEGER NOT NULL DEFAULT 0,
                zone_score INTEGER NOT NULL DEFAULT 0,
                started_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                completed_at TIMESTAMP NULL,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_zone_progress_player FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE,
                CONSTRAINT fk_zone_progress_zone FOREIGN KEY (zone_id) REFERENCES zones (id) ON DELETE CASCADE,
                CONSTRAINT unique_progress UNIQUE (player_id, zone_id)
            )
        ');

        $this->addSql('CREATE INDEX idx_zone_progress_player ON zone_progress(player_id)');
        $this->addSql('CREATE INDEX idx_zone_progress_zone ON zone_progress(zone_id)');
        $this->addSql('CREATE INDEX idx_zone_progress_status ON zone_progress(status)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS zone_progress');
    }
}
