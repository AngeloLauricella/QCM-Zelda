<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205093657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_gallery_items ALTER purchased_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN player_gallery_items.purchased_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE score ALTER value DROP NOT NULL');
        $this->addSql('ALTER TABLE score ALTER created_at DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE score ALTER value SET NOT NULL');
        $this->addSql('ALTER TABLE score ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE player_gallery_items ALTER purchased_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN player_gallery_items.purchased_at IS NULL');
    }
}
