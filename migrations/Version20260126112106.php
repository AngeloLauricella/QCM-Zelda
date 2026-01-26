<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260126112106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_event_completions ADD question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_event_completions ADD CONSTRAINT FK_603E0BC91E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_603E0BC91E27F6BF ON player_event_completions (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_event_completions DROP FOREIGN KEY FK_603E0BC91E27F6BF');
        $this->addSql('DROP INDEX IDX_603E0BC91E27F6BF ON player_event_completions');
        $this->addSql('ALTER TABLE player_event_completions DROP question_id');
    }
}
