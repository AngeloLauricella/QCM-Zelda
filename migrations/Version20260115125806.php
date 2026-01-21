<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260115125806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_results (id INT AUTO_INCREMENT NOT NULL, user_answer VARCHAR(1) NOT NULL, is_correct TINYINT NOT NULL, points_earned INT NOT NULL, answered_at DATETIME NOT NULL, score_after INT NOT NULL, player_id INT NOT NULL, question_id INT NOT NULL, INDEX idx_player (player_id), INDEX idx_question (question_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, score INT NOT NULL, hearts INT NOT NULL, is_active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_264E43A6E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, option_a VARCHAR(255) NOT NULL, option_b VARCHAR(255) NOT NULL, option_c VARCHAR(255) NOT NULL, option_d VARCHAR(255) NOT NULL, correct_answer VARCHAR(1) NOT NULL, category VARCHAR(50) NOT NULL, display_order INT NOT NULL, points_value INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE game_results ADD CONSTRAINT FK_A619B3B99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_results ADD CONSTRAINT FK_A619B3B1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_results DROP FOREIGN KEY FK_A619B3B99E6F5DF');
        $this->addSql('ALTER TABLE game_results DROP FOREIGN KEY FK_A619B3B1E27F6BF');
        $this->addSql('DROP TABLE game_results');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
