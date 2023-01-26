<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112141537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, picture_id INT DEFAULT NULL, user_id INT NOT NULL, followers_count INT NOT NULL, following_count INT NOT NULL, nickname VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_C0155143EE45BDBF (picture_id), INDEX IDX_C0155143A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, with_user_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_659DF2AAAE83ED76 (with_user_id), INDEX IDX_659DF2AAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, text LONGTEXT NOT NULL, likes_count INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_like (id INT AUTO_INCREMENT NOT NULL, comment_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_8A55E25FF8697D13 (comment_id), INDEX IDX_8A55E25FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_5373C966A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_message (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_D76AB636EA9FDD75 (media_id), INDEX IDX_D76AB636537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, chat_id INT NOT NULL, user_id INT NOT NULL, type SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_B6BD307F1A9A7125 (chat_id), INDEX IDX_B6BD307FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, for_user_id INT DEFAULT NULL, text LONGTEXT NOT NULL, read_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_BF5476CA9B5BB4B8 (for_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, user_id INT NOT NULL, given_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, is_male TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_34DCD176F92F3E70 (country_id), INDEX IDX_34DCD176A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, user_id INT NOT NULL, text LONGTEXT DEFAULT NULL, likes_count INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, comments_count INT NOT NULL, INDEX IDX_5A8A6C8DEA9FDD75 (media_id), INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_like (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_653627B84B89032C (post_id), INDEX IDX_653627B8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_post (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_54B59E984B89032C (post_id), INDEX IDX_54B59E98A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, user_id INT NOT NULL, bg_color VARCHAR(6) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_EB560438EA9FDD75 (media_id), INDEX IDX_EB560438A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story_text (id INT AUTO_INCREMENT NOT NULL, story_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_AD26CBE9AA5D4036 (story_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, follow_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_A3C664D38711D3BC (follow_id), INDEX IDX_A3C664D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE text_message (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_921C1F40537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAAE83ED76 FOREIGN KEY (with_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media_message ADD CONSTRAINT FK_D76AB636EA9FDD75 FOREIGN KEY (media_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE media_message ADD CONSTRAINT FK_D76AB636537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DEA9FDD75 FOREIGN KEY (media_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B84B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_post ADD CONSTRAINT FK_54B59E984B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE saved_post ADD CONSTRAINT FK_54B59E98A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438EA9FDD75 FOREIGN KEY (media_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE story_text ADD CONSTRAINT FK_AD26CBE9AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D38711D3BC FOREIGN KEY (follow_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE text_message ADD CONSTRAINT FK_921C1F40537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FF8697D13');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176F92F3E70');
        $this->addSql('ALTER TABLE media_message DROP FOREIGN KEY FK_D76AB636537A1329');
        $this->addSql('ALTER TABLE text_message DROP FOREIGN KEY FK_921C1F40537A1329');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE post_like DROP FOREIGN KEY FK_653627B84B89032C');
        $this->addSql('ALTER TABLE saved_post DROP FOREIGN KEY FK_54B59E984B89032C');
        $this->addSql('ALTER TABLE story_text DROP FOREIGN KEY FK_AD26CBE9AA5D4036');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_like');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE media_message');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_like');
        $this->addSql('DROP TABLE saved_post');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE story_text');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE text_message');
    }
}
