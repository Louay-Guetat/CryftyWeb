<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216142000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block ADD node_id INT NOT NULL, ADD previous_hash_id INT NOT NULL, ADD hash VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id)');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722EE63201 FOREIGN KEY (previous_hash_id) REFERENCES block (id)');
        $this->addSql('CREATE INDEX IDX_831B9722460D9FD7 ON block (node_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_831B9722EE63201 ON block (previous_hash_id)');
        $this->addSql('ALTER TABLE node ADD node_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE transfer ADD sender_id_id INT NOT NULL, ADD reciever_id_id INT NOT NULL, ADD amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C06061F7CF FOREIGN KEY (sender_id_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C0AE06B8F9 FOREIGN KEY (reciever_id_id) REFERENCES wallet (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4034A3C06061F7CF ON transfer (sender_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4034A3C0AE06B8F9 ON transfer (reciever_id_id)');
        $this->addSql('ALTER TABLE wallet ADD node_id_id INT NOT NULL, ADD wallet_address VARCHAR(255) NOT NULL, ADD balance DOUBLE PRECISION NOT NULL, ADD wallet_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F66EF0CAB FOREIGN KEY (node_id_id) REFERENCES node (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F66EF0CAB ON wallet (node_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722460D9FD7');
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722EE63201');
        $this->addSql('DROP INDEX IDX_831B9722460D9FD7 ON block');
        $this->addSql('DROP INDEX UNIQ_831B9722EE63201 ON block');
        $this->addSql('ALTER TABLE block DROP node_id, DROP previous_hash_id, DROP hash');
        $this->addSql('ALTER TABLE client CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE moderator CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE node DROP node_label');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C06061F7CF');
        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C0AE06B8F9');
        $this->addSql('DROP INDEX UNIQ_4034A3C06061F7CF ON transfer');
        $this->addSql('DROP INDEX UNIQ_4034A3C0AE06B8F9 ON transfer');
        $this->addSql('ALTER TABLE transfer DROP sender_id_id, DROP reciever_id_id, DROP amount');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F66EF0CAB');
        $this->addSql('DROP INDEX UNIQ_7C68921F66EF0CAB ON wallet');
        $this->addSql('ALTER TABLE wallet DROP node_id_id, DROP wallet_address, DROP balance, DROP wallet_label');
    }
}
