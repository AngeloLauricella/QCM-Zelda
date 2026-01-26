<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260126103947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Remove old User relationship, establish new Player relationship
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY `FK_32993751A76ED395`');
        $this->addSql('DROP INDEX IDX_32993751A76ED395 ON score');
        $this->addSql('ALTER TABLE score CHANGE user_id player_id INT NOT NULL');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375199E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3299375199E6F5DF ON score (player_id)');
        
        // Remove old int score column from players
        $this->addSql('ALTER TABLE players DROP score');
    }

    public function down(Schema $schema): void
    {
        // Revert: Restore old structure
        $this->addSql('ALTER TABLE players ADD score INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_3299375199E6F5DF');
        $this->addSql('DROP INDEX UNIQ_3299375199E6F5DF ON score');
        $this->addSql('ALTER TABLE score CHANGE player_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT `FK_32993751A76ED395` FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_32993751A76ED395 ON score (user_id)');
    }
}
