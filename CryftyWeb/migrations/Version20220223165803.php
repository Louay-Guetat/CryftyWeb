<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223165803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, node_id INT NOT NULL, wallet_id INT DEFAULT NULL, hash VARCHAR(255) NOT NULL, previous_hash VARCHAR(255) NOT NULL, INDEX IDX_831B9722460D9FD7 (node_id), INDEX IDX_831B9722712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, contents VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_comment (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, comment VARCHAR(255) NOT NULL, date_c DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, wallets_id INT DEFAULT NULL, client_id_id INT DEFAULT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_BA388B7C3B43BA3 (wallets_id), UNIQUE INDEX UNIQ_BA388B7DC2902E0 (client_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_nft (cart_id INT NOT NULL, nft_id INT NOT NULL, INDEX IDX_E9D7557F1AD5CDBF (cart_id), INDEX IDX_E9D7557FE813668D (nft_id), PRIMARY KEY(cart_id, nft_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, nbr_nft INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_chat (id INT NOT NULL, owner_id INT DEFAULT NULL, INDEX IDX_4CC7A9DA7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_chat_user (group_chat_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EBBC17E99C9A2529 (group_chat_id), INDEX IDX_EBBC17E9A76ED395 (user_id), PRIMARY KEY(group_chat_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, conversation_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307F9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moderator (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, category_id INT DEFAULT NULL, sub_category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, creation_date DATETIME NOT NULL, image VARCHAR(255) NOT NULL, likes INT NOT NULL, INDEX IDX_D9C7463C7E3C61F9 (owner_id), INDEX IDX_D9C7463C12469DE2 (category_id), INDEX IDX_D9C7463CF7BFE87C (sub_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_cart (nft_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_48325459E813668D (nft_id), INDEX IDX_483254591AD5CDBF (cart_id), PRIMARY KEY(nft_id, cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_comment (id INT AUTO_INCREMENT NOT NULL, nft_id INT DEFAULT NULL, user_id INT DEFAULT NULL, post_date DATETIME NOT NULL, likes INT NOT NULL, dislikes INT NOT NULL, INDEX IDX_9D8D8644E813668D (nft_id), INDEX IDX_9D8D8644A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE node (id INT AUTO_INCREMENT NOT NULL, node_label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE private_chat (id INT NOT NULL, sender_id INT DEFAULT NULL, received_id INT DEFAULT NULL, INDEX IDX_F55F8614F624B39D (sender_id), INDEX IDX_F55F8614B821E5F5 (received_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_category (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, nbr_nft INT NOT NULL, INDEX IDX_BCE3F79812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support_ticket (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_1F5A4D5319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, cart_id_id INT DEFAULT NULL, wallets_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, datetransaction DATETIME NOT NULL, INDEX IDX_723705D120AEF35F (cart_id_id), INDEX IDX_723705D1C3B43BA3 (wallets_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_history (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transfer (id INT AUTO_INCREMENT NOT NULL, sender_id_id INT NOT NULL, reciever_id_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_4034A3C06061F7CF (sender_id_id), INDEX IDX_4034A3C0AE06B8F9 (reciever_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_chat (user_id INT NOT NULL, group_chat_id INT NOT NULL, INDEX IDX_EBA06F36A76ED395 (user_id), INDEX IDX_EBA06F369C9A2529 (group_chat_id), PRIMARY KEY(user_id, group_chat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, node_id_id INT DEFAULT NULL, client_id INT NOT NULL, wallet_address VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, wallet_label VARCHAR(255) NOT NULL, INDEX IDX_7C68921F66EF0CAB (node_id_id), INDEX IDX_7C68921F19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7C3B43BA3 FOREIGN KEY (wallets_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE cart_nft ADD CONSTRAINT FK_E9D7557F1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_nft ADD CONSTRAINT FK_E9D7557FE813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat ADD CONSTRAINT FK_4CC7A9DA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_chat ADD CONSTRAINT FK_4CC7A9DABF396750 FOREIGN KEY (id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat_user ADD CONSTRAINT FK_EBBC17E99C9A2529 FOREIGN KEY (group_chat_id) REFERENCES group_chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat_user ADD CONSTRAINT FK_EBBC17E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463CF7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('ALTER TABLE nft_cart ADD CONSTRAINT FK_48325459E813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft_cart ADD CONSTRAINT FK_483254591AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft_comment ADD CONSTRAINT FK_9D8D8644E813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE nft_comment ADD CONSTRAINT FK_9D8D8644A76ED395 FOREIGN KEY (user_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE private_chat ADD CONSTRAINT FK_F55F8614F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_chat ADD CONSTRAINT FK_F55F8614B821E5F5 FOREIGN KEY (received_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_chat ADD CONSTRAINT FK_F55F8614BF396750 FOREIGN KEY (id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D5319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D120AEF35F FOREIGN KEY (cart_id_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1C3B43BA3 FOREIGN KEY (wallets_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C06061F7CF FOREIGN KEY (sender_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C0AE06B8F9 FOREIGN KEY (reciever_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE user_group_chat ADD CONSTRAINT FK_EBA06F36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_chat ADD CONSTRAINT FK_EBA06F369C9A2529 FOREIGN KEY (group_chat_id) REFERENCES group_chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F66EF0CAB FOREIGN KEY (node_id_id) REFERENCES node (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_nft DROP FOREIGN KEY FK_E9D7557F1AD5CDBF');
        $this->addSql('ALTER TABLE nft_cart DROP FOREIGN KEY FK_483254591AD5CDBF');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D120AEF35F');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C12469DE2');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7DC2902E0');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C7E3C61F9');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644A76ED395');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D5319EB6921');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F19EB6921');
        $this->addSql('ALTER TABLE group_chat DROP FOREIGN KEY FK_4CC7A9DABF396750');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614BF396750');
        $this->addSql('ALTER TABLE group_chat_user DROP FOREIGN KEY FK_EBBC17E99C9A2529');
        $this->addSql('ALTER TABLE user_group_chat DROP FOREIGN KEY FK_EBA06F369C9A2529');
        $this->addSql('ALTER TABLE cart_nft DROP FOREIGN KEY FK_E9D7557FE813668D');
        $this->addSql('ALTER TABLE nft_cart DROP FOREIGN KEY FK_48325459E813668D');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644E813668D');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722460D9FD7');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F66EF0CAB');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463CF7BFE87C');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE group_chat DROP FOREIGN KEY FK_4CC7A9DA7E3C61F9');
        $this->addSql('ALTER TABLE group_chat_user DROP FOREIGN KEY FK_EBBC17E9A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268BF396750');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614F624B39D');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614B821E5F5');
        $this->addSql('ALTER TABLE user_group_chat DROP FOREIGN KEY FK_EBA06F36A76ED395');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722712520F3');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7C3B43BA3');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1C3B43BA3');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C06061F7CF');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C0AE06B8F9');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE block');
        $this->addSql('DROP TABLE blog_article');
        $this->addSql('DROP TABLE blog_comment');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_nft');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE group_chat');
        $this->addSql('DROP TABLE group_chat_user');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE moderator');
        $this->addSql('DROP TABLE nft');
        $this->addSql('DROP TABLE nft_cart');
        $this->addSql('DROP TABLE nft_comment');
        $this->addSql('DROP TABLE node');
        $this->addSql('DROP TABLE private_chat');
        $this->addSql('DROP TABLE sub_category');
        $this->addSql('DROP TABLE support_ticket');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_history');
        $this->addSql('DROP TABLE transfer');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_group_chat');
        $this->addSql('DROP TABLE wallet');
    }
}
