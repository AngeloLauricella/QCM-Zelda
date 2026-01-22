<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260121145626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_progress (id INT AUTO_INCREMENT NOT NULL, current_step INT NOT NULL, current_score INT NOT NULL, is_game_over TINYINT NOT NULL, game_over_reason VARCHAR(255) DEFAULT NULL, started_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, ended_at DATETIME DEFAULT NULL, player_id INT NOT NULL, UNIQUE INDEX UNIQ_89450D6099E6F5DF (player_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE game_progress ADD CONSTRAINT FK_89450D6099E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE players CHANGE last_step last_step INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE questions ADD step INT NOT NULL, ADD is_active TINYINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_progress DROP FOREIGN KEY FK_89450D6099E6F5DF');
        $this->addSql('DROP TABLE game_progress');
        $this->addSql('ALTER TABLE players CHANGE last_step last_step INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE questions DROP step, DROP is_active');
    }
}
