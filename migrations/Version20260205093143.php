<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205093143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gallery (id SERIAL NOT NULL, user_id INT NOT NULL, image_path VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price INT DEFAULT 100 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_472B783AA76ED395 ON gallery (user_id)');
        $this->addSql('COMMENT ON COLUMN gallery.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE game_events (id SERIAL NOT NULL, zone_id INT NOT NULL, title VARCHAR(100) NOT NULL, description TEXT NOT NULL, type VARCHAR(50) NOT NULL, reward_hearts INT NOT NULL, reward_points INT NOT NULL, penalty_hearts INT NOT NULL, penalty_points INT NOT NULL, is_one_time_only BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, display_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2EB2FA829F2C3FAB ON game_events (zone_id)');
        $this->addSql('CREATE TABLE game_progress (id SERIAL NOT NULL, player_id INT NOT NULL, hearts INT NOT NULL, points INT NOT NULL, current_zone_id INT NOT NULL, is_game_over BOOLEAN NOT NULL, game_over_reason VARCHAR(255) DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89450D6099E6F5DF ON game_progress (player_id)');
        $this->addSql('COMMENT ON COLUMN game_progress.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game_progress.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN game_progress.ended_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE game_results (id SERIAL NOT NULL, player_id INT NOT NULL, question_id INT NOT NULL, user_answer VARCHAR(1) NOT NULL, is_correct BOOLEAN NOT NULL, points_earned INT NOT NULL, answered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, score_after INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_player ON game_results (player_id)');
        $this->addSql('CREATE INDEX idx_question ON game_results (question_id)');
        $this->addSql('COMMENT ON COLUMN game_results.answered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE player_equipment (id SERIAL NOT NULL, game_progress_id INT NOT NULL, weapon_equipped_id INT DEFAULT NULL, object1_id INT DEFAULT NULL, object2_id INT DEFAULT NULL, object3_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7334E0BA9D8EC5CB ON player_equipment (game_progress_id)');
        $this->addSql('CREATE INDEX IDX_7334E0BAEF94B462 ON player_equipment (weapon_equipped_id)');
        $this->addSql('CREATE INDEX IDX_7334E0BA7AF3F985 ON player_equipment (object1_id)');
        $this->addSql('CREATE INDEX IDX_7334E0BA6846566B ON player_equipment (object2_id)');
        $this->addSql('CREATE INDEX IDX_7334E0BAD0FA310E ON player_equipment (object3_id)');
        $this->addSql('CREATE TABLE player_event_completions (id SERIAL NOT NULL, game_progress_id INT NOT NULL, game_event_id INT DEFAULT NULL, question_id INT DEFAULT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, hearts_earned INT NOT NULL, points_earned INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_603E0BC99D8EC5CB ON player_event_completions (game_progress_id)');
        $this->addSql('CREATE INDEX IDX_603E0BC9DAB317AD ON player_event_completions (game_event_id)');
        $this->addSql('CREATE INDEX IDX_603E0BC91E27F6BF ON player_event_completions (question_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_player_event ON player_event_completions (game_progress_id, game_event_id)');
        $this->addSql('COMMENT ON COLUMN player_event_completions.completed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE player_gallery_items (id SERIAL NOT NULL, player_id INT NOT NULL, gallery_item_id INT NOT NULL, purchased_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D06345DA99E6F5DF ON player_gallery_items (player_id)');
        $this->addSql('CREATE INDEX IDX_D06345DA2A151376 ON player_gallery_items (gallery_item_id)');
        $this->addSql('CREATE TABLE players (id SERIAL NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, hearts INT DEFAULT 3 NOT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, shop_points INT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_264E43A6E7927C74 ON players (email)');
        $this->addSql('CREATE INDEX IDX_264E43A6A76ED395 ON players (user_id)');
        $this->addSql('COMMENT ON COLUMN players.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN players.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE purchase_history (id SERIAL NOT NULL, game_progress_id INT NOT NULL, trophy_id INT NOT NULL, cost_paid INT NOT NULL, purchased_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3C60BA329D8EC5CB ON purchase_history (game_progress_id)');
        $this->addSql('CREATE INDEX IDX_3C60BA32F59AEEEF ON purchase_history (trophy_id)');
        $this->addSql('COMMENT ON COLUMN purchase_history.purchased_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE questions (id SERIAL NOT NULL, zone_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, option_a VARCHAR(255) NOT NULL, option_b VARCHAR(255) NOT NULL, option_c VARCHAR(255) NOT NULL, option_d VARCHAR(255) NOT NULL, correct_answer VARCHAR(1) NOT NULL, category VARCHAR(50) NOT NULL, display_order INT NOT NULL, points_value INT NOT NULL, reward_hearts INT NOT NULL, reward_points INT NOT NULL, penalty_hearts INT NOT NULL, penalty_points INT NOT NULL, is_one_time_only BOOLEAN NOT NULL, step INT NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8ADC54D59F2C3FAB ON questions (zone_id)');
        $this->addSql('CREATE TABLE score (id SERIAL NOT NULL, player_id INT NOT NULL, value INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3299375199E6F5DF ON score (player_id)');
        $this->addSql('COMMENT ON COLUMN score.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE shop_items (id SERIAL NOT NULL, trophy_id INT NOT NULL, cost INT NOT NULL, stock INT NOT NULL, is_available BOOLEAN NOT NULL, display_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2608B31FF59AEEEF ON shop_items (trophy_id)');
        $this->addSql('CREATE TABLE trophies (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, description TEXT NOT NULL, type VARCHAR(50) NOT NULL, unlock_condition TEXT NOT NULL, heart_bonus INT NOT NULL, points_multiplier DOUBLE PRECISION NOT NULL, icon VARCHAR(100) NOT NULL, is_visible BOOLEAN NOT NULL, display_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trophy_unlocks (id SERIAL NOT NULL, game_progress_id INT NOT NULL, trophy_id INT NOT NULL, unlocked_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F8BFF6909D8EC5CB ON trophy_unlocks (game_progress_id)');
        $this->addSql('CREATE INDEX IDX_F8BFF690F59AEEEF ON trophy_unlocks (trophy_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_player_trophy ON trophy_unlocks (game_progress_id, trophy_id)');
        $this->addSql('COMMENT ON COLUMN trophy_unlocks.unlocked_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_verified BOOLEAN NOT NULL, profile_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE zones (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, description TEXT NOT NULL, display_order INT NOT NULL, min_points_to_unlock INT NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_events ADD CONSTRAINT FK_2EB2FA829F2C3FAB FOREIGN KEY (zone_id) REFERENCES zones (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_progress ADD CONSTRAINT FK_89450D6099E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_results ADD CONSTRAINT FK_A619B3B99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_results ADD CONSTRAINT FK_A619B3B1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BA9D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BAEF94B462 FOREIGN KEY (weapon_equipped_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BA7AF3F985 FOREIGN KEY (object1_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BA6846566B FOREIGN KEY (object2_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_equipment ADD CONSTRAINT FK_7334E0BAD0FA310E FOREIGN KEY (object3_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_event_completions ADD CONSTRAINT FK_603E0BC99D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_event_completions ADD CONSTRAINT FK_603E0BC9DAB317AD FOREIGN KEY (game_event_id) REFERENCES game_events (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_event_completions ADD CONSTRAINT FK_603E0BC91E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_gallery_items ADD CONSTRAINT FK_D06345DA99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player_gallery_items ADD CONSTRAINT FK_D06345DA2A151376 FOREIGN KEY (gallery_item_id) REFERENCES gallery (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_history ADD CONSTRAINT FK_3C60BA329D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_history ADD CONSTRAINT FK_3C60BA32F59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D59F2C3FAB FOREIGN KEY (zone_id) REFERENCES zones (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375199E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop_items ADD CONSTRAINT FK_2608B31FF59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trophy_unlocks ADD CONSTRAINT FK_F8BFF6909D8EC5CB FOREIGN KEY (game_progress_id) REFERENCES game_progress (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trophy_unlocks ADD CONSTRAINT FK_F8BFF690F59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gallery DROP CONSTRAINT FK_472B783AA76ED395');
        $this->addSql('ALTER TABLE game_events DROP CONSTRAINT FK_2EB2FA829F2C3FAB');
        $this->addSql('ALTER TABLE game_progress DROP CONSTRAINT FK_89450D6099E6F5DF');
        $this->addSql('ALTER TABLE game_results DROP CONSTRAINT FK_A619B3B99E6F5DF');
        $this->addSql('ALTER TABLE game_results DROP CONSTRAINT FK_A619B3B1E27F6BF');
        $this->addSql('ALTER TABLE player_equipment DROP CONSTRAINT FK_7334E0BA9D8EC5CB');
        $this->addSql('ALTER TABLE player_equipment DROP CONSTRAINT FK_7334E0BAEF94B462');
        $this->addSql('ALTER TABLE player_equipment DROP CONSTRAINT FK_7334E0BA7AF3F985');
        $this->addSql('ALTER TABLE player_equipment DROP CONSTRAINT FK_7334E0BA6846566B');
        $this->addSql('ALTER TABLE player_equipment DROP CONSTRAINT FK_7334E0BAD0FA310E');
        $this->addSql('ALTER TABLE player_event_completions DROP CONSTRAINT FK_603E0BC99D8EC5CB');
        $this->addSql('ALTER TABLE player_event_completions DROP CONSTRAINT FK_603E0BC9DAB317AD');
        $this->addSql('ALTER TABLE player_event_completions DROP CONSTRAINT FK_603E0BC91E27F6BF');
        $this->addSql('ALTER TABLE player_gallery_items DROP CONSTRAINT FK_D06345DA99E6F5DF');
        $this->addSql('ALTER TABLE player_gallery_items DROP CONSTRAINT FK_D06345DA2A151376');
        $this->addSql('ALTER TABLE players DROP CONSTRAINT FK_264E43A6A76ED395');
        $this->addSql('ALTER TABLE purchase_history DROP CONSTRAINT FK_3C60BA329D8EC5CB');
        $this->addSql('ALTER TABLE purchase_history DROP CONSTRAINT FK_3C60BA32F59AEEEF');
        $this->addSql('ALTER TABLE questions DROP CONSTRAINT FK_8ADC54D59F2C3FAB');
        $this->addSql('ALTER TABLE score DROP CONSTRAINT FK_3299375199E6F5DF');
        $this->addSql('ALTER TABLE shop_items DROP CONSTRAINT FK_2608B31FF59AEEEF');
        $this->addSql('ALTER TABLE trophy_unlocks DROP CONSTRAINT FK_F8BFF6909D8EC5CB');
        $this->addSql('ALTER TABLE trophy_unlocks DROP CONSTRAINT FK_F8BFF690F59AEEEF');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE game_events');
        $this->addSql('DROP TABLE game_progress');
        $this->addSql('DROP TABLE game_results');
        $this->addSql('DROP TABLE player_equipment');
        $this->addSql('DROP TABLE player_event_completions');
        $this->addSql('DROP TABLE player_gallery_items');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE purchase_history');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP TABLE shop_items');
        $this->addSql('DROP TABLE trophies');
        $this->addSql('DROP TABLE trophy_unlocks');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE zones');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
