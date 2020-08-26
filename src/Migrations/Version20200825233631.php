<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825233631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(15) NOT NULL, user_password VARCHAR(255) NOT NULL, doctor_first_name VARCHAR(255) NOT NULL, doctor_last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, fk_doctor_id INT NOT NULL, customer_first_name VARCHAR(255) NOT NULL, customer_reservation_code VARCHAR(255) NOT NULL, is_in_appointment TINYINT(1) NOT NULL, appointment_is_finished TINYINT(1) NOT NULL, appointment_time DATETIME NOT NULL, INDEX IDX_81398E09E6AD2C87 (fk_doctor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tiny_pizza (id INT AUTO_INCREMENT NOT NULL, no VARCHAR(2555) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09E6AD2C87 FOREIGN KEY (fk_doctor_id) REFERENCES doctor (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09E6AD2C87');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE tiny_pizza');
    }
}
