<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122085412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_events (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, reward_hearts INT NOT NULL, reward_points INT NOT NULL, penalty_hearts INT NOT NULL, penalty_points INT NOT NULL, is_one_time_only TINYINT NOT NULL, is_active TINYINT NOT NULL, display_order INT NOT NULL, zone_id INT NOT NULL, INDEX IDX_2EB2FA829F2C3FAB (zone_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE player_event_completions (id INT AUTO_INCREMENT NOT NULL, completed_at DATETIME NOT NULL, hearts_earned INT NOT NULL, points_earned INT NOT NULL, game_progress_id INT NOT NULL, game_event_id INT NOT NULL, INDEX IDX_603E0BC99D8EC5CB (game_progress_id), INDEX IDX_603E0BC9DAB317AD (game_event_id), UNIQUE INDEX unique_player_event (game_progress_id, game_event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE zones (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, display_order INT NOT NULL, min_points_to_unlock INT NOT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE game_events ADD CONSTRAINT FK_2EB2FA829F2C3FAB FOREIGN KEY (zone_id) REFERENCES zones (id)');
        $this->addSql('ALTER TABLE player_event_completions ADD CONSTRAINT FK_603E0BC99D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id)');
        $this->addSql('ALTER TABLE player_event_completions ADD CONSTRAINT FK_603E0BC9DAB317AD FOREIGN KEY (game_event_id) REFERENCES game_events (id)');
        $this->addSql('ALTER TABLE game_progress ADD hearts INT NOT NULL, ADD points INT NOT NULL, ADD current_zone_id INT NOT NULL, DROP current_step, DROP current_score');
        $this->addSql('ALTER TABLE questions ADD reward_hearts INT NOT NULL, ADD reward_points INT NOT NULL, ADD penalty_hearts INT NOT NULL, ADD penalty_points INT NOT NULL, ADD is_one_time_only TINYINT NOT NULL, ADD zone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D59F2C3FAB FOREIGN KEY (zone_id) REFERENCES zones (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D59F2C3FAB ON questions (zone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_events DROP FOREIGN KEY FK_2EB2FA829F2C3FAB');
        $this->addSql('ALTER TABLE player_event_completions DROP FOREIGN KEY FK_603E0BC99D8EC5CB');
        $this->addSql('ALTER TABLE player_event_completions DROP FOREIGN KEY FK_603E0BC9DAB317AD');
        $this->addSql('DROP TABLE game_events');
        $this->addSql('DROP TABLE player_event_completions');
        $this->addSql('DROP TABLE zones');
        $this->addSql('ALTER TABLE game_progress ADD current_step INT NOT NULL, ADD current_score INT NOT NULL, DROP hearts, DROP points, DROP current_zone_id');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D59F2C3FAB');
        $this->addSql('DROP INDEX IDX_8ADC54D59F2C3FAB ON questions');
        $this->addSql('ALTER TABLE questions DROP reward_hearts, DROP reward_points, DROP penalty_hearts, DROP penalty_points, DROP is_one_time_only, DROP zone_id');
    }
}
