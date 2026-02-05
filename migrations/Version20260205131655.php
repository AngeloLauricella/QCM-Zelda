<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205131655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone_progress (id SERIAL NOT NULL, player_id INT NOT NULL, zone_id INT NOT NULL, status VARCHAR(20) NOT NULL, questions_answered INT NOT NULL, questions_correct INT NOT NULL, zone_score INT NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ABD7EDB99E6F5DF ON zone_progress (player_id)');
        $this->addSql('CREATE INDEX IDX_ABD7EDB9F2C3FAB ON zone_progress (zone_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_progress ON zone_progress (player_id, zone_id)');
        $this->addSql('COMMENT ON COLUMN zone_progress.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN zone_progress.completed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN zone_progress.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE zone_progress ADD CONSTRAINT FK_ABD7EDB99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zone_progress ADD CONSTRAINT FK_ABD7EDB9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zones (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_gallery_items ALTER purchased_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN player_gallery_items.purchased_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE score ALTER value DROP NOT NULL');
        $this->addSql('ALTER TABLE score ALTER created_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE zone_progress DROP CONSTRAINT FK_ABD7EDB99E6F5DF');
        $this->addSql('ALTER TABLE zone_progress DROP CONSTRAINT FK_ABD7EDB9F2C3FAB');
        $this->addSql('DROP TABLE zone_progress');
        $this->addSql('ALTER TABLE score ALTER value SET NOT NULL');
        $this->addSql('ALTER TABLE score ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE player_gallery_items ALTER purchased_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN player_gallery_items.purchased_at IS NULL');
    }
}
