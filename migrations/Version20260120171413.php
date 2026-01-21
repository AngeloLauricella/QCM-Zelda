<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260120171413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE players ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_264E43A6A76ED395 ON players (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A76ED395');
        $this->addSql('DROP INDEX IDX_264E43A6A76ED395 ON players');
        $this->addSql('ALTER TABLE players DROP user_id');
    }
}
