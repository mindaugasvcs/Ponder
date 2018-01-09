<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180108231029 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE poll_option_vote (id INT AUTO_INCREMENT NOT NULL, poll_option_id INT NOT NULL, voter_id INT NOT NULL, voter_ip VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_807C58856C13349B (poll_option_id), INDEX IDX_807C5885EBB4B8AD (voter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poll_option_vote ADD CONSTRAINT FK_807C58856C13349B FOREIGN KEY (poll_option_id) REFERENCES poll_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poll_option_vote ADD CONSTRAINT FK_807C5885EBB4B8AD FOREIGN KEY (voter_id) REFERENCES app_users (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE poll_option_vote');
    }
}
