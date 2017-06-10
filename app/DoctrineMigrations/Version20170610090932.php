<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170610090932 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(25) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookshop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookshop_book_relation (bookshop_id INT NOT NULL, book_id INT NOT NULL, amount_sold INT NOT NULL, INDEX IDX_7B05A61D9DF228D3 (bookshop_id), INDEX IDX_7B05A61D16A2B381 (book_id), PRIMARY KEY(bookshop_id, book_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookshop_book_relation ADD CONSTRAINT FK_7B05A61D9DF228D3 FOREIGN KEY (bookshop_id) REFERENCES bookshop (id)');
        $this->addSql('ALTER TABLE bookshop_book_relation ADD CONSTRAINT FK_7B05A61D16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bookshop_book_relation DROP FOREIGN KEY FK_7B05A61D16A2B381');
        $this->addSql('ALTER TABLE bookshop_book_relation DROP FOREIGN KEY FK_7B05A61D9DF228D3');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE bookshop');
        $this->addSql('DROP TABLE bookshop_book_relation');
    }
}
