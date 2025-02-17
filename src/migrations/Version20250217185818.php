<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

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
        $this->addSql(<<<SQL
INSERT INTO 
    book (name)
VALUES 
    ('Sherlock Holmes'),
    ('Harry Potter'),
    ('The Hunger Games'),
    ('Dune')
SQL
        );
        $this->addSql(<<<SQL
INSERT INTO 
    animal (name)
VALUES 
    ('Dog'),
    ('Cat'),
    ('Beaver'),
    ('Lion')
SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
