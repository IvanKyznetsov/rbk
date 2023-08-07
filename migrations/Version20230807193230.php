<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807193230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE rbk_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rbk_url_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rbk_product (id INT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, body VARCHAR(30000) NOT NULL, url VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rbk_url (id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE rbk_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rbk_url_id_seq CASCADE');
        $this->addSql('DROP TABLE rbk_product');
        $this->addSql('DROP TABLE rbk_url');
    }
}
