<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260126123517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Make game_event_id nullable - allow tracking question completions without associated events
        $this->addSql('ALTER TABLE player_event_completions CHANGE game_event_id game_event_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Revert: Restore NOT NULL constraint
        $this->addSql('ALTER TABLE player_event_completions CHANGE game_event_id game_event_id INT NOT NULL');
    }
}
