<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127100649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_gallery_items (id INT AUTO_INCREMENT NOT NULL, purchased_at DATETIME NOT NULL, player_id INT NOT NULL, gallery_item_id INT NOT NULL, INDEX IDX_D06345DA99E6F5DF (player_id), INDEX IDX_D06345DA2A151376 (gallery_item_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE player_gallery_items ADD CONSTRAINT FK_D06345DA99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_gallery_items ADD CONSTRAINT FK_D06345DA2A151376 FOREIGN KEY (gallery_item_id) REFERENCES gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gallery ADD price INT DEFAULT 100 NOT NULL');
        $this->addSql('ALTER TABLE players ADD shop_points INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_gallery_items DROP FOREIGN KEY FK_D06345DA99E6F5DF');
        $this->addSql('ALTER TABLE player_gallery_items DROP FOREIGN KEY FK_D06345DA2A151376');
        $this->addSql('DROP TABLE player_gallery_items');
        $this->addSql('ALTER TABLE gallery DROP price');
        $this->addSql('ALTER TABLE players DROP shop_points');
    }
}
