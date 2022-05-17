<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516160502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result ADD finished_first_id INT DEFAULT NULL, ADD finished_second_id INT DEFAULT NULL, ADD finished_third_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11384A91840 FOREIGN KEY (finished_first_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11372F2F243 FOREIGN KEY (finished_second_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131828A9D5 FOREIGN KEY (finished_third_id) REFERENCES driver (id)');
        $this->addSql('CREATE INDEX IDX_136AC11384A91840 ON result (finished_first_id)');
        $this->addSql('CREATE INDEX IDX_136AC11372F2F243 ON result (finished_second_id)');
        $this->addSql('CREATE INDEX IDX_136AC1131828A9D5 ON result (finished_third_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver CHANGE fullname fullname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE driver_id driver_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code code VARCHAR(3) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE round round VARCHAR(2) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE season season VARCHAR(4) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE circuit_name circuit_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE locality locality VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(120) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country_code country_code VARCHAR(2) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prediction CHANGE time time VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11384A91840');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11372F2F243');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1131828A9D5');
        $this->addSql('DROP INDEX IDX_136AC11384A91840 ON result');
        $this->addSql('DROP INDEX IDX_136AC11372F2F243 ON result');
        $this->addSql('DROP INDEX IDX_136AC1131828A9D5 ON result');
        $this->addSql('ALTER TABLE result DROP finished_first_id, DROP finished_second_id, DROP finished_third_id, CHANGE time time VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE score CHANGE season season VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE personaname personaname VARCHAR(32) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE twitch_id twitch_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
