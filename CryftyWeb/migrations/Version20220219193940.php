<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220219193940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_chat_user (group_chat_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EBBC17E99C9A2529 (group_chat_id), INDEX IDX_EBBC17E9A76ED395 (user_id), PRIMARY KEY(group_chat_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_chat (user_id INT NOT NULL, group_chat_id INT NOT NULL, INDEX IDX_EBA06F36A76ED395 (user_id), INDEX IDX_EBA06F369C9A2529 (group_chat_id), PRIMARY KEY(user_id, group_chat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_chat_user ADD CONSTRAINT FK_EBBC17E99C9A2529 FOREIGN KEY (group_chat_id) REFERENCES group_chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat_user ADD CONSTRAINT FK_EBBC17E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_chat ADD CONSTRAINT FK_EBA06F36A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_chat ADD CONSTRAINT FK_EBA06F369C9A2529 FOREIGN KEY (group_chat_id) REFERENCES group_chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block ADD node_id INT NOT NULL, ADD wallet_id INT DEFAULT NULL, ADD hash VARCHAR(255) NOT NULL, ADD previous_hash VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_831B9722460D9FD7 ON block (node_id)');
        $this->addSql('CREATE INDEX IDX_831B9722712520F3 ON block (wallet_id)');
        $this->addSql('ALTER TABLE blog_article DROP subtitle, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE contents contents VARCHAR(255) DEFAULT NULL, CHANGE author author VARCHAR(255) DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD nft_prod_id INT DEFAULT NULL, ADD client_id_id INT DEFAULT NULL, ADD wallets_id INT DEFAULT NULL, ADD total DOUBLE PRECISION NOT NULL, ADD quantite INT NOT NULL, ADD date_creation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7831EB5D FOREIGN KEY (nft_prod_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7DC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7C3B43BA3 FOREIGN KEY (wallets_id) REFERENCES wallet (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7831EB5D ON cart (nft_prod_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7DC2902E0 ON cart (client_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7C3B43BA3 ON cart (wallets_id)');
        $this->addSql('ALTER TABLE category ADD name VARCHAR(255) NOT NULL, ADD creation_date DATETIME NOT NULL, ADD nbr_nft INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD nom VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE group_chat ADD owner_id INT DEFAULT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE group_chat ADD CONSTRAINT FK_4CC7A9DA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_chat ADD CONSTRAINT FK_4CC7A9DABF396750 FOREIGN KEY (id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4CC7A9DA7E3C61F9 ON group_chat (owner_id)');
        $this->addSql('ALTER TABLE message ADD sender_id INT DEFAULT NULL, ADD conversation_id INT DEFAULT NULL, ADD contenu VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
        $this->addSql('ALTER TABLE nft ADD owner_id INT DEFAULT NULL, ADD category_id INT DEFAULT NULL, ADD sub_category_id INT DEFAULT NULL, ADD title VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD creation_date DATETIME NOT NULL, ADD image VARCHAR(255) NOT NULL, ADD likes INT NOT NULL');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463CF7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
        $this->addSql('CREATE INDEX IDX_D9C7463C7E3C61F9 ON nft (owner_id)');
        $this->addSql('CREATE INDEX IDX_D9C7463C12469DE2 ON nft (category_id)');
        $this->addSql('CREATE INDEX IDX_D9C7463CF7BFE87C ON nft (sub_category_id)');
        $this->addSql('ALTER TABLE nft_comment ADD nft_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD post_date DATETIME NOT NULL, ADD likes INT NOT NULL, ADD dislikes INT NOT NULL');
        $this->addSql('ALTER TABLE nft_comment ADD CONSTRAINT FK_9D8D8644E813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE nft_comment ADD CONSTRAINT FK_9D8D8644A76ED395 FOREIGN KEY (user_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9D8D8644E813668D ON nft_comment (nft_id)');
        $this->addSql('CREATE INDEX IDX_9D8D8644A76ED395 ON nft_comment (user_id)');
        $this->addSql('ALTER TABLE node ADD node_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE private_chat ADD sender_id INT DEFAULT NULL, ADD received_id INT DEFAULT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE private_chat ADD CONSTRAINT FK_F55F8614F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_chat ADD CONSTRAINT FK_F55F8614B821E5F5 FOREIGN KEY (received_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_chat ADD CONSTRAINT FK_F55F8614BF396750 FOREIGN KEY (id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F55F8614F624B39D ON private_chat (sender_id)');
        $this->addSql('CREATE INDEX IDX_F55F8614B821E5F5 ON private_chat (received_id)');
        $this->addSql('ALTER TABLE sub_category ADD category_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD creation_date DATETIME NOT NULL, ADD nbr_nft INT NOT NULL');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_BCE3F79812469DE2 ON sub_category (category_id)');
        $this->addSql('ALTER TABLE support_ticket ADD client_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD subject VARCHAR(255) NOT NULL, ADD message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D5319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_1F5A4D5319EB6921 ON support_ticket (client_id)');
        $this->addSql('ALTER TABLE transaction ADD cart_id_id INT DEFAULT NULL, ADD wallets_id INT DEFAULT NULL, ADD montant DOUBLE PRECISION NOT NULL, ADD client VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D120AEF35F FOREIGN KEY (cart_id_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1C3B43BA3 FOREIGN KEY (wallets_id) REFERENCES wallet (id)');
        $this->addSql('CREATE INDEX IDX_723705D120AEF35F ON transaction (cart_id_id)');
        $this->addSql('CREATE INDEX IDX_723705D1C3B43BA3 ON transaction (wallets_id)');
        $this->addSql('ALTER TABLE transfer ADD sender_id_id INT NOT NULL, ADD reciever_id_id INT NOT NULL, ADD amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C06061F7CF FOREIGN KEY (sender_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C0AE06B8F9 FOREIGN KEY (reciever_id_id) REFERENCES wallet (id)');
        $this->addSql('CREATE INDEX IDX_4034A3C06061F7CF ON transfer (sender_id_id)');
        $this->addSql('CREATE INDEX IDX_4034A3C0AE06B8F9 ON transfer (reciever_id_id)');
        $this->addSql('ALTER TABLE wallet ADD node_id_id INT DEFAULT NULL, ADD client_id INT NOT NULL, ADD wallet_address VARCHAR(255) NOT NULL, ADD balance DOUBLE PRECISION NOT NULL, ADD wallet_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F66EF0CAB FOREIGN KEY (node_id_id) REFERENCES node (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_7C68921F66EF0CAB ON wallet (node_id_id)');
        $this->addSql('CREATE INDEX IDX_7C68921F19EB6921 ON wallet (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE group_chat_user');
        $this->addSql('DROP TABLE user_group_chat');
        $this->addSql('ALTER TABLE admin CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722460D9FD7');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722712520F3');
        $this->addSql('DROP INDEX IDX_831B9722460D9FD7 ON block');
        $this->addSql('DROP INDEX IDX_831B9722712520F3 ON block');
        $this->addSql('ALTER TABLE block DROP node_id, DROP wallet_id, DROP hash, DROP previous_hash');
        $this->addSql('ALTER TABLE blog_article ADD subtitle VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contents contents VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE category category VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE author author VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE blog_comment CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE comment comment VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7831EB5D');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7DC2902E0');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7C3B43BA3');
        $this->addSql('DROP INDEX IDX_BA388B7831EB5D ON cart');
        $this->addSql('DROP INDEX UNIQ_BA388B7DC2902E0 ON cart');
        $this->addSql('DROP INDEX UNIQ_BA388B7C3B43BA3 ON cart');
        $this->addSql('ALTER TABLE cart DROP nft_prod_id, DROP client_id_id, DROP wallets_id, DROP total, DROP quantite, DROP date_creation');
        $this->addSql('ALTER TABLE category DROP name, DROP creation_date, DROP nbr_nft');
        $this->addSql('ALTER TABLE client CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE conversation DROP nom, DROP type');
        $this->addSql('ALTER TABLE group_chat DROP FOREIGN KEY FK_4CC7A9DA7E3C61F9');
        $this->addSql('ALTER TABLE group_chat DROP FOREIGN KEY FK_4CC7A9DABF396750');
        $this->addSql('DROP INDEX IDX_4CC7A9DA7E3C61F9 ON group_chat');
        $this->addSql('ALTER TABLE group_chat DROP owner_id, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('DROP INDEX IDX_B6BD307FF624B39D ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396 ON message');
        $this->addSql('ALTER TABLE message DROP sender_id, DROP conversation_id, DROP contenu, DROP created_at');
        $this->addSql('ALTER TABLE moderator CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C7E3C61F9');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C12469DE2');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463CF7BFE87C');
        $this->addSql('DROP INDEX IDX_D9C7463C7E3C61F9 ON nft');
        $this->addSql('DROP INDEX IDX_D9C7463C12469DE2 ON nft');
        $this->addSql('DROP INDEX IDX_D9C7463CF7BFE87C ON nft');
        $this->addSql('ALTER TABLE nft DROP owner_id, DROP category_id, DROP sub_category_id, DROP title, DROP description, DROP price, DROP creation_date, DROP image, DROP likes');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644E813668D');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644A76ED395');
        $this->addSql('DROP INDEX IDX_9D8D8644E813668D ON nft_comment');
        $this->addSql('DROP INDEX IDX_9D8D8644A76ED395 ON nft_comment');
        $this->addSql('ALTER TABLE nft_comment DROP nft_id, DROP user_id, DROP post_date, DROP likes, DROP dislikes');
        $this->addSql('ALTER TABLE node DROP node_label');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614F624B39D');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614B821E5F5');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614BF396750');
        $this->addSql('DROP INDEX IDX_F55F8614F624B39D ON private_chat');
        $this->addSql('DROP INDEX IDX_F55F8614B821E5F5 ON private_chat');
        $this->addSql('ALTER TABLE private_chat DROP sender_id, DROP received_id, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('DROP INDEX IDX_BCE3F79812469DE2 ON sub_category');
        $this->addSql('ALTER TABLE sub_category DROP category_id, DROP name, DROP creation_date, DROP nbr_nft');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D5319EB6921');
        $this->addSql('DROP INDEX IDX_1F5A4D5319EB6921 ON support_ticket');
        $this->addSql('ALTER TABLE support_ticket DROP client_id, DROP name, DROP email, DROP subject, DROP message');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D120AEF35F');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1C3B43BA3');
        $this->addSql('DROP INDEX IDX_723705D120AEF35F ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1C3B43BA3 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP cart_id_id, DROP wallets_id, DROP montant, DROP client, DROP email');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C06061F7CF');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C0AE06B8F9');
        $this->addSql('DROP INDEX IDX_4034A3C06061F7CF ON transfer');
        $this->addSql('DROP INDEX IDX_4034A3C0AE06B8F9 ON transfer');
        $this->addSql('ALTER TABLE transfer DROP sender_id_id, DROP reciever_id_id, DROP amount');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F66EF0CAB');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F19EB6921');
        $this->addSql('DROP INDEX IDX_7C68921F66EF0CAB ON wallet');
        $this->addSql('DROP INDEX IDX_7C68921F19EB6921 ON wallet');
        $this->addSql('ALTER TABLE wallet DROP node_id_id, DROP client_id, DROP wallet_address, DROP balance, DROP wallet_label');
    }
}
