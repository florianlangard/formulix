<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518145930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE podium ADD race_first_id INT DEFAULT NULL, ADD race_second_id INT DEFAULT NULL, ADD race_third_id INT DEFAULT NULL, ADD event_first_id INT DEFAULT NULL, ADD event_second_id INT DEFAULT NULL, ADD event_third_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C53B857A3B6 FOREIGN KEY (race_first_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C5326105BD1 FOREIGN KEY (race_second_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C5324D61223 FOREIGN KEY (race_third_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C53AEA18B8F FOREIGN KEY (event_first_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C53790325F1 FOREIGN KEY (event_second_id) REFERENCES prediction (id)');
        $this->addSql('ALTER TABLE podium ADD CONSTRAINT FK_338B4C5332203A1A FOREIGN KEY (event_third_id) REFERENCES prediction (id)');
        $this->addSql('CREATE INDEX IDX_338B4C53B857A3B6 ON podium (race_first_id)');
        $this->addSql('CREATE INDEX IDX_338B4C5326105BD1 ON podium (race_second_id)');
        $this->addSql('CREATE INDEX IDX_338B4C5324D61223 ON podium (race_third_id)');
        $this->addSql('CREATE INDEX IDX_338B4C53AEA18B8F ON podium (event_first_id)');
        $this->addSql('CREATE INDEX IDX_338B4C53790325F1 ON podium (event_second_id)');
        $this->addSql('CREATE INDEX IDX_338B4C5332203A1A ON podium (event_third_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver CHANGE fullname fullname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE driver_id driver_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code code VARCHAR(3) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE round round VARCHAR(2) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE season season VARCHAR(4) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE circuit_name circuit_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE locality locality VARCHAR(64) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(120) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country_code country_code VARCHAR(2) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C53B857A3B6');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C5326105BD1');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C5324D61223');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C53AEA18B8F');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C53790325F1');
        $this->addSql('ALTER TABLE podium DROP FOREIGN KEY FK_338B4C5332203A1A');
        $this->addSql('DROP INDEX IDX_338B4C53B857A3B6 ON podium');
        $this->addSql('DROP INDEX IDX_338B4C5326105BD1 ON podium');
        $this->addSql('DROP INDEX IDX_338B4C5324D61223 ON podium');
        $this->addSql('DROP INDEX IDX_338B4C53AEA18B8F ON podium');
        $this->addSql('DROP INDEX IDX_338B4C53790325F1 ON podium');
        $this->addSql('DROP INDEX IDX_338B4C5332203A1A ON podium');
        $this->addSql('ALTER TABLE podium DROP race_first_id, DROP race_second_id, DROP race_third_id, DROP event_first_id, DROP event_second_id, DROP event_third_id');
        $this->addSql('ALTER TABLE prediction CHANGE time time VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE result CHANGE time time VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE score CHANGE season season VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE personaname personaname VARCHAR(32) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE twitch_id twitch_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
