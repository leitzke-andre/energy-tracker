<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924163824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, provider VARCHAR(255) NOT NULL, description VARCHAR(4000) DEFAULT NULL, start_date DATE NOT NULL --(DC2Type:date_immutable)
        , end_date DATE DEFAULT NULL --(DC2Type:date_immutable)
        , price_per_kwh INTEGER NOT NULL, additional_costs INTEGER NOT NULL, initial_reading INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE reading (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contract_id INTEGER NOT NULL, reading_date DATE NOT NULL --(DC2Type:date_immutable)
        , valuein_kwh NUMERIC(7, 1) NOT NULL, comment VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_C11AFC412576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C11AFC412576E0FD ON reading (contract_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE reading');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
