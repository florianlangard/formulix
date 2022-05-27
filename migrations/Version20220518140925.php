<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518140925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE podium (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, qualifying_first_id INT DEFAULT NULL, qualifying_second_id INT DEFAULT NULL, qualifying_third_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_338B4C5371F7E88B (event_id), INDEX IDX_338B4C53EB42F8EC (qualifying_first_id), INDEX IDX_338B4C53ADFDF660 (qualifying_second_id), INDEX IDX_338B4C5377C34979 (qualifying_third_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C5371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C53EB42F8EC FOREIGN KEY (qualifying_first_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C53ADFDF660 FOREIGN KEY (qualifying_second_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C5377C34979 FOREIGN KEY (qualifying_third_id) REFERENCES prediction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE podium');
        $this->addSql('ALTER TABLE driver CHANGE fullname fullname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE driver_id driver_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code code VARCHAR(3) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE round round VARCHAR(2) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE season season VARCHAR(4) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE circuit_name circuit_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE locality locality VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(120) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country_code country_code VARCHAR(2) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prediction CHANGE time time VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE result CHANGE time time VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE score CHANGE season season VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE personaname personaname VARCHAR(32) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE twitch_id twitch_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
