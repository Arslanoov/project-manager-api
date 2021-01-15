<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 * @psalm-suppress DeprecatedClass
 */
final class Version20200816112606 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE todo_schedules ADD name VARCHAR(255) NOT NULL');
        $this->addSql('COMMENT ON COLUMN todo_schedules.name IS \'(DC2Type:todo_schedule_name)\'');
        $this->addSql('ALTER TABLE todo_schedule_tasks ALTER finished_steps DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE todo_schedules DROP name');
        $this->addSql('ALTER TABLE todo_schedule_tasks ALTER finished_steps SET DEFAULT 0');
    }
}
