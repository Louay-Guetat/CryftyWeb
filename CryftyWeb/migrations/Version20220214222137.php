<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214222137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nft (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, creation_date DATETIME NOT NULL, category VARCHAR(255) NOT NULL, sub_category VARCHAR(255) NOT NULL, INDEX IDX_D9C7463C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE category ADD name VARCHAR(255) NOT NULL, ADD creation_date DATETIME NOT NULL, ADD sub_categories INT NOT NULL');
        $this->addSql('ALTER TABLE nft_comment ADD nft_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL, ADD post_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE nft_comment ADD CONSTRAINT FK_9D8D8644E813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE nft_comment ADD CONSTRAINT FK_9D8D864419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9D8D8644E813668D ON nft_comment (nft_id)');
        $this->addSql('CREATE INDEX IDX_9D8D864419EB6921 ON nft_comment (client_id)');
        $this->addSql('ALTER TABLE sub_category ADD category_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD creation_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_BCE3F79812469DE2 ON sub_category (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644E813668D');
        $this->addSql('DROP TABLE nft');
        $this->addSql('ALTER TABLE admin CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category DROP name, DROP creation_date, DROP sub_categories');
        $this->addSql('ALTER TABLE client CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE moderator CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D864419EB6921');
        $this->addSql('DROP INDEX IDX_9D8D8644E813668D ON nft_comment');
        $this->addSql('DROP INDEX IDX_9D8D864419EB6921 ON nft_comment');
        $this->addSql('ALTER TABLE nft_comment DROP nft_id, DROP client_id, DROP post_date');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('DROP INDEX IDX_BCE3F79812469DE2 ON sub_category');
        $this->addSql('ALTER TABLE sub_category DROP category_id, DROP name, DROP creation_date');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
