<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220305145219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_nft (client_id INT NOT NULL, nft_id INT NOT NULL, INDEX IDX_FE63D59819EB6921 (client_id), INDEX IDX_FE63D598E813668D (nft_id), PRIMARY KEY(client_id, nft_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_nft ADD CONSTRAINT FK_FE63D59819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_nft ADD CONSTRAINT FK_FE63D598E813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_nft ADD CONSTRAINT FK_E9D7557F1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_nft ADD CONSTRAINT FK_E9D7557FE813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat ADD CONSTRAINT FK_4CC7A9DA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_chat ADD CONSTRAINT FK_4CC7A9DABF396750 FOREIGN KEY (id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat_user ADD CONSTRAINT FK_EBBC17E99C9A2529 FOREIGN KEY (group_chat_id) REFERENCES group_chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_chat_user ADD CONSTRAINT FK_EBBC17E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE moderator ADD CONSTRAINT FK_6A30B268BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C38248176 FOREIGN KEY (currency_id) REFERENCES node (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463CF7BFE87C FOREIGN KEY (sub_category_id) REFERENCES sub_category (id)');
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
        $this->addSql('DROP TABLE client_nft');
        $this->addSql('ALTER TABLE admin CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE block CHANGE hash hash VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE previous_hash previous_hash VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog_article CHANGE title title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contents contents VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE category category VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE author author VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blog_comment CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE comment comment VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart CHANGE date_creation date_creation VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart_nft DROP FOREIGN KEY FK_E9D7557F1AD5CDBF');
        $this->addSql('ALTER TABLE cart_nft DROP FOREIGN KEY FK_E9D7557FE813668D');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE client CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE conversation CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE group_chat DROP FOREIGN KEY FK_4CC7A9DA7E3C61F9');
        $this->addSql('ALTER TABLE group_chat DROP FOREIGN KEY FK_4CC7A9DABF396750');
        $this->addSql('ALTER TABLE group_chat_user DROP FOREIGN KEY FK_EBBC17E99C9A2529');
        $this->addSql('ALTER TABLE group_chat_user DROP FOREIGN KEY FK_EBBC17E9A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message CHANGE contenu contenu VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE moderator DROP FOREIGN KEY FK_6A30B268BF396750');
        $this->addSql('ALTER TABLE moderator CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C38248176');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C7E3C61F9');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C12469DE2');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463CF7BFE87C');
        $this->addSql('ALTER TABLE nft CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644E813668D');
        $this->addSql('ALTER TABLE nft_comment DROP FOREIGN KEY FK_9D8D8644A76ED395');
        $this->addSql('ALTER TABLE nft_comment CHANGE comment comment VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE node CHANGE node_label node_label VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE abrv abrv VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614F624B39D');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614B821E5F5');
        $this->addSql('ALTER TABLE private_chat DROP FOREIGN KEY FK_F55F8614BF396750');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('ALTER TABLE sub_category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D5319EB6921');
        $this->addSql('ALTER TABLE support_ticket CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE subject subject VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE message message VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D120AEF35F');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1C3B43BA3');
        $this->addSql('ALTER TABLE transaction CHANGE client client VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C06061F7CF');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C0AE06B8F9');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_group_chat DROP FOREIGN KEY FK_EBA06F36A76ED395');
        $this->addSql('ALTER TABLE user_group_chat DROP FOREIGN KEY FK_EBA06F369C9A2529');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F66EF0CAB');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F19EB6921');
        $this->addSql('ALTER TABLE wallet CHANGE wallet_address wallet_address VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE wallet_label wallet_label VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
