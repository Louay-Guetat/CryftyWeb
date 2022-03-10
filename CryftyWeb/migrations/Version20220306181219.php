<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220306181219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_nft (client_id INT NOT NULL, nft_id INT NOT NULL, INDEX IDX_FE63D59819EB6921 (client_id), INDEX IDX_FE63D598E813668D (nft_id), PRIMARY KEY(client_id, nft_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_client (nft_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_88DC0759E813668D (nft_id), INDEX IDX_88DC075919EB6921 (client_id), PRIMARY KEY(nft_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_nft ADD CONSTRAINT FK_FE63D59819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_nft ADD CONSTRAINT FK_FE63D598E813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft_client ADD CONSTRAINT FK_88DC0759E813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft_client ADD CONSTRAINT FK_88DC075919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE transaction_history');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7831EB5D');
        $this->addSql('DROP INDEX IDX_BA388B7831EB5D ON cart');
        $this->addSql('ALTER TABLE cart DROP nft_prod_id, DROP quantite, CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE category ADD nbr_sub_category INT NOT NULL');
        $this->addSql('ALTER TABLE client DROP nfts');
        $this->addSql('ALTER TABLE message CHANGE conversation_id conversation_id INT NOT NULL');
        $this->addSql('ALTER TABLE nft ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C38248176 FOREIGN KEY (currency_id) REFERENCES node (id)');
        $this->addSql('CREATE INDEX IDX_D9C7463C38248176 ON nft (currency_id)');
        $this->addSql('ALTER TABLE nft_comment ADD comment VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE node ADD coin_code VARCHAR(5) NOT NULL, ADD node_reward DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE support_ticket ADD client_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD subject VARCHAR(255) NOT NULL, ADD message VARCHAR(255) NOT NULL, ADD etat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D5319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_1F5A4D5319EB6921 ON support_ticket (client_id)');
        $this->addSql('ALTER TABLE transaction ADD datetransaction DATETIME NOT NULL, DROP client, DROP email');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C0AE06B8F9');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C06061F7CF');
        $this->addSql('ALTER TABLE transfer ADD transfer_date DATETIME NOT NULL, CHANGE sender_id_id sender_id_id INT DEFAULT NULL, CHANGE reciever_id_id reciever_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C0AE06B8F9 FOREIGN KEY (reciever_id_id) REFERENCES wallet (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C06061F7CF FOREIGN KEY (sender_id_id) REFERENCES wallet (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE wallet ADD wallet_image_file_name VARCHAR(255) DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_history (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE client_nft');
        $this->addSql('DROP TABLE nft_client');
        $this->addSql('ALTER TABLE cart ADD nft_prod_id INT DEFAULT NULL, ADD quantite INT NOT NULL, CHANGE date_creation date_creation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7831EB5D FOREIGN KEY (nft_prod_id) REFERENCES nft (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7831EB5D ON cart (nft_prod_id)');
        $this->addSql('ALTER TABLE category DROP nbr_sub_category');
        $this->addSql('ALTER TABLE client ADD nfts INT NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE conversation_id conversation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C38248176');
        $this->addSql('DROP INDEX IDX_D9C7463C38248176 ON nft');
        $this->addSql('ALTER TABLE nft DROP currency_id');
        $this->addSql('ALTER TABLE nft_comment DROP comment');
        $this->addSql('ALTER TABLE node DROP coin_code, DROP node_reward');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D5319EB6921');
        $this->addSql('DROP INDEX IDX_1F5A4D5319EB6921 ON support_ticket');
        $this->addSql('ALTER TABLE support_ticket DROP client_id, DROP name, DROP email, DROP subject, DROP message, DROP etat');
        $this->addSql('ALTER TABLE transaction ADD client VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, DROP datetransaction');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C06061F7CF');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C0AE06B8F9');
        $this->addSql('ALTER TABLE transfer DROP transfer_date, CHANGE sender_id_id sender_id_id INT NOT NULL, CHANGE reciever_id_id reciever_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C06061F7CF FOREIGN KEY (sender_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C0AE06B8F9 FOREIGN KEY (reciever_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE user DROP is_active');
        $this->addSql('ALTER TABLE wallet DROP wallet_image_file_name, DROP is_active');
    }
}
