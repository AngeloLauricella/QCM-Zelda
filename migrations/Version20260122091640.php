<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260122091640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_equipment (id INT AUTO_INCREMENT NOT NULL, game_progress_id INT NOT NULL, weapon_equipped_id INT DEFAULT NULL, object1_id INT DEFAULT NULL, object2_id INT DEFAULT NULL, object3_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_7334E0BA9D8EC5CB (game_progress_id), INDEX IDX_7334E0BAEF94B462 (weapon_equipped_id), INDEX IDX_7334E0BA7AF3F985 (object1_id), INDEX IDX_7334E0BA6846566B (object2_id), INDEX IDX_7334E0BAD0FA310E (object3_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE purchase_history (id INT AUTO_INCREMENT NOT NULL, cost_paid INT NOT NULL, purchased_at DATETIME NOT NULL, game_progress_id INT NOT NULL, trophy_id INT NOT NULL, INDEX IDX_3C60BA329D8EC5CB (game_progress_id), INDEX IDX_3C60BA32F59AEEEF (trophy_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE shop_items (id INT AUTO_INCREMENT NOT NULL, cost INT NOT NULL, stock INT NOT NULL, is_available TINYINT NOT NULL, display_order INT NOT NULL, trophy_id INT NOT NULL, INDEX IDX_2608B31FF59AEEEF (trophy_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE trophies (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, unlock_condition LONGTEXT NOT NULL, heart_bonus INT NOT NULL, points_multiplier DOUBLE PRECISION NOT NULL, icon VARCHAR(100) NOT NULL, is_visible TINYINT NOT NULL, display_order INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE trophy_unlocks (id INT AUTO_INCREMENT NOT NULL, unlocked_at DATETIME NOT NULL, game_progress_id INT NOT NULL, trophy_id INT NOT NULL, INDEX IDX_F8BFF6909D8EC5CB (game_progress_id), INDEX IDX_F8BFF690F59AEEEF (trophy_id), UNIQUE INDEX unique_player_trophy (game_progress_id, trophy_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BA9D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id)');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BAEF94B462 FOREIGN KEY (weapon_equipped_id) REFERENCES trophies (id)');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BA7AF3F985 FOREIGN KEY (object1_id) REFERENCES trophies (id)');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BA6846566B FOREIGN KEY (object2_id) REFERENCES trophies (id)');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BAD0FA310E FOREIGN KEY (object3_id) REFERENCES trophies (id)');
        $this->addSql('ALTER TABLE purchase_history ADD CONSTRAINT FK_3C60BA329D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id)');
        $this->addSql('ALTER TABLE purchase_history ADD CONSTRAINT FK_3C60BA32F59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophies (id)');
        $this->addSql('ALTER TABLE shop_items ADD CONSTRAINT FK_2608B31FF59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophies (id)');
        $this->addSql('ALTER TABLE trophy_unlocks ADD CONSTRAINT FK_F8BFF6909D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id)');
        $this->addSql('ALTER TABLE trophy_unlocks ADD CONSTRAINT FK_F8BFF690F59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophies (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_equipment DROP FOREIGN KEY FK_7334E0BA9D8EC5CB');
        $this->addSql('ALTER TABLE player_equipment DROP FOREIGN KEY FK_7334E0BAEF94B462');
        $this->addSql('ALTER TABLE player_equipment DROP FOREIGN KEY FK_7334E0BA7AF3F985');
        $this->addSql('ALTER TABLE player_equipment DROP FOREIGN KEY FK_7334E0BA6846566B');
        $this->addSql('ALTER TABLE player_equipment DROP FOREIGN KEY FK_7334E0BAD0FA310E');
        $this->addSql('ALTER TABLE purchase_history DROP FOREIGN KEY FK_3C60BA329D8EC5CB');
        $this->addSql('ALTER TABLE purchase_history DROP FOREIGN KEY FK_3C60BA32F59AEEEF');
        $this->addSql('ALTER TABLE shop_items DROP FOREIGN KEY FK_2608B31FF59AEEEF');
        $this->addSql('ALTER TABLE trophy_unlocks DROP FOREIGN KEY FK_F8BFF6909D8EC5CB');
        $this->addSql('ALTER TABLE trophy_unlocks DROP FOREIGN KEY FK_F8BFF690F59AEEEF');
        $this->addSql('DROP TABLE player_equipment');
        $this->addSql('DROP TABLE purchase_history');
        $this->addSql('DROP TABLE shop_items');
        $this->addSql('DROP TABLE trophies');
        $this->addSql('DROP TABLE trophy_unlocks');
    }
}
