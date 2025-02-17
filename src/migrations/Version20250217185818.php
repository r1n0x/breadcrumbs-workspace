<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217185818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Load initial database contents';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
INSERT INTO 
    product (name)
VALUES 
    ('Chicken'),
    ('Duck'),
    ('Tomatoes'),
    ('Apple')
SQL
        );

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
