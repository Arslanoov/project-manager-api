<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 * @psalm-suppress DeprecatedClass
 */
final class Version20200830151107 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE user_users ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_users ADD sign_up_confirm_token_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_users ADD sign_up_confirm_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN user_users.status IS \'(DC2Type:user_user_status)\'');
        $this->addSql('COMMENT ON COLUMN user_users.sign_up_confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_users DROP status');
        $this->addSql('ALTER TABLE user_users DROP sign_up_confirm_token_value');
        $this->addSql('ALTER TABLE user_users DROP sign_up_confirm_token_expires');
    }
}
