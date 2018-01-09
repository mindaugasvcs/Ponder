<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180108185819 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE poll (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, hits_count INT NOT NULL, INDEX IDX_84BCFA45F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poll_option (id INT AUTO_INCREMENT NOT NULL, poll_id INT NOT NULL, sequence INT NOT NULL, votes_count INT NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_B68343EB3C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(64) NOT NULL, username VARCHAR(32) NOT NULL, password VARCHAR(64) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C2502824E7927C74 (email), UNIQUE INDEX UNIQ_C2502824F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poll ADD CONSTRAINT FK_84BCFA45F675F31B FOREIGN KEY (author_id) REFERENCES app_users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE poll_option DROP FOREIGN KEY FK_B68343EB3C947C0F');
        $this->addSql('ALTER TABLE poll DROP FOREIGN KEY FK_84BCFA45F675F31B');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE poll_option');
        $this->addSql('DROP TABLE app_users');
    }
}
