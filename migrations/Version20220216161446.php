<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216161446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, pole_id INT NOT NULL, event_id INT NOT NULL, time VARCHAR(30) NOT NULL, INDEX IDX_136AC113419C3385 (pole_id), UNIQUE INDEX UNIQ_136AC11371F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113419C3385 FOREIGN KEY (pole_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE result');
        $this->addSql('ALTER TABLE driver CHANGE fullname fullname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE driver_id driver_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code code VARCHAR(3) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE round round VARCHAR(2) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE season season VARCHAR(4) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE circuit_name circuit_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE locality locality VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(120) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prediction CHANGE time time VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE personaname personaname VARCHAR(32) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
