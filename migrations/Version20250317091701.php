<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317091701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE customers (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL,
                address VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
           )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE customers');
    }
}
